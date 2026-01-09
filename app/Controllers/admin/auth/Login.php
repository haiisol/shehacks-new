<?php

namespace App\Controllers\Admin\Auth;

use App\Controllers\AdminController;
use Config\Services;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Login extends AdminController
{
    protected $db;
    protected $validation;

    public function __construct()
    {
        $this->db         = db_connect();
        $this->validation = Services::validation();
    }

    public function index()
    {
        if (session()->get('logged_in_admin') === true) {
            return redirect()->to(base_url('admin/dashboard/dashboard'));
        }

        $data = [
            'title' => 'Login',
            'page' => 'admin/auth/login',
        ];

        $this->data = array_merge($this->data, $data);

        return view('admin/index', $this->data);
    }

    function login_verify()
    {
        if (!session()->get('2fa_id_admin')) {
            return redirect()->to(base_url('panel'));
        }

        $data = [
            'title' => '2FA Verify',
            'page' => 'admin/auth/login_verify',
        ];

        $this->data = array_merge($this->data, $data);

        return view('admin/index', $this->data);
    }

    function post_login()
    {
        $data = [
            'email_admin'    => $this->request->getPost('email_admin'),
            'password_admin' => $this->request->getPost('password_admin'),
        ];

        $this->validation->setRules([
            'email_admin'    => 'required|valid_email',
            'password_admin' => 'required'
        ]);

        if (!$this->validation->run($data)) {
            return json_response([
                'status'  => 0,
                'message' => $this->validation->listErrors()
            ]);
        }

        $email    = $data['email_admin'];
        $password = md5($data['password_admin']);

        $user = $this->db->table('tb_admin_user')
            ->where('email_admin', $email)
            ->get()
            ->getRowArray();

        if (!$user || $password !== $user['password_admin']) {
            return json_response([
                'status'  => 0,
                'message' => 'Email Pengguna atau Kata Sandi salah.'
            ]);
        }

        $this->fa_handle($user);

        return json_response([
            'status'   => 1,
            'message'  => 'Success, Login Akun',
            'redirect' => base_url('panel/auth-verify')
        ]);
    }

    function fa_handle($data)
    {
        $generate_code = rand(100000, 999999);

        $data2fa = [
            'id_user' => $data['id_admin'],
            'code' => $generate_code,
            'access_policy' => 'CMS',
            'code_encrypt' => encrypt_url($generate_code),
            'date_create' => date('Y-m-d H:i:s'),
            'date_expired' => date('Y-m-d H:i:s', strtotime('+10 minutes'))
        ];

        $builder = $this->db->table('tb_user_2fa');

        if (!$builder->insert($data2fa)) {
            return false;
        }

        $dataEmail = [
            'code' => $generate_code,
            'email' => $data['email_admin'],
            'param' => 'Login',
        ];

        $subject = $generate_code . " - Kode akses login akun SheHacks";
        $message = view('email/2fa_email_login', $dataEmail);

        $emailData = [
            'subject' => $subject,
            'message' => $message,
            'email' => $data['email_admin'],
        ];

        $this->send_email($emailData);

        session()->set('2fa_id_admin', encrypt_url($data['id_admin']));
    }

    function post_login_verify()
    {
        $this->validation->setRules([
            'kode' => 'required|numeric'
        ]);

        if (!$this->validation->run($this->request->getPost())) {
            return json_response([
                'status'  => 0,
                'message' => $this->validation->listErrors()
            ]);
        }

        $kode   = $this->request->getPost('kode');
        $userId = decrypt_url(session()->get('2fa_id_admin'));

        $now    = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));

        if (!$userId) {
            return json_response([
                'status'  => 0,
                'message' => 'Failed, session not found.'
            ]);
        }

        $row = $this->db->table('tb_user_2fa')
            ->where([
                'id_user'       => $userId,
                'code'          => $kode,
                'access_policy' => 'CMS'
            ])
            ->get()
            ->getRowArray();

        if (!$row) {
            return json_response([
                'status'  => 0,
                'message' => 'Failed, code not match.'
            ]);
        }

        if (strtotime($row['date_expired']) < $now->getTimestamp()) {
            return json_response([
                'status'  => 0,
                'message' => 'Failed, session is expired.'
            ]);
        }

        session()->set([
            'key_auth_admin'  => encrypt_url($row['id_user']),
            'key_token_admin' => $row['code_encrypt'],
            'logged_in_admin' => true
        ]);

        $this->db->table('tb_admin_user')
            ->where('id_admin', $row['id_user'])
            ->update([
                'terakhir_login_admin' => $now->format('Y-m-d H:i:s')
            ]);

        $this->db->table('tb_user_2fa')
            ->where('id', $row['id'])
            ->update([
                'date_verification' => $now->format('Y-m-d H:i:s')
            ]);

        session()->remove('2fa_id_admin');

        return json_response([
            'status'   => 1,
            'message'  => 'Success',
            'redirect' => base_url('panel-dashboard')
        ]);
    }

    function send_email($data)
    {
        $getKonf = $this->db->table('tb_admin_konf_email')
            ->where('id', 1)
            ->get()
            ->getRowArray();

        $email = $data['email'];
        $subject = $data['subject'];
        $message = $data['message'];

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
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->MsgHTML(stripslashes($message));
            $mail->send();
        } catch (Exception $e) {
            log_message('error', 'Email failed: ' . $mail->ErrorInfo);
        }
    }

    function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('panel'));
    }
}
