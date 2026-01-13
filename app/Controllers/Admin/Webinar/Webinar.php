<?php

namespace App\Controllers\Admin\Webinar;

use App\Controllers\AdminController;

class Webinar extends AdminController
{
    protected $db;
    function __construct()
    {
        $this->db = db_connect();
    }

    public function index()
    {
        $this->requireAccess('webinar');

        $data = [
            'title' => 'Data Webinar',
            'page' => 'admin/webinar/webinar',
        ];

        $this->data = array_merge($this->data, $data);

        return view('admin/index', $this->data);
    }

    protected function sql()
    {
        return $this->db->table('tb_webinar i')
            ->select('i.id_webinar, i.judul, i.kode_youtube')
            ->where('i.status_delete', 0);
    }

    public function datatables()
    {
        $this->requireAccess('webinar');

        $validColumns = [
            1 => 'i.id_webinar',
            3 => 'i.judul',
        ];

        $params = [
            'start'  => (int) $this->request->getGet('start'),
            'length' => (int) $this->request->getGet('length'),
            'order'  => $this->request->getGet('order'),
            'search' => $this->request->getGet('search')['value'] ?? '',
        ];

        $baseBuilder = $this->sql();

        $dataBuilder = clone $baseBuilder;
        $this->mainModel->datatable($dataBuilder, $validColumns, $params);
        $query = $dataBuilder->get()->getResultArray();

        $filteredBuilder = clone $baseBuilder;
        $this->mainModel->datatable($filteredBuilder, $validColumns, $params);
        $recordsFiltered = $filteredBuilder->countAllResults();

        $recordsTotal = $baseBuilder->countAllResults();

        $no   = (int) $this->request->getGet('start');
        $data = [];

        foreach ($query as $row) {
            $no++;

            $urlImage = 'http://img.youtube.com/vi/' . $row['kode_youtube'] . '/mqdefault.jpg';
            $cact     = $this->mainModel->check_access_action('webinar');

            $data[] = [
                '<label class="checkbox-custome">
                    <input type="checkbox" name="check-record" value="' . $row['id_webinar'] . '" class="check-record">
                 </label>',
                $no,
                '<img src="' . $urlImage . '" class="img-fluid">',
                character_limiter($row['judul'], 70),
                '<div class="dropdown dropdown-action ' . $cact['access_action'] . ' options ms-auto text-center">
                    <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                        <a href="javascript:void(0)" class="dropdown-item edit-data ' . $cact['access_edit'] . '" data="' . $row['id_webinar'] . '">
                            <ion-icon name="pencil-sharp"></ion-icon> Edit
                        </a>
                        <a href="javascript:void(0)" class="dropdown-item ' . $cact['access_delete'] . '" id="delete-data" data="' . $row['id_webinar'] . '">
                            <ion-icon name="trash-sharp"></ion-icon> Delete
                        </a>
                    </div>
                </div>'
            ];
        }

        return json_response([
            'draw'            => (int) $this->request->getGet('draw'),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data
        ]);
    }

    public function cek_value()
    {
        $this->requireAccess('webinar');
        $cact = $this->mainModel->check_access_action('webinar');

        if ($cact['access_view'] === 'd-none') {
            return json_response([
                'status'  => 0,
                'message' => 'Gagal, tidak memiliki akses.'
            ]);
        }

        $judul = $this->request->getPost('value');

        if (!$this->validate([
            'value' => 'required|regex_match[/^[a-zA-Z0-9 ]+$/]'
        ])) {
            return json_response([
                'status'  => 0,
                'message' => $this->validator->getErrors()
            ]);
        }

        $exists = $this->db->table('tb_webinar')
            ->where('judul', $judul)
            ->countAllResults();

        return json_response([
            'status'  => $exists ? 0 : 1,
            'message' => ''
        ]);
    }

    public function add_data()
    {
        $this->requireAccess('webinar');
        $cact = $this->mainModel->check_access_action('webinar');

        if ($cact['access_add'] === 'd-none') {
            return json_response([
                'status'  => 0,
                'message' => 'Gagal, tidak memiliki akses.'
            ]);
        }

        if (!$this->validate([
            'judul'        => 'required|regex_match[/^[a-zA-Z0-9 :-]+$/]',
            'kode_youtube' => 'required|regex_match[/^[a-zA-Z0-9 :-]+$/]'
        ])) {
            return json_response([
                'status'  => 0,
                'message' => $this->validator->getErrors()
            ]);
        }

        $insert = $this->db->table('tb_webinar')->insert([
            'judul'        => $this->request->getPost('judul'),
            'kode_youtube' => $this->request->getPost('kode_youtube')
        ]);

        return json_response([
            'status'  => $insert ? 1 : 2,
            'message' => $insert ? 'Data berhasil ditambahkan.' : 'Gagal menambah data.'
        ]);
    }

