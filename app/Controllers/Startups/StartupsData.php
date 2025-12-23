<?php

namespace App\Controllers\Startups;

use App\Controllers\BaseController;
use Config\Database;
use Config\Services;
use CodeIgniter\API\ResponseTrait;

class StartupsData extends BaseController
{
    use ResponseTrait;

    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    private function _sql($param)
    {
        $validation = service('validation');

        $rules = [
            'limit'  => 'required|numeric',
            'offset' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return [
                'error' => true,
                'messages' => $this->validator->getErrors()
            ];
        }

        $limit_post  = (int) $this->request->getGet('limit');
        $offset_post = (int) $this->request->getGet('offset');

        // Parse filter string (e.g., search=foo&sector=1)
        $filter_str = $this->request->getGet('filter');
        parse_str($filter_str ?? '', $filter_data);

        $offset_end = $offset_post + $limit_post;

        $builder = $this->db->table('tb_startup b');
        $builder->select('b.*');
        $builder->where('b.status_delete', 0);

        if (!empty($filter_data['search'])) {
            $builder->like('b.startup_name', $filter_data['search']);
        }
        if (!empty($filter_data['period'])) {
            $builder->where('b.period', $filter_data['period']);
        }
        if (!empty($filter_data['sector'])) {
            $builder->where('b.id_sector', $filter_data['sector']);
        }

        $builder->orderBy('b.date_create', 'DESC');
        $builder->limit($limit_post);

        // Logic for offset calculation
        $actual_offset = ($param == 'main') ? $offset_post : $offset_end;
        $builder->offset($actual_offset);

        return [
            'error'      => false,
            'query'      => $builder->get(),
            'offset_end' => $offset_end
        ];
    }

    public function fetch_data()
    {
        $sql_main = $this->_sql('main');

        if ($sql_main['error']) {
            return json_response(['status' => 0, 'message' => $sql_main['messages']]);
        }

        $query = $sql_main['query']->getResultArray();
        $data  = [];

        foreach ($query as $key) {
            $get_sector = $this->db->table('tb_master_sector')
                ->where('id_sector', $key['id_sector'])
                ->get()
                ->getRowArray();

            $data[] = [
                'url_detail'       => url_startups_detail($key['slug'], $key['id_startup']),
                'id_startup'       => $key['id_startup'],
                'sector'           => $get_sector['nama'] ?? '',
                'period'           => $key['period'],
                'startup_name'     => $key['startup_name'],
                'sort_description' => strip_tags($key['sort_description']),
                'description'      => strip_tags($key['description']),
                'founders_name'    => $key['founders_name'],
                'founders_url'     => $key['founders_url'] ?? '',
                'url_label'        => $key['url_label'] ?? '',
                'url'              => $key['url'] ?? '',
                'date_create'      => date_ind(date('Y-m-d', strtotime($key['date_create'])), 'short'),
                'gambar'           => url_image($key['thumbnail'], 'image-content'),
                'logo'             => url_image($key['logo'], 'image-content'),
            ];
        }

        // Check if there's more data for "Load More" button
        $sql_second = $this->_sql('second');
        $load_more  = (!empty($sql_second['query']->getResultArray())) ? 1 : 0;

        return json_response([
            'data'      => $data,
            'offset'    => $sql_main['offset_end'],
            'load_more' => $load_more,
            'status'    => 1,
            'message'   => 'Success'
        ]);
    }

    public function fetch_data_detail()
    {
        $id_enc = $this->request->getGet('id_enc');

        if (empty($id_enc) || !preg_match('/^[a-zA-Z0-9]+$/', $id_enc)) {
            return json_response(['status' => 0, 'message' => 'Invalid ID']);
        }

        $id = decrypt_url($id_enc);

        $query = $this->db->table('tb_startup b')
            ->where('b.id_startup', $id)
            ->get()
            ->getResultArray();

        if ($query) {
            $data = [];
            foreach ($query as $key) {
                $get_sector = $this->db->table('tb_master_sector')
                    ->where('id_sector', $key['id_sector'])
                    ->get()
                    ->getRowArray();

                $data[] = [
                    'startup_name'     => $key['startup_name'],
                    'founders_name'    => $key['founders_name'],
                    'sector'           => $get_sector['nama'] ?? '',
                    'period'           => $key['period'],
                    'sort_description' => $key['sort_description'],
                    'description'      => $key['description'],
                    'date_create'      => date_ind(date('Y-m-d', strtotime($key['date_create'])), 'short'),
                    'logo'             => url_image($key['logo'], 'image-content'),
                    'thumbnail'        => url_image($key['thumbnail'], 'image-content'),
                ];
            }

            return json_response(['data' => $data, 'status' => 1, 'message' => 'Success']);
        }

        return json_response(['status' => 0, 'message' => 'Data not found']);
    }

    private function _fetch_tags($id_startup)
    {
        if (!$id_startup) return '';

        $get_tags = $this->db->table('tb_startup_rel_tags rt')
            ->select('tg.id_tags, tg.tags')
            ->join('tb_startup_tags tg', 'tg.id_tags = rt.id_tags', 'left')
            ->where('rt.id_startup', $id_startup)
            ->get()
            ->getResultArray();

        $data = [];
        foreach ($get_tags as $key) {
            $data[] = [
                'url_tags' => url_blog_tags($key['tags'], $key['id_tags']),
                'tags'     => $key['tags']
            ];
        }

        return $data;
    }
}
