<?php

namespace App\Controllers\Admin\Voting;

use App\Controllers\AdminController;

class Voting extends AdminController
{
    protected $db;

    function __construct()
    {
        $this->db = db_connect();
    }

    public function index()
    {
        $this->requireAccess('voting');

        $data = [
            'title' => 'Data Voting',
            'page' => 'admin/voting/voting',
        ];

        $this->data = array_merge($this->data, $data);

        return view('admin/index', $this->data);
    }

    protected function sql()
    {
        $fil_kategori = trim($this->request->getGet('fil_kategori') ?? 'all');

        $builder = $this->db->table('tb_voting m')
            ->select('m.id_voting, m.nama_founders, m.nama_usaha, m.bidang_usaha, m.kategori, m.logo, m.video_upload')
            ->where('m.status_delete', 0);

        if ($fil_kategori !== 'all') {
            $builder->where('m.kategori', $fil_kategori);
        }

        return $builder->orderBy('m.id_voting', 'DESC');
    }

    public function datatables()
    {
        $this->requireAccess('voting');

        if (
            !$this->validate([
                'fil_kategori' => 'permit_empty|alpha_numeric'
            ])
        ) {
            return json_response([
                'status' => 0,
                'message' => $this->validator->getErrors(),
                'csrf_name' => csrf_token(),
                'csrf_hash' => csrf_hash(),
            ]);
        }

        $validColumns = [
            1 => 'm.id_voting',
            4 => 'm.nama_founders',
            5 => 'm.nama_usaha',
        ];

        $params = [
            'start' => (int) $this->request->getGet('start'),
            'length' => (int) $this->request->getGet('length'),
            'order' => $this->request->getGet('order'),
            'search' => $this->request->getGet('search')['value'] ?? '',
        ];

        $baseBuilder = $this->sql();

        $dataBuilder = clone $baseBuilder;
        $this->mainModel->datatable($dataBuilder, $validColumns, $params);
        $rows = $dataBuilder->get()->getResultArray();

        $filteredBuilder = clone $baseBuilder;
        $this->mainModel->datatable($filteredBuilder, $validColumns, $params);
        $recordsFiltered = $filteredBuilder->countAllResults();

        $recordsTotal = $baseBuilder->countAllResults();

        $no = $params['start'];
        $data = [];

        foreach ($rows as $row) {
            $no++;
            $cact = $this->mainModel->check_access_action('voting');

            $url_image = url_image($row['logo'], 'image-logo');

            $nama_founders = $row['nama_founders']
                ? character_limiter($row['nama_founders'], 30)
                : '-';

            $nama_usaha = $row['nama_usaha']
                ? character_limiter($row['nama_usaha'], 30)
                : '-';

            $kategori = $row['kategori'] === 'Ideasi'
                ? '<span class="badge bg-danger">' . $row['kategori'] . '</span>'
                : '<span class="badge bg-success">' . $row['kategori'] . '</span>';

            $file = $row['video_upload']
                ? "<a href='https://www.youtube.com/embed/{$row['video_upload']}?autoplay=1' target='_blank'>Lihat Link</a>"
                : '-';

            $data[] = [
                '<label class="checkbox-custome">
                    <input type="checkbox" class="check-record" value="' . $row['id_voting'] . '">
                 </label>',
                $no,
                '<img src="' . $url_image . '" class="img-fluid">',
                $kategori,
                $nama_founders,
                $nama_usaha,
                $file,
                '<div class="dropdown dropdown-action ' . $cact['access_action'] . '">
                    <div class="dropdown-toggle" data-bs-toggle="dropdown">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu">
                        <a class="dropdown-item ' . $cact['access_edit'] . '" data="' . $row['id_voting'] . '" id="edit-data">
                            <ion-icon name="create-sharp"></ion-icon> Edit
                        </a>
                        <a class="dropdown-item ' . $cact['access_delete'] . '" data="' . $row['id_voting'] . '" id="delete-data">
                            <ion-icon name="trash-sharp"></ion-icon> Delete
                        </a>
                    </div>
                </div>'
            ];
        }

        return json_response([
            'draw' => (int) $this->request->getGet('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function add_data()
    {
        $this->requireAccess('voting');
        $cact = $this->mainModel->check_access_action('voting');

        if ($cact['access_add'] === 'd-none') {
            return json_response([
                'status' => 0,
                'message' => 'Gagal, tidak memiliki akses.',
                'csrf_name' => csrf_token(),
                'csrf_hash' => csrf_hash(),
            ]);
        }

        $data = [
            'kategori' => sanitize_input($this->request->getPost('kategori')),
            'nama_founders' => sanitize_input($this->request->getPost('nama_founders')),
            'nama_usaha' => sanitize_input($this->request->getPost('nama_usaha')),
            'bidang_usaha' => sanitize_input($this->request->getPost('bidang_usaha')),
            'description' => sanitize_input_textEditor($this->request->getPost('description')),
            'domisili' => sanitize_input($this->request->getPost('domisili')),
            'video_upload' => sanitize_input($this->request->getPost('video_upload')),
            'date_create' => date('Y-m-d H:i:s'),
        ];

        if ($file = $this->request->getFile('logo')) {
            if ($file->isValid()) {
                $data['logo'] = $this->upload_image();
            }
        }

        $insert = $this->db->table('tb_voting')->insert($data);

        return json_response([
            'status' => $insert ? 1 : 2,
            'message' => $insert ? 'Data berhasil ditambahkan.' : 'Gagal menambah data.',
            'csrf_name' => csrf_token(),
            'csrf_hash' => csrf_hash(),
        ]);
    }

    public function get_data()
    {
        $this->requireAccess('voting');

        $cact = $this->mainModel->check_access_action('voting');

        if ($cact['access_view'] === 'd-none') {
            return json_response([
                'status' => 0,
                'message' => 'Gagal, tidak memiliki akses.',
            ]);
        }

        if (!$this->validate(['id' => 'required|numeric'])) {
            return json_response([
                'status' => 0,
                'message' => $this->validator->getErrors(),
            ]);
        }

        $id = (int) $this->request->getGet('id');

        $row = $this->db->table('tb_voting')
            ->where('id_voting', $id)
            ->get()
            ->getRowArray();

        $row['logo'] = url_image($row['logo'], 'image-logo');

        return json_response($row);
    }

    public function edit_data()
    {
        $this->requireAccess('voting');

        $cact = $this->mainModel->check_access_action('voting');

        if ($cact['access_edit'] === 'd-none') {
            return json_response([
                'status' => 0,
                'message' => 'Gagal, tidak memiliki akses.'
            ]);
        }

        $id = (int) $this->request->getPost('id');

        $rules = [
            'id' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return json_response([
                'status' => 0,
                'message' => $this->validator->listErrors()
            ]);
        }

        $data = [
            'kategori' => sanitize_input($this->request->getPost('kategori')),
            'nama_founders' => sanitize_input($this->request->getPost('nama_founders')),
            'nama_usaha' => sanitize_input($this->request->getPost('nama_usaha')),
            'bidang_usaha' => sanitize_input($this->request->getPost('bidang_usaha')),
            'description' => sanitize_input_textEditor($this->request->getPost('description')),
            'domisili' => sanitize_input($this->request->getPost('domisili')),
            'video_upload' => sanitize_input($this->request->getPost('video_upload')),
        ];

        if ($file = $this->request->getFile('logo')) {
            if ($file->isValid() && !$file->hasMoved()) {
                $data['logo'] = $this->upload_image();
            }
        }

        $updated = $this->db->table('tb_voting')
            ->where('id_voting', $id)
            ->update($data);

        return json_response([
            'status' => $updated ? 3 : 4,
            'message' => $updated
                ? 'Data berhasil Disimpan.'
                : 'Gagal menyimpan data.'
        ]);
    }

    protected function upload_image()
    {
        $file = $this->request->getFile('logo');

        if (!$file || !$file->isValid()) {
            return '';
        }

        $allowed = ['jpg', 'jpeg', 'png'];
        $ext = $file->guessExtension();

        if (!in_array($ext, $allowed, true)) {
            return '';
        }

        $newName = 'logo-voting-' . strtoupper(random_string('alnum', 5)) . '.' . $ext;
        $file->move(FCPATH . 'file_media/image-logo', $newName, true);

        return $newName;
    }

    public function delete_data()
    {
        $this->requireAccess('voting');

        $cact = $this->mainModel->check_access_action('voting');

        if ($cact['access_delete'] === 'd-none') {
            return json_response([
                'status' => 0,
                'message' => 'Gagal, tidak memiliki akses.',
            ]);
        }

        $method = $this->request->getPost('method');
        $id = $this->request->getPost('id');

        if ($method === 'single') {
            $this->delete_file($id);
            $this->db->table('tb_voting')->where('id_voting', $id)->update(['status_delete' => 1]);
            $status = 1;
        } else {
            $ids = json_decode($id, true);
            if ($ids) {
                $this->db->table('tb_voting')->whereIn('id_voting', $ids)->update(['status_delete' => 1]);
                $status = 2;
            }
        }

        return json_response([
            'status' => $status ?? 0,
            'csrf_name' => csrf_token(),
            'csrf_hash' => csrf_hash(),
        ]);
    }

    protected function delete_file($id)
    {
        $row = $this->db->table('tb_voting')->where('id_voting', $id)->get()->getRowArray();
        if (!$row || !$row['logo'])
            return;

        $path = FCPATH . 'file_media/image-logo/' . $row['logo'];
        if (is_file($path))
            unlink($path);
    }

}