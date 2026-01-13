<?php

namespace App\Controllers\Admin\Dashboard;

use App\Controllers\AdminController;
use Config\Services;

class Dashboard extends AdminController
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

        $this->requireAccess('dashboard');

        $data = [
            'title' => 'Welcome to Dashboard',
            'page' => 'admin/dashboard/dashboard',
        ];

        $this->data = array_merge($this->data, $data);

        return view('admin/index', $this->data);
    }

    function data_info()
    {
        $this->requireAccess('dashboard');

        $cact = $this->mainModel->check_access_action('dashboard');

        if ($cact['access_view'] === 'd-none') {
            return json_response([
                'status'  => 0,
                'message' => 'Gagal, tidak memiliki akses.'
            ]);
        }

        $value = $this->request->getGet('value');

        $this->validation->setRules([
            'value' => 'permit_empty|alpha_numeric'
        ]);

        if (!$this->validation->run(['value' => $value])) {
            return json_response([
                'status'  => 0,
                'message' => $this->validation->listErrors()
            ]);
        }

        $days  = date('d');
        $month = date('m');
        $year  = date('Y');

        $today = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));
        $last7 = clone $today;
        $last7->modify('-7 days');

        $label = '';

        $baseBuilder = function () use ($value, $today, $last7, $month, $year, &$label, $days) {
            $builder = $this->db->table('tb_user');

            if ($value === 'today') {
                $label = $days . ' ' . conv_month($month) . ' ' . $year;
                $builder->where('DATE(date_create)', date('Y-m-d'));
            } elseif ($value === 'last7') {
                $label = '7 Hari Terakhir';
                $builder->where('DATE(date_create) >=', $last7->format('Y-m-d'))
                    ->where('DATE(date_create) <=', $today->format('Y-m-d'));
            } elseif ($value === 'month') {
                $label = conv_month($month) . ' ' . $year;
                $builder->where('MONTH(date_create)', $month)
                    ->where('YEAR(date_create)', $year);
            } elseif ($value === 'year') {
                $label = $year;
                $builder->where('YEAR(date_create)', $year);
            }

            return $builder;
        };

        // total user
        $get_user = $baseBuilder()
            ->where('id_user !=', 0)
            ->selectCount('id_user', 'total')
            ->get()
            ->getRowArray();

        // total ideasi
        $get_ideasi = $baseBuilder()
            ->where('kategori_user', 'Ideasi')
            ->selectCount('id_user', 'total')
            ->get()
            ->getRowArray();

        // total mvp
        $get_mvp = $baseBuilder()
            ->where('kategori_user', 'MVP')
            ->selectCount('id_user', 'total')
            ->get()
            ->getRowArray();

        return json_response([
            'total_user'        => number_format($get_user['total'] ?? 0, 0, ',', '.'),
            'total_user_label'  => $label,
            'total_user_ideasi' => number_format($get_ideasi['total'] ?? 0, 0, ',', '.'),
            'total_user_mvp'    => number_format($get_mvp['total'] ?? 0, 0, ',', '.')
        ]);
    }

    function load_data_kategori()
    {
        $this->requireAccess('dashboard');
        $cact = $this->mainModel->check_access_action('dashboard');

        if ($cact['access_view'] === 'd-none') {
            return json_response([
                'status'  => 0,
                'message' => 'Gagal, tidak memiliki akses.'
            ]);
        }

        $labels   = [];
        $datasets = [];

        $countData = $this->db->table('tb_user')
            ->selectCount('id_user', 'total')
            ->get()
            ->getRowArray();

        $getData = $this->db->table('tb_user')
            ->select("IFNULL(kategori_user, '-') AS nama, COUNT(id_user) AS total", false)
            ->groupBy('kategori_user')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($getData as $row) {
            $labels[]   = $row['nama'];
            $datasets[] = $row['total'];
        }

        return json_response([
            'labels'       => $labels,
            'datasets'     => $datasets,
            'legend_title' => 'Total',
            'legend_value' => $countData['total'] ?? 0,
            'status'       => 1
        ]);
    }

    function load_data_channel()
    {
        $this->requireAccess('dashboard');
        $cact = $this->mainModel->check_access_action('dashboard');

        if ($cact['access_view'] === 'd-none') {
            return json_response([
                'status'  => 0,
                'message' => 'Gagal, tidak memiliki akses.'
            ]);
        }

        $labels   = [];
        $datasets = [];

        $countData = $this->db->table('tb_user')
            ->selectCount('id_user', 'total')
            ->get()
            ->getRowArray();

        $channels = [
            'Alumni 2023'       => '%2023%',
            'Alumni 2024'       => '%2024%',
            'Alumni 2023, 2024' => '%2023, 2024%'
        ];

        foreach ($channels as $label => $like) {
            $total = $this->db->table('tb_user')
                ->like('channel', $like)
                ->countAllResults();

            $labels[]   = $label;
            $datasets[] = $total;
        }

        return json_response([
            'labels'       => $labels,
            'datasets'     => $datasets,
            'legend_title' => 'Total',
            'legend_value' => $countData['total'] ?? 0,
            'status'       => 1
        ]);
    }

    function load_data_tingkat_pendidikan()
    {
        $this->requireAccess('dashboard');
        $cact = $this->mainModel->check_access_action('dashboard');

        if ($cact['access_view'] === 'd-none') {
            return json_response([
                'status'  => 0,
                'message' => 'Gagal, tidak memiliki akses.'
            ]);
        }

        $labels   = [];
        $datasets = [];
        $list     = [];

        $getData = $this->db->table('tb_user u')
            ->select("IFNULL(p.nama, '-') AS nama, COUNT(u.pendidikan) AS total", false)
            ->join('tb_master_pendidikan p', 'u.pendidikan = p.id_pendidikan', 'left')
            ->groupBy('p.nama')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($getData as $row) {
            $labels[]   = $row['nama'];
            $datasets[] = $row['total'];
            $list[]     = [
                'label' => $row['nama'],
                'value' => $row['total']
            ];
        }

        return json_response([
            'labels'    => $labels,
            'datasets'  => $datasets,
            'data_list' => $list,
            'status'    => 1
        ]);
    }


    function load_data_provinsi()
    {
        $this->requireAccess('dashboard');
        $cact = $this->mainModel->check_access_action('dashboard');

        if ($cact['access_view'] === 'd-none') {
            return json_response([
                'status'  => 0,
                'message' => 'Gagal, tidak memiliki akses.'
            ]);
        }

        $labels   = [];
        $datasets = [];
        $list     = [];

        $countData = $this->db->table('tb_user u')
            ->join('tb_master_province j', 'u.provinsi = j.id', 'left')
            ->selectCount('u.id_user', 'total')
            ->get()
            ->getRowArray();

        $getData = $this->db->table('tb_user u')
            ->select("IFNULL(j.name, '-') AS nama, COUNT(u.id_user) AS total", false)
            ->join('tb_master_province j', 'u.provinsi = j.id', 'left')
            ->groupBy('j.name')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($getData as $row) {
            $labels[]   = $row['nama'];
            $datasets[] = $row['total'];
            $list[]     = [
                'label' => $row['nama'],
                'value' => $row['total']
            ];
        }

        return json_response([
            'labels'       => $labels,
            'datasets'     => $datasets,
            'legend_title' => 'Total',
            'legend_value' => $countData['total'] ?? 0,
            'data_list'    => $list,
            'status'       => 1
        ]);
    }

    function load_data_dapat_informasi()
    {
        $this->requireAccess('dashboard');
        $cact = $this->mainModel->check_access_action('dashboard');

        if ($cact['access_view'] === 'd-none') {
            return json_response([
                'status'  => 0,
                'message' => 'Gagal, tidak memiliki akses.'
            ]);
        }

        $labels   = [];
        $datasets = [];
        $list     = [];

        $getData = $this->db->table('tb_user u')
            ->select("IFNULL(j.nama, '-') AS nama, COUNT(u.id_user) AS total", false)
            ->join('tb_master_dapat_informasi j', 'u.dapat_informasi = j.id_informasi', 'left')
            ->groupBy('j.nama')
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($getData as $row) {
            $labels[]   = $row['nama'];
            $datasets[] = $row['total'];
            $list[]     = [
                'label' => $row['nama'],
                'value' => $row['total']
            ];
        }

        return json_response([
            'labels'    => $labels,
            'datasets'  => $datasets,
            'data_list' => $list,
            'status'    => 1
        ]);
    }


    function get_address()
    {
        $id    = $this->request->getPost('id');
        $param = $this->request->getPost('param');

        $data = '';

        if ($param === 'provinsi') {
            $rows = $this->db->table('tb_master_regencies')
                ->where('province_id', $id)
                ->orderBy('name', 'ASC')
                ->get()
                ->getResultArray();
        } elseif ($param === 'kabupaten') {
            $rows = $this->db->table('tb_master_district')
                ->where('regency_id', $id)
                ->orderBy('name', 'ASC')
                ->get()
                ->getResultArray();
        } else {
            $rows = [];
        }

        foreach ($rows as $row) {
            $data .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
        }

        return json_response([
            'data'  => $data,
            'param' => $param
        ]);
    }
}