    public function get_data()
    {
        $this->requireAccess('webinar');

        $cact = $this->mainModel->check_access_action('webinar');

        if ($cact['access_view'] === 'd-none') {
            return json_response([
                'status'  => 0,
                'message' => 'Gagal, tidak memiliki akses.'
            ]);
        }

        if (!$this->validate(['id' => 'required|numeric'])) {
            return json_response([
                'status'  => 0,
                'message' => $this->validator->getErrors()
            ]);
        }

        $data = $this->db->table('tb_webinar')
            ->select('id_webinar, judul, kode_youtube')
            ->where('id_webinar', $this->request->getPost('id'))
            ->get()
            ->getRowArray();

        return json_response($data);
    }

    public function edit_data()
    {
        $this->requireAccess('webinar');
        $cact = $this->mainModel->check_access_action('webinar');

        if ($cact['access_edit'] === 'd-none') {
            return json_response([
                'status'  => 0,
                'message' => 'Gagal, tidak memiliki akses.'
            ]);
        }

        if (!$this->validate([
            'id'           => 'required|numeric',
            'judul'        => 'required|regex_match[/^[a-zA-Z0-9 :-]+$/]',
            'kode_youtube' => 'required|regex_match[/^[a-zA-Z0-9 :-]+$/]'
        ])) {
            return json_response([
                'status'  => 0,
                'message' => $this->validator->getErrors()
            ]);
        }

        $update = $this->db->table('tb_webinar')
            ->where('id_webinar', $this->request->getPost('id'))
            ->update([
                'judul'        => $this->request->getPost('judul'),
                'kode_youtube' => $this->request->getPost('kode_youtube')
            ]);

        return json_response([
            'status'  => $update ? 3 : 4,
            'message' => $update ? 'Data berhasil Disimpan.' : 'Gagal menyimpan data.'
        ]);
    }

    public function detail_data()
    {
        $id = $this->request->getPost('id');

        if (!$this->validate([
            'id'           => 'required|numeric',
        ])) {
            return json_response([
                'status'  => 0,
                'message' => $this->validator->getErrors()
            ]);
        }

        $query = $this->db->table('tb_webinar')
            ->select('id_webinar, judul, url_youtube')
            ->where('id_webinar', $id)
            ->get()
            ->getRowArray();

        if (!$query) {
            return json_response([
                'status'  => 0,
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        return json_response([
            'status'      => 1,
            'id_webinar'  => $query['id_webinar'],
            'judul'       => $query['judul'],
            'url_youtube' => $query['url_youtube'],
        ]);
    }

    public function delete_data()
    {
        $this->requireAccess('webinar');
        $cact = $this->mainModel->check_access_action('webinar');

        if ($cact['access_delete'] === 'd-none') {
            return json_response([
                'status'  => 0,
                'message' => 'Gagal, tidak memiliki akses.'
            ]);
        }

        $method = $this->request->getPost('method');
        $idPost = $this->request->getPost('id');

        if (!$this->validate([
            'method' => 'required|alpha_numeric',
            'id'     => 'required'
        ])) {
            return json_response([
                'status'  => 0,
                'message' => $this->validator->getErrors()
            ]);
        }

        $builder = $this->db->table('tb_webinar');

        if ($method === 'single') {
            $success = $builder
                ->where('id_webinar', $idPost)
                ->update(['status_delete' => 1]);

            return json_response([
                'status'  => $success ? 1 : 0,
                'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data.'
            ]);
        }

        $ids = json_decode($idPost, true);

        if (!is_array($ids) || count($ids) === 0) {
            return json_response([
                'status'  => 0,
                'message' => 'ID tidak valid.'
            ]);
        }

        $success = $builder
            ->whereIn('id_webinar', $ids)
            ->update(['status_delete' => 1]);

        return json_response([
            'status'  => $success ? 2 : 0,
            'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data.'
        ]);
    }
}
