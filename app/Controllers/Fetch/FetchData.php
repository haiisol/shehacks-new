<?php

namespace App\Controllers\Fetch;

use App\Controllers\BaseController;
use App\Models\MainModel;
use App\Models\FormatTimeModel;

class FetchData extends BaseController
{
    protected $db;
    protected $session;
    protected $mainModel;
    protected $formatTimeModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->mainModel = new MainModel();
        $this->formatTimeModel = new FormatTimeModel();
    }

    public function index()
    {
        // Default index
    }

    public function fetch_data_voting()
    {
        $logged_in_front = $this->session->get('logged_in_front');
        $year = date('Y');
        $status_vote = 0;

        if ($logged_in_front) {
            $id_user = key_auth();
            $get_vote = $this->db->query("SELECT id_user 
                FROM tb_voting_user 
                WHERE id_user = ? 
                AND YEAR(date_create) = ?", [$id_user, $year])->getRowArray();

            if ($get_vote) {
                $status_vote = 1;
            }
        }

        $builder = $this->db->table('tb_voting v');
        $builder->select('v.id_voting, v.kategori, v.nama_founders, v.domisili, v.nama_usaha, v.bidang_usaha, v.description, v.logo, v.video_upload');
        $builder->where('v.status_delete', 0);

        $query = $builder->get()->getResultArray();

        $data_mvp = [];
        $data_ide = [];

        foreach ($query as $key) {
            $row = [
                'id_voting_enc' => encrypt_url($key['id_voting']),
                'nama_founders' => $key['nama_founders'],
                'nama_usaha'    => $key['nama_usaha'],
                'bidang_usaha'  => $key['bidang_usaha'],
                'domisili'      => ucwords(strtolower($key['domisili'])),
                'description'   => strip_tags($key['description']),
                'logo'          => url_image($key['logo'], 'image-logo'),
                'video_upload'  => $key['video_upload'],
                'status_vote'   => $status_vote,
            ];

            if ($key['kategori'] == 'Innovate') {
                $data_mvp[] = $row;
            } else {
                $data_ide[] = $row;
            }
        }

        return json_response([
            'data_mvp' => $data_mvp,
            'data_ide' => $data_ide,
            'status'   => 1,
            'message'  => 'Success'
        ]);
    }

    public function post_data_voting()
    {
        $logged_in_front = $this->session->get('logged_in_front');
        $id_voting_enc = $this->request->getPost('id_voting_enc');
        $id_voting = decrypt_url($id_voting_enc);

        if (!$logged_in_front) {
            return json_response(['status' => 0, 'message' => 'Silahkan login terlebih dahulu.']);
        }

        $year = date('Y');
        $id_user = key_auth();

        $get_vote = $this->db->query("SELECT id_user 
            FROM tb_voting_user 
            WHERE id_user = ? 
            AND YEAR(date_create) = ?", [$id_user, $year])->getRowArray();

        if ($get_vote) {
            return json_response(['status' => 0, 'message' => 'Gagal, Anda sudah pernah voting']);
        }

        $data = [
            'id_voting'   => $id_voting,
            'id_user'     => $id_user,
            'date_create' => date('Y-m-d H:i:s')
        ];

        if ($this->db->table('tb_voting_user')->insert($data)) {
            $get_vote = $this->db->table('tb_voting')->select('total_voting')->where('id_voting', $id_voting)->get()->getRowArray();

            $data_update['total_voting'] = $get_vote['total_voting'] + 1;
            $this->mainModel->update_data('tb_voting', $data_update, 'id_voting', $id_voting);

            return json_response(['status' => 1, 'message' => 'Success.']);
        }

        return json_response(['status' => 0, 'message' => 'Failed.']);
    }

    public function fetch_data_intro()
    {
        $param = $this->request->getGet('param');

        if (empty($param)) {
            return json_response(['status' => 0, 'message' => 'The param field is required.']);
        }

        $builder = $this->db->table('tb_content');
        $builder->select('heading, subheading, content, image, button_url, button_text');
        $builder->where('status_delete', 0);

        if ($param) {
            $builder->where('section', $param);
        }

        $query = $builder->limit(1)->get()->getResultArray();
        $data = [];

        foreach ($query as $key) {
            $get_web = $this->db->table('tb_admin_web')->select('instagram')->where('id', 1)->get()->getRowArray();

            $data[] = [
                'heading'     => $key['heading'],
                'subheading'  => $key['subheading'],
                'content'     => $key['content'],
                'button_text' => $key['button_text'],
                'button_url'  => $key['button_url'],
                'image'       => url_image($key['image'], 'image-content'),
                'instagram'   => $get_web['instagram'] ?? '',
            ];
        }

        return json_response([
            'data'    => $data,
            'status'  => 1,
            'message' => 'Success'
        ]);
    }
    public function fetch_data_schedule()
    {
        $query = $this->db->table('tb_content')
            ->select('heading, subheading, image, image_2')
            ->where('status_delete', 0)
            ->where('section', 'home_rangkaian_event')
            ->limit(1)
            ->get()
            ->getResultArray();

        $data = [];
        foreach ($query as $key) {
            $data[] = [
                'heading'    => $key['heading'],
                'subheading' => $key['subheading'],
                'image'      => url_image($key['image'], 'image-content'),
                'image_2'    => url_image($key['image_2'], 'image-content'),
            ];
        }

        return json_response([
            'data'    => $data,
            'status'  => 1,
            'message' => 'Success'
        ]);
    }

    public function fetch_data_program_benefit()
    {
        $query = $this->db->table('tb_content')
            ->select('heading, subheading, content, image, date_create')
            ->where('status_delete', 0)
            ->where('section', 'program_topik')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        $data = [];
        foreach ($query as $key) {
            $data[] = [
                'heading'    => $key['heading'],
                'subheading' => $key['subheading'],
                'content'    => $key['content'],
                'tanggal'    => $this->formatTimeModel->tanggal_transaction($key['date_create'], ''),
                'image'      => url_image($key['image'], 'image-content'),
            ];
        }

        return json_response(['data' => $data, 'status' => 1, 'message' => 'Success']);
    }

    public function fetch_data_agenda()
    {
        $query = $this->db->table('tb_content')
            ->select('heading, subheading, content, image, date_create')
            ->where('status_delete', 0)
            ->where('section', 'home_agenda')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        $data = [];
        foreach ($query as $key) {
            $data[] = [
                'heading'    => $key['heading'],
                'subheading' => $key['subheading'],
                'content'    => $key['content'],
                'tanggal'    => $this->formatTimeModel->tanggal_transaction($key['date_create'], ''),
                'image'      => url_image($key['image'], 'image-content'),
            ];
        }

        return json_response(['data' => $data, 'status' => 1, 'message' => 'Success']);
    }

    public function fetch_data_artikel()
    {
        $query = $this->db->table('tb_blog')
            ->select('slug, id_blog, id_blog_kategori, judul as heading, deskripsi as content, gambar, date_create')
            ->where('id_blog !=', 0)
            ->orderBy('date_create', 'DESC')
            ->limit(3)
            ->get()
            ->getResultArray();

        $data = [];

        foreach ($query as $key) {
            $get_kategori = $this->db->table('tb_blog_kategori')
                ->select('nama')
                ->where('id_blog_kategori', $key['id_blog_kategori'])
                ->get()
                ->getRowArray();

            $data[] = [
                'url_detail' => url_blog_detail($key['slug'], $key['id_blog']),
                'heading'    => $key['heading'],
                'kategori'   => $get_kategori['nama'] ?? 'Uncategorized',
                'content'    => strip_tags(character_limiter($key['content'], 150)),
                'tanggal'    => $this->formatTimeModel->tanggal_transaction($key['date_create'], ''),
                'image'      => url_image($key['gambar'], 'file-blog'),
            ];
        }

        return json_response(['data' => $data, 'status' => 1, 'message' => 'Success']);
    }

    public function fetch_data_artikel_alumni()
    {
        $query = $this->db->table('tb_blog')
            ->select('slug, id_blog, id_blog_kategori, judul as heading, deskripsi as content, gambar, date_create')
            ->where('id_blog !=', 0)
            ->where('id_blog_kategori', '10')
            ->orderBy('date_create', 'DESC')
            ->limit(3)
            ->get()
            ->getResultArray();

        $data = [];

        foreach ($query as $key) {
            $get_kategori = $this->db->table('tb_blog_kategori')
                ->select('nama')
                ->where('id_blog_kategori', $key['id_blog_kategori'])
                ->get()
                ->getRowArray();

            $data[] = [
                'url_detail' => url_blog_detail($key['slug'], $key['id_blog']),
                'heading'    => $key['heading'],
                'kategori'   => $get_kategori['nama'] ?? 'Alumni',
                'content'    => strip_tags(character_limiter($key['content'], 150)),
                'tanggal'    => $this->formatTimeModel->tanggal_transaction($key['date_create'], ''),
                'image'      => url_image($key['gambar'], 'file-blog'),
            ];
        }

        return json_response(['data' => $data, 'status' => 1, 'message' => 'Success']);
    }

    public function fetch_data_video()
    {
        $query = $this->db->table('tb_content')
            ->select('heading, video')
            ->where('status_delete', 0)
            ->where('section', 'home_video')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        $data = [];
        foreach ($query as $key) {
            $data[] = [
                'heading' => $key['heading'],
                'video'   => $key['video']
            ];
        }

        return json_response([
            'data'    => $data,
            'status'  => 1,
            'message' => 'Success'
        ]);
    }

    public function fetch_data_testimoni()
    {
        $query = $this->db->table('tb_content')
            ->select('heading, subheading, content, image')
            ->where('status_delete', 0)
            ->where('section', 'home_testimoni')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        $data = [];
        foreach ($query as $key) {
            $data[] = [
                'heading'    => $key['heading'],
                'subheading' => $key['subheading'],
                'content'    => $key['content'],
                'image'      => url_image($key['image'], 'image-content')
            ];
        }

        return json_response([
            'data'    => $data,
            'status'  => 1,
            'message' => 'Success'
        ]);
    }

    public function fetch_data_partner()
    {
        $categories = $this->db->table('tb_partner_kategori')
            ->select('id_kategori, nama')
            ->where('status_delete', 0)
            ->orderBy('urutan', 'ASC')
            ->get()
            ->getResultArray();

        $data = [];

        if ($categories) {
            foreach ($categories as $cat) {
                $partners = $this->db->table('tb_partner')
                    ->select('id_partner, nama, image, url')
                    ->where('status_delete', 0)
                    ->where('id_kategori', $cat['id_kategori'])
                    ->orderBy('urutan', 'ASC')
                    ->get()
                    ->getResultArray();

                $partner_list = [];
                foreach ($partners as $p) {
                    $partner_list[] = [
                        'id_partner' => $p['id_partner'],
                        'nama'       => $p['nama'],
                        'image'      => url_image($p['image'], 'image-content'),
                        'url'        => $p['url']
                    ];
                }

                $data[] = [
                    'id_kategori' => $cat['id_kategori'],
                    'nama'        => $cat['nama'],
                    'partner'     => $partner_list
                ];
            }

            return json_response(['data' => $data, 'status' => 1, 'message' => 'Yess']);
        }

        return json_response(['data' => [], 'status' => 0, 'message' => 'Noo']);
    }

    public function fetch_data_faq()
    {
        $search = trim($this->request->getGet('search') ?? '');
        $limit  = trim($this->request->getGet('limit') ?? '');

        $builder = $this->db->table('tb_content')
            ->select('id, heading, description')
            ->where('status_delete', 0)
            ->where('section', 'faq');

        if (!empty($search)) {
            $builder->like('heading', $search);
        }

        if (!empty($limit)) {
            $builder->limit((int)$limit);
        }

        $query = $builder->orderBy('id', 'ASC')->get()->getResultArray();

        if ($query) {
            return json_response(['data' => $query, 'status' => 1, 'message' => 'Yess']);
        }

        return json_response(['data' => [], 'status' => 0, 'message' => 'Noo']);
    }

    public function fetch_data_banner_popup()
    {
        $query = $this->db->table('tb_content')
            ->select('button_url, image')
            ->where('status_delete', 0)
            ->where('status', 1)
            ->where('section', 'banner_popup')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        $data = [];
        foreach ($query as $key) {
            $data[] = [
                'button_url' => $key['button_url'],
                'image'      => !empty($key['image']) ? url_image($key['image'], 'image-content') : ''
            ];
        }

        if (!empty($data)) {
            return json_response(['data' => $data, 'status' => 1, 'message' => 'Success']);
        }

        return json_response(['data' => [], 'status' => 0, 'message' => 'Gagal']);
    }
}
