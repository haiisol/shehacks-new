<?php

namespace App\Controllers\Auth;

use App\Controllers\FrontController;
use Config\Services;
use Config\Database;
use App\Models\MainModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Throwable;

class Login extends FrontController
{

    protected $session;
    protected $db;
    protected $mainModel;
    function __construct()
    {
        $this->session = Services::session();
        $this->db = Database::connect();
        $this->mainModel = new MainModel();
    }

    public function index()
    {

        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        $referer = sanitize_header($referer);

        if ($this->session->get('logged_in_front') === true) {
            return redirect()->to(base_url('dashboard'));
        }

        $data = [
            'page' => 'auth/login'
        ];

        $this->data = array_merge($this->data, $data);

        return view('index', $this->data);
    }

    function login_verify()
    {
        if (!$this->session->get('2fa_id_user')) {
            return redirect()->to(base_url('login'));
        }

        $data = [
            'title' => '2FA Verify',
            'page' => 'auth/login_verify'
        ];

        $this->data = array_merge($this->data, $data);

        return view('index', $this->data);
    }

    function post_login()
    {
        try {
            $email = secure_input($this->request->getPost('email'));
            $password = $this->request->getPost('password');
            $uri_string = secure_input($this->request->getPost('uri_string'));

            $safeReferer = sanitize_header($this->request->getServer('HTTP_REFERER'));

            if (!str_contains($safeReferer, base_url())) {
                $safeReferer = '';
            }

            $ip = $this->request->getIPAddress();

            // -------- RATE LIMIT --------
            $attemptFailed = $this->mainModel->get_login_attempts($ip, $email, 'failed');
            if ($attemptFailed >= 5) {
                return json_response([
                    'status' => 0,
                    'message' => 'Terlalu banyak percobaan login. Coba lagi dalam 15 menit.'
                ]);
            }

            $attemptSuccess = $this->mainModel->get_login_attempts($ip, $email, 'success');
            if ($attemptSuccess >= 3) {
                return json_response([
                    'status' => 0,
                    'message' => 'Terlalu banyak permintaan OTP. Coba lagi dalam 15 menit.'
                ]);
            }

            // ---------- CHECK USER ----------
            $user = $this->db->table('tb_user')
                ->select('id_user, email, password, status')
                ->where('email', $email)
                ->get()
                ->getRowArray();

            if ($user && md5($password) === $user['password']) {

                if ((int) $user['status'] === 0) {
                    $this->mainModel->record_login_attempt($ip, $email, 'failed');
                    sleep(1);

                    return json_response([
                        'status' => 0,
                        'message' => 'Gagal, Akun email belum diverifikasi.'
                    ]);
                }

                $this->fa_handle($user, $uri_string);
                $this->mainModel->record_login_attempt($ip, $email, 'success');
                $this->mainModel->clear_login_attempts($ip, $email, 'failed');

                return json_response([
                    'status' => 1,
                    'message' => 'Success'
                ]);

            } else {

                $this->mainModel->record_login_attempt($ip, $email, 'failed');
                sleep(1);

                return json_response([
                    'status' => 0,
                    'message' => 'Email Pengguna atau Kata Sandi salah.'
                ]);
            }

        } catch (Throwable $e) {

            log_message('error', 'Login error: ' . $e->getMessage());

            return json_response([
                'status' => 0,
                'message' => 'Email Pengguna atau Kata Sandi salah.'
            ]);
        }
    }

    function fa_handle($data, $uri_string)
    {
        $code = rand(100000, 999999);

        $insert = [
            'id_user' => $data['id_user'],
            'code' => $code,
            'access_policy' => 'FE',
            'code_encrypt' => encrypt_url($code),
            'date_create' => date('Y-m-d H:i:s'),
            'date_expired' => date('Y-m-d H:i:s', strtotime('+10 minutes'))
        ];

        $this->db->table('tb_user_2fa')->insert($insert);

        $email = [
            'id_user' => $data['id_user'],
            'code' => $code
        ];

        $this->send_email($email);

        $this->session->set('2fa_id_user', encrypt_url($data['id_user']));
        $this->session->set('uri_string', $uri_string);
    }

