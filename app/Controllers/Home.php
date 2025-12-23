<?php

namespace App\Controllers;

use Config\Database;
use Config\Services;
use App\Models\MainModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Home extends FrontController
{
    protected $db;
    protected $mainModel;
    protected $userModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        $this->db = Database::connect();

        $this->mainModel = new MainModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (!$this->data['under_construction']) {
            if ($this->data['event_running']) {
                if ($this->data['web']['coming_soon'] == 1) {
                    $this->data['coming_soon_date'] = $this->data['web']['coming_soon_date'];
                    $this->data['page'] = 'page/coming_soon';
                } else {
                    $this->data['page'] = 'home';
                }
            } else {
                $this->data['page'] = 'home_announcement';
            }
        } else {
            $this->data['page'] = 'page/under_construction';
        }

        return view('index', $this->data);
    }

    function coming_soon()
    {
        $this->data['coming_soon_date'] = $this->data['web']['coming_soon_date'];

        $this->data['page'] = 'page/coming_soon';
        return view('index', $this->data);
    }

    function preview()
    {
        $this->data['title'] = 'Preview';
        $this->data['page'] = 'home';
        return view('index', $this->data);
    }

    function preview_voting()
    {
        $this->data['title'] = 'Preview Voting';
        $this->data['page'] = 'home_voting';
        return view('index', $this->data);
    }

    public function valid_alfabet($str)
    {
        $validation = service('validation');
        
        if (preg_match('/^[a-zA-Z]+$/', $str)) {
            return TRUE;
        } else {
            $validation->setError('valid_alfabet', 'Kolom {field} hanya boleh berisi huruf alfabet.');
            return FALSE;
        }
    }

    function get_address()
    {
        $validation = service('validation');
        $id = $this->request->getGet('id', TRUE);
        $param = $this->request->getGet('param', TRUE);

        $rules = [
            'id' => 'trim|required|numeric',
            'param' => [
                'rules' => 'trim|required|callback_valid_alfabet',
                'errors' => []
            ]
        ];

        if (!$validation->setRules($rules)->run(['id' => $id, 'param' => $param]) || !$this->valid_alfabet($param)) {
            return json_response([
                'status' => 0,
                'message' => $validation->listErrors()
            ]);
        }

        $data = '';

        if ($param == 'provinsi') {

            $kab = $this->db->table('tb_master_regencies')
                ->where('province_id', $id)
                ->orderBy('name', 'ASC')
                ->get()
                ->getResultArray();

            foreach ($kab as $row) {
                $data .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
            }
        } else if ($param == 'kabupaten') {
            $kec = $this->db->table('tb_master_district')
                ->where('regency_id', $id)
                ->orderBy('name', 'ASC')
                ->get()
                ->getResultArray();

            foreach ($kec as $row) {
                $data .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
            }
        }

        return json_response([
            'data' => $data,
            'param' => $param
        ]);
    }

    function post_contact()
    {
        $validation = service('validation');
        
        $secret = env('recaptcha.secret');
        $captchaToken = $this->request->getPost('g-recaptcha-response');

        $verify = file_get_contents(
            "https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$captchaToken}"
        );

        $res = json_decode($verify);

        if (!$res->success) {
            return json_response([
                'status' => 2,
                'message' => 'Silahkan verifikasi captcha.'
            ]);
        }

        $row = [
            'phone' => $this->request->getPost('phone'),
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'subject' => $this->request->getPost('subject'),
            'message' => $this->request->getPost('message'),
        ];

        $rules = [
            'phone' => 'required|numeric',
            'name' => 'required',
            'email' => 'required|valid_email',
            'subject' => 'required',
            'message' => 'required',
        ];

        if (!$validation->setRules($rules)->run($row)) {
            return json_response([
                'status' => 0,
                'message' => $validation->listErrors()
            ]);
        }

        $data = [
            'name' => $this->sanitize_input($row['name']),
            'email' => $row['email'],
            'phone' => $row['phone'],
            'subject' => $this->sanitize_input($row['subject']),
            'message' => $this->sanitize_input($row['message']),
            'date' => date('Y-m-d H:i:s'),
            'status' => 0
        ];

        $insert = $this->db->table('tb_message')->insert($data);

        if ($insert) {
            return json_response([
                'status' => 1,
                'message' => 'Pesan berhasil terkirim.'
            ]);
        }

        return json_response([
            'status' => 0,
            'message' => 'Gagal mengirim pesan.'
        ], 400);
    }

    public function sanitize_input($str)
    {
        $str = strip_tags($str); // Menghapus tag HTML/JS
        $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8'); // Konversi simbol HTML agar tidak dieksekusi
        return $str;
    }

    function reload_countdown()
    {
        $data['coming_soon'] = 0;
        $data['coming_soon_date'] = '0000-00-00 00:00:00';

        $this->mainModel->update_data('tb_admin_web', $data, 'id', 1);

        return json_response(1);
    }


    // function generate_channel()
    // {
    //     $limit      = $this->input->get('limit');
    //     $offset     = $this->input->get('offset');

    //     $get_user = $this->db->query("SELECT id_user FROM tb_user LIMIT ".$limit." OFFSET ".$offset." ")->result_array();

    //     foreach ($get_user as $key) {
    //         $cek = $this->db->query("SELECT id_channel FROM tb_user_channel WHERE id_user = ".$key['id_user']." ")->result_array();

    //         if (empty($cek)) {
    //             $data['id_user']     = $key['id_user'];
    //             $data['channel']     = '2023';
    //             $data['date_create'] = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');
    //             $this->db->insert('tb_user_channel', $data);
    //         }
    //     }

    //     echo '----------- Selesai -----------';
    // }

}
