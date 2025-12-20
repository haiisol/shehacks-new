<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Artikel_data extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
        
    }

    function _sql($param)
    {
        $limit_post  = $this->input->get('limit');
        $offset_post = $this->input->get('offset');
        $filter_post = parse_str($this->input->get('filter'), $filter_data);

        $data = array();

        if ($offset_post == 0) {
            $offset     = $offset_post;
            $offset_end = $offset + $limit_post;
        } else {
            $offset_end = $offset_post + $limit_post;
            $offset     = $offset_end;
        }

        $this->db->select('b.*');
        $this->db->from('tb_blog b');
        $this->db->join('tb_admin_user a', 'a.id_admin = b.id_admin', 'left');
        $this->db->where('b.id_blog >', 0);

        if (!empty($filter_data['search'])) {
            $this->db->like('b.judul', $filter_data['search']);
        }

        if (!empty($filter_data['kategori'])) {
            $this->db->where('b.id_blog_kategori', $filter_data['kategori'], TRUE);
        }

        $this->db->order_by('b.date_create', 'DESC');
        $this->db->limit($limit_post);

        $offset = ($param == 'main') ? $offset_post : $offset_end;
        $this->db->offset($offset);

        $query = $this->db->get();

        return array(
            'query'      => $query, 
            'offset_end' => $offset_end
        );
    }

    function fetch_data() 
    {
        $data = array();

        $query = $this->_sql('main')['query']->result_array();

        foreach ($query as $key) {
            
            $get_kategori = $this->db->query("SELECT nama FROM tb_blog_kategori WHERE id_blog_kategori = ".$key['id_blog_kategori']." ")->row_array();

            $row['url_detail']          = url_blog_detail($key['slug'], $key['id_blog']);
            $row['id_blog']             = $key['id_blog'];
            $row['judul']               = $key['judul'];
            $row['deskripsi']           = strip_tags($key['deskripsi']);
            $row['kategori']            = $get_kategori['nama'];
            $row['date_create']         = date_ind(date('Y-m-d', strtotime($key['date_create'])), 'short');
            $row['gambar']              = $this->main_model->url_image($key['gambar'], 'file-blog');
            $row['gambar_keterangan']   = $key['gambar_keterangan'];

            array_push($data, $row);
        }
        
        $query_cek = $this->_sql('second')['query']->result_array();

        if ($query_cek) {
            $load_more = 1;
        } else {
            $load_more = 0;
        }

        $response['data']      = $data;
        $response['offset']    = $this->_sql('main')['offset_end'];
        $response['load_more'] = $load_more;
        $response['status']    = 1;
        $response['message']   = 'Success';

        json_response($response);
    }


    function fetch_data_detail() 
    {
        $id = decrypt_url($this->input->get('id_enc', TRUE));

        $this->db->where('id_blog', $id);
        $sql = $this->db->get('tb_blog');
        $query = $sql->result_array();

        if ($query) {

            $data = array();

            foreach ($query as $key) {
                
                $get_kategori   = $this->db->query("SELECT nama FROM tb_blog_kategori WHERE id_blog_kategori = ".$key['id_blog_kategori']." ")->row_array();
                $get_admin      = $this->db->query("SELECT nama_admin FROM tb_admin_user WHERE id_admin = ".$key['id_admin']." ")->row_array();

                $row['judul']               = $key['judul'];
                $row['kategori']            = $get_kategori['nama'];
                $row['admin']               = $get_admin['nama_admin'];
                $row['deskripsi']           = $key['deskripsi'];
                $row['date_create']         = date_ind(date('Y-m-d', strtotime($key['date_create'])), 'short');
                $row['tags']                = $this->_fetch_tags($key['id_blog']);
                $row['gambar']              = $this->main_model->url_image($key['gambar'], 'file-blog');
                $row['gambar_keterangan']   = $key['gambar_keterangan'];
                $row['gambar_sumber']       = $key['gambar_sumber'];

                array_push($data, $row);
            }
            
            $response['data']      = $data;
            $response['status']    = 1;
            $response['message']   = 'Success';
        }
        else {
            $response['status']    = 0;
            $response['message']   = 'Data blog tidak ditemukan';
        }

        json_response($response);
    }

    function _fetch_tags($id_blog) {

        if ($id_blog) {

            $data = array();

            $get_tags = $this->db->query("
                SELECT tg.id_tags, tg.tags 
                FROM tb_blog_rel_tags rt 
                LEFT JOIN tb_blog_tags tg ON tg.id_tags = rt.id_tags 
                WHERE rt.id_blog = ".$id_blog." 
                ")->result_array();

            foreach ($get_tags as $key) {

                $isi['url_tags'] = url_blog_tags($key['tags'], $key['id_tags']);
                $isi['tags']     = $key['tags'];

                array_push($data, $isi);
            }
        }
        else {
            $data = '';
        }

        return $data;
    }

}