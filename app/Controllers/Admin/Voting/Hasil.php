<?php

namespace App\Controllers\Admin\Voting;

use App\Controllers\AdminController;

class Hasil extends AdminController
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
            'title' => 'Data Hasil Voting',
            'page' => 'admin/voting/hasil',
        ];

        $this->data = array_merge($this->data, $data);

        return view('admin/index', $this->data);
    }

    protected function sql()
    {
        $fil_kategori = trim($this->request->getGet('fil_kategori') ?? 'all');

        $builder = $this->db->table('tb_voting m')
            ->select('
                m.id_voting,
                m.nama_founders,
                m.nama_usaha,
                m.bidang_usaha,
                m.kategori,
                m.logo,
                m.total_voting,
                m.description
            ')
            ->where('m.status_delete', 0);

        if ($fil_kategori !== 'all') {
            $builder->where('m.kategori', $fil_kategori);
        }

        return $builder->orderBy('m.total_voting', 'DESC');
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
                'message' => $this->validator->listErrors()
            ]);
        }

        $validColumns = [
            1 => 'm.id_voting',
            4 => 'm.nama_founders',
            5 => 'm.nama_usaha',
            6 => 'm.total_voting',
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

        dd($rows);

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

            $url_detail = base_url(
                'admin/voting/hasil/detail/' . encrypt_url($row['id_voting'])
            );

            $data[] = [
                '<label class="checkbox-custome">
                    <input type="checkbox" class="check-record" value="' . $row['id_voting'] . '">
                 </label>',
                $no,
                '<img src="' . $url_image . '" class="img-fluid">',
                $kategori,
                $nama_founders,
                $nama_usaha,
                number_format($row['total_voting']),
                '<div class="dropdown dropdown-action ' . $cact['access_action'] . ' text-center">
                    <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu">
                        <a href="' . $url_detail . '" class="dropdown-item">
                            <ion-icon name="create-sharp"></ion-icon> List Voting User
                        </a>
                    </div>
                </div>'
            ];
        }

        return json_response([
            'draw' => (int) $this->request->getGet('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    public function export()
    {
        $this->requireAccess('voting');

        $cact = $this->mainModel->check_access_action('voting');

        if ($cact['access_view'] === 'd-none') {
            return json_response([
                'status' => 0,
                'message' => 'Gagal, tidak memiliki akses.',
            ]);
        }

        $baseBuilder = $this->sql();

        $dataBuilder = clone $baseBuilder;
        $rows = $dataBuilder->get()->getResultArray();

        $output = '<table border="1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Nama Founders</th>
                    <th>Nama Usaha</th>
                    <th>Bidang Usaha</th>
                    <th>Deskripsi</th>
                    <th>Total Voting</th>
                </tr>
            </thead>
            <tbody>';

        $no = 0;
        foreach ($rows as $row) {
            $no++;
            $output .= '
                <tr>
                    <td>' . $no . '</td>
                    <td>' . $row['kategori'] . '</td>
                    <td>' . $row['nama_founders'] . '</td>
                    <td>' . $row['nama_usaha'] . '</td>
                    <td>' . $row['bidang_usaha'] . '</td>
                    <td>' . $row['description'] . '</td>
                    <td>' . $row['total_voting'] . '</td>
                </tr>';
        }

        $output .= '</tbody></table>';

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.ms-excel')
            ->setHeader(
                'Content-Disposition',
                'attachment; filename=Export-Data-Voting-' . date('Y-m-d-H-i-s') . '.xls'
            )
            ->setBody($output);
    }

    public function detail($id_enc)
    {
        $this->requireAccess('voting');

        $data = [
            'id_voting_enc' => $id_enc,
            'title' => 'Data Voting',
            'page' => 'admin/voting/detail',
        ];

        $this->data = array_merge($this->data, $data);

        return view('admin/index', $this->data);
    }

    protected function _sql_detail($id_voting_enc)
    {
        $id = decrypt_url($id_voting_enc);

        return $this->db->table('tb_voting_user m')
            ->select('u.id_user, u.nama, u.telp, u.email')
            ->join('tb_user u', 'u.id_user = m.id_user', 'left')
            ->where('m.id_voting', $id);
    }

    function datatables_detail($id_voting_enc)
    {
        $this->requireAccess('voting');

        $cact = $this->mainModel->check_access_action('voting');

        if ($cact['access_view'] === 'd-none') {
            return json_response([
                'status' => 0,
                'message' => 'Gagal, tidak memiliki akses.',
            ]);
        }

        $validColumns = [
            0 => 'id_user',
            1 => 'nama',
            2 => 'telp',
            3 => 'email',
        ];

        $params = [
            'start' => (int) $this->request->getGet('start'),
            'length' => (int) $this->request->getGet('length'),
            'order' => $this->request->getGet('order'),
            'search' => $this->request->getGet('search')['value'] ?? '',
        ];

        $baseBuilder = $this->_sql_detail($id_voting_enc);

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
            $data[] = [
                $no,
                $row['nama'],
                $row['telp'],
                $row['email']
            ];
        }

        return json_response([
            'draw' => (int) $this->request->getGet('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    function export_detail($id_enc)
    {
        $baseBuilder = $this->_sql_detail($id_enc);

        $dataBuilder = clone $baseBuilder;
        $rows = $dataBuilder->get()->getResultArray();

        $output =
            '<table class="table" border="1">
                <thead>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Telp</th>
                    <th>Email</th>
                </thead>
                <tbody>';

        $no = 0;
        foreach ($rows as $row) {
            $no++;

            $output .= '
                        <tr>
                            <td>' . $no . '</td>
                            <td>' . $row['nama'] . '</td>
                            <td>' . $row['telp'] . '</td>
                            <td>' . $row['email'] . '</td>
                        </tr>';
        }


        $output .= '</tbody></table>';

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.ms-excel')
            ->setHeader(
                'Content-Disposition',
                'attachment; filename=Export-Data-Voting-User-' . date('Y-m-d-H-i-s') . '.xls'
            )
            ->setBody($output);
    }
}