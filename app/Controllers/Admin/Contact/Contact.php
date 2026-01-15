<?php

namespace App\Controllers\Admin\Contact;

use App\Controllers\AdminController;
use DateTime;

class Contact extends AdminController
{

    protected $db;
    function __construct()
    {
        $this->db = db_connect();
    }

    public function index()
    {
        $this->requireAccess('contact');

        $data = [
            'title' => 'Data Contact',
            'page' => 'admin/contact/contact',
        ];

        $this->data = array_merge($this->data, $data);
        return view('admin/index', $this->data);
    }

    protected function sql()
    {
        $filDate = trim($this->request->getGet('fil_date') ?? '');

        $tglPertama  = date('01/m/Y');
        $tglTerakhir = date('t/m/Y');

        $filDateNew = empty($filDate)
            ? $tglPertama . ' - ' . $tglTerakhir
            : $filDate;

        $tglBuffer = explode('-', $filDateNew);

        $tglStart = DateTime::createFromFormat('d/m/Y', trim($tglBuffer[0]))->format('Y-m-d');
        $tglEnd   = DateTime::createFromFormat('d/m/Y', trim($tglBuffer[1]))->format('Y-m-d');

        $builder = $this->db->table('tb_message c')
            ->select('c.*')
            ->where('c.status', 0);

        if (!empty($filDate)) {
            $builder->where('c.date >=', $tglStart . ' 00:00:00');
            $builder->where('c.date <=', $tglEnd . ' 23:59:59');
        }

        return $builder;
    }

    function datatables()
    {
        $this->requireAccess('contact');

        $validColumns = array(
            1 => 'c.id',
            2 => 'c.name',
            3 => 'c.email',
            4 => 'c.subject',
            5 => 'c.date',
        );

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

        $no = (int) $this->request->getGet('start');
        $data = [];

        foreach ($rows as $key) {
            $no++;

            $cact = $this->mainModel->check_access_action('contact');

            // date update
            $tanggal = ($key['date'] === '0000-00-00 00:00:00')
                ? '-'
                : time_ago_from_3($key['date']);

            $data[] = array(
                '<label class="checkbox-custome"><input type="checkbox" name="check-record" value="' . $key['id'] . '" class="check-record"></label>',
                $no,
                '<a href="javascript:void(0)" class="detail-data" data="' . $key['id'] . '">' . character_limiter($key['name'], 25) . '</a>',
                character_limiter(strip_tags($key['email']), 25),
                character_limiter(strip_tags($key['subject']), 40),
                $tanggal,
                '<div class="dropdown dropdown-action ' . $cact['access_action'] . ' options ms-auto text-center">
                    <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" id="dropdown-action">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end" aria-labelledby="dropdown-action">
                     
                        <a href="javascript:void(0)" class="dropdown-item detail-data" id="delete-data" data="' . $key['id'] . '""><ion-icon name="eye-outline"></ion-icon> Detail</a>
                    </div>
                </div>'
            );
        }

        return json_response([
            'draw' => (int) $this->request->getGet('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }


    function detail_data()
    {
        $this->requireAccess('contact');

        $cact = $this->mainModel->check_access_action('contact');

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

        $data = $this->db->table('tb_message')
            ->where('id', $this->request->getPost('id'))
            ->get()
            ->getRowArray();

        return json_response([
            'id'      => $data['id'],
            'name'    => $data['name'],
            'email'   => $data['email'],
            'phone'   => $data['phone'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'date'    => date('d-m-Y H:i', strtotime($data['date']))
        ]);
    }

    function export()
    {
        $this->requireAccess('contact');

        $cact = $this->mainModel->check_access_action('contact');

        if ($cact['access_view'] === 'd-none') {
            return redirect()->to('/404');
        }

        $query = $this->sql()->get()->getResultArray();

        $output = '
        <table border="1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>';

        $no = 1;
        foreach ($query as $row) {
            $output .= '
            <tr>
                <td>' . $no++ . '</td>
                <td>' . $row['name'] . '</td>
                <td>' . $row['email'] . '</td>
                <td>' . $row['phone'] . '</td>
                <td>' . $row['subject'] . '</td>
                <td>' . $row['message'] . '</td>
                <td>' . date('d-m-Y H:i', strtotime($row['date'])) . '</td>
            </tr>';
        }

        $output .= '</tbody></table>';

        $filename = 'Export-Data-Contact-' . date('Y-m-d-H-i-s') . '.xls';

        return $this->response
            ->setHeader('Content-Type', 'application/octet-stream')
            ->setHeader('Content-Disposition', 'attachment; filename=' . $filename)
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($output);
    }
}