    function post_login_verify()
    {
        $validation = Services::validation();

        $ip = $this->request->getIPAddress();
        $kode = $this->request->getPost('kode');

        $validation->setRules([
            'kode' => 'required|numeric'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return json_response([
                'status' => 0,
                'message' => $validation->getErrors()
            ]);
        }

        $dateNow = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
        $dateNow = $dateNow->format('Y-m-d H:i:s');

        $sess2faId = decrypt_url($this->session->get('2fa_id_user'));
        $uriString = $this->session->get('uri_string');

        if (!$sess2faId) {
            return json_response([
                'status' => 0,
                'message' => 'Failed, session not found.',
                'redirect' => base_url('login')
            ]);
        }

        $builder = $this->db->table('tb_user_2fa');
        $builder->select('id,id_user,code_encrypt,date_expired');
        $builder->where('id_user', $sess2faId);
        $builder->where('code', $kode);
        $builder->where('access_policy', 'FE');
        $getUser2FA = $builder->get()->getRowArray();

        if (!$getUser2FA) {
            return json_response([
                'status' => 0,
                'message' => 'Failed, code not match.',
                'redirect' => base_url('login')
            ]);
        }

        if (strtotime($getUser2FA['date_expired']) <= strtotime($dateNow)) {
            return json_response([
                'status' => 0,
                'message' => 'Gagal, kode OTP sudah kadaluarsa.',
                'redirect' => base_url('login')
            ]);
        }

        // success login
        $this->session->set([
            'key_auth' => encrypt_url($getUser2FA['id_user']),
            'key_token' => $getUser2FA['code_encrypt'],
            'logged_in_front' => true
        ]);

        $redirect = $uriString ?: base_url('dashboard');

        // update last login
        $this->db->table('tb_user')
            ->where('id_user', $getUser2FA['id_user'])
            ->update([
                'last_login' => $dateNow
            ]);

        // update 2FA verified time
        $this->db->table('tb_user_2fa')
            ->where('id', $getUser2FA['id'])
            ->update([
                'date_verification' => $dateNow
            ]);

        // clear session
        $this->session->remove('2fa_id_user');
        $this->session->remove('uri_string');

        // clear attempts
        $userEmail = $this->db->table('tb_user')
            ->select('email')
            ->where('id_user', $sess2faId)
            ->get()
            ->getRowArray();

        $this->mainModel->clear_login_attempts($ip, $userEmail['email'], 'success');

        return json_response([
            'status' => 1,
            'message' => 'Success',
            'redirect' => $redirect
        ]);
    }

    function send_email($data)
    {
        $getUser = $this->db->table('tb_user')
            ->select('email')
            ->where('id_user', $data['id_user'])
            ->get()
            ->getRowArray();


        $dataEmail = [
            'code' => $data['code'],
            'email' => $getUser['email'],
            'param' => 'Login'
        ];

        $message = view('email/2fa_email_login', $dataEmail);

        // get smtp config
        $getKonf = $this->db->table('tb_admin_konf_email')
            ->where('id', 1)
            ->get()
            ->getRowArray();

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $getKonf['host'];
            $mail->SMTPAuth = (bool) $getKonf['smtpauth'];
            $mail->Username = $getKonf['email'];
            $mail->Password = $getKonf['password'];
            $mail->SMTPSecure = $getKonf['smtpsecure'];
            $mail->Port = $getKonf['port'];

            $mail->setFrom($getKonf['email'], $getKonf['setfrom']);
            $mail->addAddress($getUser['email']);

            $mail->isHTML(true);
            $mail->Subject = $data['code'] . " - Kode akses login akun SheHacks";
            // $mail->AddEmbeddedImage(FCPATH.'assets/front/img/Image-registrasi-2024.png', 'logo_email');

            $mail->MsgHTML(stripslashes($message));

            $mail->send();

        } catch (Exception $e) {
            log_message('error', 'Email failed: ' . $mail->ErrorInfo);
        }
    }

    function send_email_text($data)
    {
        $email = $data['user']['email'];
        $kode = $data['code'];

        $getKonf = $this->db->table('tb_admin_konf_email')
            ->select('email')
            ->where('id', 1)
            ->get()
            ->getRowArray();

        $from = $getKonf['email'];
        $to = $email;

        $subject = $kode . " - Kode akses login akun SheHacks";

        $message = "
            Halo,

            Anda dapat menyalin dan menggunakan kode dibawah ini untuk akses login akun Shehacks :

            {$kode}

            Kode ini akan kedaluwarsa setelah 10 menit atau jika Anda login ulang.

            Terima Kasih,

            Tim SheHacks";

        $headers = "From:" . $from;

        mail($to, $subject, $message, $headers);
    }

    function logout()
    {
        $idUser = key_auth();
        $keyToken = $this->session->get('key_token');

        $this->db->table('tb_user_2fa')
            ->set('logout_status', 'true')
            ->where('access_policy', 'FE')
            ->where('id_user', $idUser)
            ->where('code_encrypt', $keyToken)
            ->update();

        $this->session->remove('key_auth');
        $this->session->remove('key_token');
        $this->session->remove('uri_string');
        $this->session->remove('logged_in_front');
        $this->session->destroy();

        return redirect()->to('/');
    }


    // --------------------------- change password --------------------------- 
    function cek_password_lama()
    {
        $idUser = key_auth();

        $row = $this->db->table('tb_user')
            ->select('password')
            ->where('id_user', $idUser)
            ->get()
            ->getRowArray();

        $inputPassword = $this->request->getPost('value');

        if ($row && $row['password'] == md5($inputPassword)) {
            $response['status'] = 1;
        } else {
            $response['status'] = 0;
        }

        return json_response($response);
    }
    // --------------------------- end change password --------------------------- 

}
