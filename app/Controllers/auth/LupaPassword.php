<?php

namespace App\Controllers\Auth;

use App\Controllers\FrontController;
use App\Models\MainModel;
use Config\Database;
use Config\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class LupaPassword extends FrontController
{

    protected $db;
    protected $mainModel;
    protected $session;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->mainModel = new MainModel();
        $this->session = Services::session();
    }

    // --------------------------- reset password ---------------------------
    public function index()
    {
        $data = [
            'title' => 'Lupa Password',
            'page' => 'auth/lupa_password'
        ];

        $this->data = array_merge($this->data, $data);
        return view('index', $this->data);
    }

    public function post_reset_password()
    {
        $email = $this->request->getPost('email');
        $ip = $this->request->getIPAddress();

        // Validation rules
        $rules = ['email' => 'required|valid_email'];

        if (!$this->validate($rules)) {
            $response = [
                'status' => 0,
                'message' => $this->validator->listErrors()
            ];
            return json_response($response); // Using your helper
        }

        $get_user = $this->db->table('tb_user')->getWhere(['email' => $email])->getRowArray();

        if ($get_user) {
            $attempts = $this->mainModel->get_reset_password_attempts($ip, $email, 'submit_form');

            if ($attempts >= 3) {
                return json_response([
                    'status' => 0,
                    'message' => 'Terlalu banyak permintaan. Coba lagi dalam 10 menit.'
                ]);
            }

            $kode = generateRandomString();

            $insertData = [
                'id_user' => $get_user['id_user'],
                'email' => $get_user['email'],
                'token' => $kode,
                'time' => date('Y-m-d H:i:s'),
                'is_reset' => 0
            ];

            $query = $this->db->table('tb_user_reset_password')->insert($insertData);

            if ($query) {
                $this->send_email(['email' => $email, 'kode' => $kode]);
                $this->mainModel->record_reset_password_attempt($ip, $email, 'submit_form');

                return json_response([
                    'status' => 1,
                    'message' => 'Berhasil mengirim email, silahkan cek email Anda.'
                ]);
            }
        }

        return json_response([
            'status' => 0,
            'message' => 'Gagal, Email pengguna tidak terdaftar.'
        ]);
    }

    function send_email($data)
    {
        $data_email['email'] = $data['email'];
        $data_email['kode_otp'] = $data['kode'];

        $message = view('email/lupa_password_email', $data_email);
        $get_konf = $this->db->table('tb_admin_konf_email')->getWhere(['id' => 1])->getRowArray();

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $get_konf['host'];
            $mail->SMTPAuth = $get_konf['smtpauth'];
            $mail->Username = $get_konf['email'];
            $mail->Password = $get_konf['password'];
            $mail->SMTPSecure = $get_konf['smtpsecure'];
            $mail->Port = $get_konf['port'];
            $mail->Subject = $data_email['kode_otp'] . ' - Reset Password akun SheHacks';
            $mail->setFrom($get_konf['email'], $get_konf['setfrom']);
            $mail->addAddress($data_email['email']);
            $mail->isHTML(true);

            $mail->AddEmbeddedImage(FCPATH . 'assets/front/img/logo-shehacks.png', 'logo_email');

            $mail->MsgHTML(stripslashes($message));

            $mail->send();

        } catch (Exception $e) {
            log_message('error', 'Email failed: ' . $mail->ErrorInfo);
        }
    }

    function send_email_text($data)
    {

        $data_email['email'] = $data['email'];
        $data_email['kode_otp'] = $data['kode'];

        $get_konf = $this->db->table('tb_admin_konf_email')->getWhere(['id' => 1])->getRowArray();


        $from = $get_konf['email'];
        $to = $get_user['email'];

        $subject = $data_email['kode_otp'] . " - Reset password akun SheHacks";

        $message = "

        Halo,

        Reset password akun SheHacks.

        Berikut kode verifikasi akun Anda, silahkan gunakan kode dibawah untuk reset password pada akun Anda

        Kode Verifikasi: " . $data_email['kode_otp'] . "

        Terima Kasih,

        Tim SheHacks Anda";

        $headers = "From:" . $from;

        mail($to, $subject, $message, $headers);

    }
    // --------------------------- reset password ---------------------------


    // --------------------------- verifikasi ---------------------------
    function verifikasi()
    {
        if (!$this->session->get('logged_in_front')) {
            $data = [
                'title' => 'Verifikasi Email',
                'page' => 'auth/lupa_password_verifikasi'
            ];

            $this->data = array_merge($this->data, $data);
            return view('index', $this->data);
        }
        return redirect()->to('/');
    }

    function post_kode_verifikasi()
    {

        $email = $this->request->getPost('email');
        $kode = $this->request->getPost('kode');
        $ip = $this->request->getIPAddress();

        $attempts = $this->mainModel->get_reset_password_attempts($ip, $email, 'submit_kode');

        if ($attempts >= 5) {
            return json_response(['status' => 0, 'message' => 'Terlalu banyak permintaan. Coba lagi dalam 10 menit.']);
        }

        $get_user = $this->db->table('tb_user')->getWhere(['email' => $email])->getRowArray();

        if (!$get_user) {
            $this->mainModel->record_reset_password_attempt($ip, $email, 'submit_kode');
            return json_response(['status' => 0, 'message' => 'Gagal, Email pengguna tidak terdaftar.']);
        }

        $cek_otp = $this->db->table('tb_user_reset_password')
            ->where('id_user', $get_user['id_user'])
            ->orderBy('id', 'DESC')
            ->get()->getRowArray();

        if ($cek_otp && $cek_otp['token'] == $kode) {
            if ($cek_otp['is_reset'] == 0) {
                $update['is_reset'] = 1;
                $this->mainModel->update_data('tb_user_reset_password', $update, 'id', $cek_otp['id']);

                $this->session->set([
                    'sess_email_reset' => $get_user['email'],
                    'sess_token_reset' => $get_user['token']
                ]);

                $this->mainModel->clear_reset_password_attempts($ip, $email);
                return json_response(['status' => 1, 'message' => 'Success']);
            }
            return json_response(['status' => 0, 'message' => 'Gagal, kode verifikasi telah kadaluarsa.']);
        }


        $this->mainModel->record_reset_password_attempt($ip, $email, 'submit_kode');
        return json_response(['status' => 0, 'message' => 'Gagal, kode verikasi tidak sesuai.']);
    }
    // --------------------------- end verifikasi ---------------------------


    // --------------------------- form password ---------------------------
    function form_lupa_password()
    {
        if (!$this->session->get('logged_in_front') && $this->session->get('sess_token_reset')) {
            $data = [
                'title' => 'Form Reset Password',
                'page' => 'auth/lupa_password_form'
            ];

            $this->data = array_merge($this->data, $data);
            return view('index', $this->data);
        }

        return redirect()->to('/');
    }

    // TODO: Check this!
    function post_ganti_password()
    {
        $email = $this->session->userdata('sess_email_reset', TRUE);
        $token = $this->session->userdata('sess_token_reset', TRUE);
        $ip = $this->input->ip_address();

        $attempts = $this->main_model->get_reset_password_attempts($ip, $email, 'submit_password');
        if ($attempts >= 5) {
            $response['status'] = 0;
            $response['message'] = 'Terlalu banyak permintaan. Coba lagi dalam 10 menit.';
        } else {

            if ($token) {
                $password = $this->input->post('password', TRUE);

                $get_user = $this->db->select('id_user, token')
                    ->from('tb_user')
                    ->where('email', $email)
                    ->get()
                    ->row_array();

                if ($get_user) {

                    if ($get_user['token'] == $token) {

                        $update['password'] = md5($password);
                        $update['password_text'] = $password;

                        $query = $this->main_model->update_data('tb_user', $update, 'id_user', $get_user['id_user']);

                        if ($query) {

                            $this->session->unset_userdata('sess_email_reset');
                            $this->session->unset_userdata('sess_token_reset');

                            $this->main_model->clear_reset_password_attempts($ip, $email);

                            $response['status'] = 1;
                            $response['message'] = 'Sukses, reset password';
                        } else {

                            $this->main_model->record_reset_password_attempt($ip, $email, 'submit_password');

                            $response['status'] = 0;
                            $response['message'] = 'Gagal, silahkan coba lagi';
                        }
                    } else {

                        $this->main_model->record_reset_password_attempt($ip, $email, 'submit_password');

                        $response['status'] = 0;
                        $response['message'] = 'Gagal, akses di block';
                    }
                } else {

                    $this->main_model->record_reset_password_attempt($ip, $email, 'submit_password');

                    $response['status'] = 0;
                    $response['message'] = 'Gagal, email tidak terdaftar';
                }
            } else {

                $response['status'] = 2;
                $response['message'] = 'Gagal, sesi telah kadaluarsa';

                $this->main_model->record_reset_password_attempt($ip, $email, 'submit_password');
            }
        }

        json_response($response);
    }
    // --------------------------- end form password ---------------------------

}