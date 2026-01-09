<?php
namespace App\Controllers\Artikel;

use App\Controllers\BaseController;

class ArtikelData extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    protected function _sql(string $param)
    {
        $limit_post = (int) $this->request->getGet('limit');
        $offset_post = (int) $this->request->getGet('offset');

        $filter_data = [];
        parse_str($this->request->getGet('filter'), $filter_data);

        if ($offset_post === 0) {
            $offset = $offset_post;
            $offset_end = $offset + $limit_post;
        } else {
            $offset_end = $offset_post + $limit_post;
            $offset = $offset_end;
        }

        $builder = $this->db->table('tb_blog b');
        $builder->select('b.*')
            ->join('tb_admin_user a', 'a.id_admin = b.id_admin', 'left')
            ->where('b.id_blog >', 0);

        if (!empty($filter_data['search'])) {
            $builder->like('b.judul', $filter_data['search']);
        }

        if (!empty($filter_data['kategori'])) {
            $builder->where('b.id_blog_kategori', $filter_data['kategori']);
        }

        $builder->orderBy('b.date_create', 'DESC')
            ->limit($limit_post);

        $offset = ($param === 'main') ? $offset_post : $offset_end;
        $builder->offset($offset);

        $query = $builder->get();

        return [
            'query' => $query,
            'offset_end' => $offset_end,
        ];
    }

    public function fetch_data()
    {
        $data = [];

        $result = $this->_sql('main')['query']->getResultArray();

        foreach ($result as $key) {

            $get_kategori = $this->db->table('tb_blog_kategori')
                ->select('nama')
                ->where('id_blog_kategori', $key['id_blog_kategori'])
                ->get()
                ->getRowArray();

            $row = [
                'url_detail' => url_blog_detail($key['slug'], $key['id_blog']),
                'id_blog' => $key['id_blog'],
                'judul' => $key['judul'],
                'deskripsi' => strip_tags($key['deskripsi']),
                'kategori' => $get_kategori['nama'] ?? '',
                'date_create' => date_ind(date('Y-m-d', strtotime($key['date_create'])), 'short'),
                'gambar' => url_image($key['gambar'], 'file-blog'),
                'gambar_keterangan' => $key['gambar_keterangan'],
            ];

            $data[] = $row;
        }

        $query_cek = $this->_sql('second')['query']->getResultArray();
        $load_more = $query_cek ? 1 : 0;

        return json_response([
            'data' => $data,
            'offset' => $this->_sql('main')['offset_end'],
            'load_more' => $load_more,
            'status' => 1,
            'message' => 'Success',
        ]);
    }


    public function fetch_data_detail()
    {
        $id = decrypt_url($this->request->getGet('id_enc'));

        $query = $this->db->table('tb_blog')
            ->where('id_blog', $id)
            ->get()
            ->getResultArray();

        if (!$query) {
            return json_response([
                'status' => 0,
                'message' => 'Data blog tidak ditemukan',
            ]);
        }

        $data = [];

        foreach ($query as $key) {

            $get_kategori = $this->db->table('tb_blog_kategori')
                ->select('nama')
                ->where('id_blog_kategori', $key['id_blog_kategori'])
                ->get()
                ->getRowArray();

            $get_admin = $this->db->table('tb_admin_user')
                ->select('nama_admin')
                ->where('id_admin', $key['id_admin'])
                ->get()
                ->getRowArray();

            $row = [
                'judul' => $key['judul'],
                'kategori' => $get_kategori['nama'] ?? '',
                'admin' => $get_admin['nama_admin'] ?? '',
                'deskripsi' => $key['deskripsi'],
                'date_create' => date_ind(date('Y-m-d', strtotime($key['date_create'])), 'short'),
                'tags' => $this->_fetch_tags($key['id_blog']),
                'gambar' => url_image($key['gambar'], 'file-blog'),
                'gambar_keterangan' => $key['gambar_keterangan'],
                'gambar_sumber' => $key['gambar_sumber'],
            ];

            $data[] = $row;
        }

        return json_response([
            'data' => $data,
            'status' => 1,
            'message' => 'Success',
        ]);
    }

    protected function _fetch_tags(int $id_blog)
    {
        if (!$id_blog) {
            return [];
        }

        $data = [];

        $get_tags = $this->db->table('tb_blog_rel_tags rt')
            ->select('tg.id_tags, tg.tags')
            ->join('tb_blog_tags tg', 'tg.id_tags = rt.id_tags', 'left')
            ->where('rt.id_blog', $id_blog)
            ->get()
            ->getResultArray();
            
        foreach ($get_tags as $key) {
            $data[] = [
                'url_tags' => url_blog_tags($key['tags'], $key['id_tags']),
                'tags' => $key['tags'],
            ];
        }

        return $data;
    }

}