<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Artikel extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
        $data['title']       = 'Artikel';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']        = 'artikel/artikel';
        $this->load->view('index', $data);
    }

    function detail($slug, $enc_id)
    {
        $enc_id_kode = preg_replace('/[^A-Za-z0-9]/', '', $enc_id);
        $id = decrypt_url($enc_id);

        $this->db->where('id_blog', $id);
        $query = $this->db->get('tb_blog');
        $get_blog = $query->row_array();

        if ($get_blog) {

            $data['id_enc']     = $enc_id_kode;

            $data['title']       = $get_blog['judul'];
            $data['description'] = $get_blog['judul'];
            $data['keywords']    = '';
            $data['page']        = 'artikel/artikel_detail';
            $this->load->view('index', $data);
        } 
        else {
            redirect('');
        }
    }
}