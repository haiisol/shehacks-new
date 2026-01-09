<?php

namespace App\Controllers\Auth;

use App\Controllers\FrontController;

class LupaPassword extends FrontController
{

    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = db_connect();
        $this->session = session();
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

                $dataEmail = [
                    'kode_otp' => $kode,
                    'email' => $email
                ];

                $subject = "${kode} - Reset Password akun SheHacks";
                $message = view('email/lupa_password_email', $dataEmail);

                $emailData = [
                    'subject' => $subject,
                    'message' => $message,
                    'email' => $email,
                    'image' => FCPATH . 'assets/front/img/logo-shehacks.png',
                    'cid' => 'logo_email'
                ];

                send_email($emailData);

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

    public function post_ganti_password()
    {
        $email = $this->session->get('sess_email_reset');
        $token = $this->session->get('sess_token_reset');
        $ip = $this->request->getIPAddress();

        $attempts = $this->mainModel->get_reset_password_attempts($ip, $email, 'submit_password');

        if ($attempts >= 5) {
            return json_response([
                'status' => 0,
                'message' => 'Terlalu banyak permintaan. Coba lagi dalam 10 menit.',
            ]);
        }

        if (!$token) {
            $this->mainModel->record_reset_password_attempt($ip, $email, 'submit_password');

            return json_response([
                'status' => 2,
                'message' => 'Gagal, sesi telah kadaluarsa',
            ]);
        }

        $password = $this->request->getPost('password');

        $get_user = $this->db->table('tb_user')
            ->select('id_user, token')
            ->where('email', $email)
            ->get()
            ->getRowArray();

        if (!$get_user) {
            $this->mainModel->record_reset_password_attempt($ip, $email, 'submit_password');

            return json_response([
                'status' => 0,
                'message' => 'Gagal, email tidak terdaftar',
            ]);
        }

        if ($get_user['token'] !== $token) {
            $this->mainModel->record_reset_password_attempt($ip, $email, 'submit_password');

            return json_response([
                'status' => 0,
                'message' => 'Gagal, akses di block',
            ]);
        }

        $update = [
            'password' => md5($password),
            'password_text' => $password,
        ];

        $query = $this->mainModel->update_data('tb_user', $update, 'id_user', $get_user['id_user']);

        if (!$query) {
            $this->mainModel->record_reset_password_attempt($ip, $email, 'submit_password');

            return json_response([
                'status' => 0,
                'message' => 'Gagal, silahkan coba lagi',
            ]);
        }

        // sukses
        $this->session->remove(['sess_email_reset', 'sess_token_reset']);
        $this->mainModel->clear_reset_password_attempts($ip, $email);

        return json_response([
            'status' => 1,
            'message' => 'Sukses, reset password',
        ]);
    }
    // --------------------------- end form password ---------------------------

}