<?php

namespace App\Controllers\Admin\Setting;

use App\Controllers\AdminController;
use PHPMailer\PHPMailer\PHPMailer;
use Config\Services;

class EmailAkun extends AdminController
{
    protected $db;
    protected $validation;

    function __construct()
    {
        $this->db = db_connect();
        $this->validation = Services::validation();
    }

    public function index()
    {
        $this->requireAccess('akun_email');
        $cact = $this->mainModel->check_access_action('akun_email');

        $data = [
            'title' => 'Setting Email Akun',
            'page' => 'admin/setting/email_akun',
            'access_edit' => $cact['access_edit'],
        ];

        $this->data = array_merge($this->data, $data);
        return view('admin/index', $this->data);
    }

    function get_data()
    {
        $this->requireAccess('akun_email');
        $query = $this->db->table('tb_admin_konf_email')
            ->where('id', 1)
            ->get()
            ->getRowArray();

        return json_response($query);
    }

    function edit_data()
    {
         $this->requireAccess('akun_email');
        $cact = $this->mainModel->check_access_action('akun_email');

        if ($cact['access_edit'] === 'd-none') {
            return json_response([
                'status' => 0,
                'message' => 'Gagal, tidak memiliki akses edit.'
            ]);
        }

        $id = $this->request->getPost('id');

        $this->validation->setRules([
            'id' => 'required|numeric'
        ]);

        if (!$this->validation->run(['id' => $id])) {
            return json_response([
                'status'  => 0,
                'message' => $this->validation->getErrors()
            ]);
        }

        $data = [
            'host'          => $this->request->getPost('host'),
            'smtpauth'      => $this->request->getPost('smtpauth'),
            'email'         => $this->request->getPost('email'),
            'password'      => $this->request->getPost('password'),
            'smtpsecure'    => $this->request->getPost('smtpsecure'),
            'port'          => $this->request->getPost('port'),
            'setfrom'       => $this->request->getPost('setfrom'),
            'email_subject' => $this->request->getPost('email_subject'),
        ];

        $update = $this->db->table('tb_admin_konf_email')
            ->where('id', $id)
            ->update($data);

        if ($update) {
            $response = [
                'status'  => 1,
                'message' => 'Data berhasil disimpan.'
            ];
        } else {
            $response = [
                'status'  => 0,
                'message' => 'Gagal menyimpan data.'
            ];
        }

        return json_response($response);
    }

    function testing_kirim()
    {
        $getEmail = $this->db->table('tb_admin_konf_email')
            ->where('id', 1)
            ->get()
            ->getRowArray();

        $emailTest = $this->request->getPost('email_test');
        $message   = '----- Email Testing -----';

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $getEmail['host'];
            $mail->SMTPAuth   = $getEmail['smtpauth'];
            $mail->Username   = $getEmail['email'];
            $mail->Password   = $getEmail['password'];
            $mail->SMTPSecure = $getEmail['smtpsecure'];
            $mail->Port       = $getEmail['port'];

            $mail->setFrom($emailTest, $getEmail['setfrom']);
            $mail->addAddress($emailTest);

            $mail->isHTML(true);
            $mail->Subject = 'Email Account Test - ' . $getEmail['email'];
            $mail->Body    = $message;

            $mail->send();

            $response = [
                'status'  => 1,
                'message' => 'Berhasil mengirim email',
                'info'    => 'Email berhasil terkirim, silahkan cek email'
            ];
        } catch (\Exception $e) {
            $response = [
                'status'  => 0,
                'message' => 'Gagal mengirim email',
                'info'    => $mail->ErrorInfo
            ];
        }

        return json_response($response);
    }
}
