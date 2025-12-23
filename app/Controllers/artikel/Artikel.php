<?php

namespace App\Controllers\Artikel;

use App\Controllers\FrontController;
use Config\Database;

class Artikel extends FrontController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function index()
    {
        $get_kategori = $this->db->table('tb_blog_kategori')
            ->where('status_delete', 0)
            ->orderBy('nama', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Artikel',
            'page' => 'artikel/artikel',
            'get_kategori' => $get_kategori,
        ];

        $this->data = array_merge($this->data, $data);

        return view('index', $this->data);
    }

    public function detail($slug, $enc_id)
    {
        // sanitize encoded id
        $enc_id_kode = preg_replace('/[^A-Za-z0-9]/', '', $enc_id);

        $id = decrypt_url($enc_id);

        $get_blog = $this->db->table('tb_blog')
            ->where('id_blog', $id)
            ->get()
            ->getRowArray();

        if (!$get_blog) {
            return redirect()->to('/');
        }

        $data = [
            'id_enc' => $enc_id_kode,
            'title' => $get_blog['judul'],
            'description' => $get_blog['judul'],
            'keywords' => '',
            'page' => 'artikel/artikel_detail',
        ];

        $this->data = array_merge($this->data, $data);

        return view('index', $this->data);
    }
}
