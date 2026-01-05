<?php

namespace App\Controllers\Page;

use App\Controllers\FrontController;
use Config\Database;

class Webinar extends FrontController
{
    public function index()
    {
        $data = [
            'title' => 'Webinar',
            'page' => 'page/webinar'
        ];

        $this->data = array_merge($this->data, $data);

        return view('index', $this->data);
    }

    public function fetch_data_webinar()
    {
        try {

            $validation = service('validation');

            $dataInput = [
                'limit' => (int) $this->request->getGet('limit'),
                'offset' => (int) $this->request->getGet('offset'),
            ];

            $validation->setRules([
                'limit' => 'required|numeric',
                'offset' => 'required|numeric'
            ]);

            if (!$validation->run($dataInput)) {
                return json_response([
                    'status' => 0,
                    'message' => $validation->getErrors()
                ]);
            }

            $limit = $dataInput['limit'];
            $offset = $dataInput['offset'];

            // calculate next offset
            if ($offset == 0) {
                $offsetEnd = $limit;
            } else {
                $offsetEnd = $offset + $limit;
            }

            $db = Database::connect();

            // ---------- MAIN DATA ----------
            $query = $db->table('tb_webinar')
                ->select('id_webinar, judul, kode_youtube')
                ->where('status_delete', 0)
                ->orderBy('id_webinar', 'DESC')
                ->limit($limit, $offset)
                ->get()
                ->getResultArray();

            $data = [];

            foreach ($query as $row) {
                $data[] = [
                    'id_webinar' => $row['id_webinar'],
                    'heading' => $row['judul'],
                    'video' => $row['kode_youtube']
                ];
            }

            // ---------- CHECK NEXT LOAD ----------
            $checkNext = $db->table('tb_webinar')
                ->select('id_webinar')
                ->where('status_delete', 0)
                ->orderBy('id_webinar', 'DESC')
                ->limit($limit, $offsetEnd)
                ->get()
                ->getResultArray();

            $loadMore = !empty($checkNext) ? 1 : 0;

            return json_response([
                'data' => $data,
                'offset' => $offsetEnd,
                'load_more' => $loadMore,
                'status' => 1,
                'message' => 'Success'
            ]);

        } catch (\Throwable $e) {

            return json_response([
                'data' => [],
                'status' => 0,
                'message' => 'Database Error',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
