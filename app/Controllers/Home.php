<?php

use App\Controllers\BaseController;
use Config\Database;
use Config\Services;
use App\Models\MainModel;

class Home extends BaseController
{
    protected $db;
    protected $validation;
    protected $mainModel;

    public function __construct()
    {
        
        helper(['url', 'form']);
        $this->db = Database::connect();
        $this->validation = Services::validation();

        $this->mainModel = new MainModel();
    }

    public function index()
    {
        $web = $this->mainModel->get_admin_web();

        if ($web['under_construction'] == 'false') {
            if ($web['event_running'] == 'true') {
                if ($web['coming_soon'] == 1) {
                    $data['coming_soon_date'] = $web['coming_soon_date'];
                    $data['page'] = 'page/coming_soon';
                } else {
                    $data['page'] = 'home';
                }
            } else {
                $data['page'] = 'home_announcement';
            }
        } else {
            $data['page'] = 'page/under_construction';
        }

        $data['title'] = '';
        $data['description'] = '';
        $data['keywords'] = '';
        
        return view('index', $data);
    }

    function coming_soon()
    {
        $web = $this->mainModel->get_admin_web();

        $data['coming_soon_date'] = $web['coming_soon_date'];

        $data['title'] = '';
        $data['description'] = '';
        $data['keywords'] = '';
        $data['page'] = 'page/coming_soon';
        return view('index', $data);
    }

    function preview()
    {
        $data['title'] = 'Preview';
        $data['description'] = '';
        $data['keywords'] = '';
        $data['page'] = 'home';
        return view('index', $data);
    }

    function preview_voting()
    {
        $data['title'] = 'Preview Voting';
        $data['description'] = '';
        $data['keywords'] = '';
        $data['page'] = 'home_voting';
        return view('index', $data);
    }

    public function valid_alfabet($str)
    {
        if (preg_match('/^[a-zA-Z]+$/', $str)) {
            return TRUE;
        } else {
            $this->validation->setError('valid_alfabet', 'Kolom {field} hanya boleh berisi huruf alfabet.');
            return FALSE;
        }
    }

    function get_address()
    {
        $id = $this->request->getGet('id', TRUE);
        $param = $this->request->getGet('param', TRUE);

        $rules = [
            'id' => 'trim|required|numeric',
            'param' => [
                'rules' => 'trim|required|callback_valid_alfabet',
                'errors' => []
            ]
        ];

        if (!$this->validation->setRules($rules)->run(['id' => $id, 'param' => $param]) || !$this->valid_alfabet($param)) {
            return $this->response->setJSON([
                'status' => 0,
                'message' => $this->validation->listErrors()
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

        return $this->response->setJSON([
            'data' => $data,
            'param' => $param
        ]);
    }

    function post_contact()
    {
        $secret = '6LfAu4MlAAAAAIBAk925mhEEj0T7PDyIuuBGjVIX';
        $captchaToken = $this->request->getPost('g-recaptcha-response');

        $verify = file_get_contents(
            "https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$captchaToken}"
        );

        $res = json_decode($verify);

        if (!$res->success) {
            return $this->response->setJSON([
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

        if (!$this->validation->setRules($rules)->run($row)) {
            return $this->response->setJSON([
                'status' => 0,
                'message' => $this->validation->listErrors()
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
            return $this->response->setJSON([
                'status' => 1,
                'message' => 'Pesan berhasil terkirim.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 0,
            'message' => 'Gagal mengirim pesan.'
        ]);
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

        return $this->response->setJSON(1);
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