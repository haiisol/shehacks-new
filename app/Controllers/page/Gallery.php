<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    // public function index()
    // {
    //     $data['title']       = 'Gallery';
    //     $data['description'] = '';
    //     $data['keywords']    = '';
    //     $data['page']        = 'page/gallery';
    //     $this->load->view('index', $data);
    // }

    // function fetch_data_gallery()
    // {
    //     $limit_post    = $this->input->post('limit');
    //     $offset_post   = $this->input->post('offset');
        
    //     if ($offset_post == 0) {
    //         $offset     = 0;
    //         $offset_end = $offset + $limit_post;
    //     } else {
    //         $offset_end = $offset_post + $limit_post;
    //         $offset     = $offset_end;
    //     }

    //     $sql = "
    //         SELECT c.heading, c.image 
    //         FROM tb_content c 
    //         WHERE c.status_delete = 0 
    //         AND c.section = 'gallery' 
    //         ORDER BY c.id DESC
    //         LIMIT ".$limit_post." 
    //         OFFSET ".$offset_post." ";

    //     $query = $this->db->query($sql)->result_array();
        
    //     $data = array();

    //     foreach ($query as $key) {

    //         if ($key['image']) {
    //             $image = $this->main_model->url_image($key['image'], 'image-content');
    //         } else {
    //             $image = '';
    //         }

    //         $row['heading'] = $key['heading'];
    //         $row['image']   = $image;

    //         array_push($data, $row);
    //     }


    //     // check next load
    //     $sql_cek_load_more = "
    //         SELECT c.id 
    //         FROM tb_content c 
    //         WHERE c.status_delete = 0 
    //         AND c.section = 'gallery' 
    //         ORDER BY c.id DESC
    //         LIMIT ".$limit_post." 
    //         OFFSET ".$offset_end." ";

    //     $query_cek = $this->db->query($sql_cek_load_more)->result_array();

    //     if ($query_cek) { $load_more = 1; } else { $load_more = 0; }
        
        
    //     $response['data']      = $data;
    //     $response['offset']    = $offset_end;
    //     $response['load_more'] = $load_more;
    //     $response['status']    = 1;
    //     $response['message']   = 'Success';

    //     json_response($response);
    // }
}