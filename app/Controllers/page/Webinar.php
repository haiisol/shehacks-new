<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Webinar extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
        $data['title']       = 'Webinar';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']        = 'page/webinar';
        $this->load->view('index', $data);
    }

    function fetch_data_webinar()
    {   
        $this->load->library('form_validation');
        $row = array(
            "limit"         => (int) $this->input->post('limit', TRUE),
            "offset"        => (int) $this->input->post('offset', TRUE),
        ); 

        $this->form_validation->set_data($row);
        $this->form_validation->set_rules('limit', 'limit', 'trim|required|numeric');
        $this->form_validation->set_rules('offset', 'offset', 'trim|required|numeric');

        if ( $this->form_validation->run() === false ) {
            $response['status']  = 0;
            $response['message'] = validation_errors();
            json_response($response);
            return;
        }

        $limit_post     = (int) $this->input->post('limit', TRUE);
        $offset_post    = (int) $this->input->post('offset', TRUE);
        
        if ($offset_post == 0) {
            $offset     = 0;
            $offset_end = $offset + $limit_post;
        } else {
            $offset_end = $offset_post + $limit_post;
            $offset     = $offset_end;
        }

        $query = $this->db->select('c.id_webinar, c.judul, c.kode_youtube')
                ->from('tb_webinar c')
                ->where('c.status_delete', 0)
                ->order_by('c.id_webinar', 'DESC')
                ->limit($limit_post, $offset_post)
                ->get()
                ->result_array();
        
        $data = array();

        foreach ($query as $key) {

            $row['id_webinar']  = $key['id_webinar'];
            $row['heading']     = $key['judul'];
            $row['video']       = $key['kode_youtube'];

            array_push($data, $row);
        }

        // check next load
        $sql_cek_load_more = "
            SELECT c.id_webinar 
                FROM tb_webinar c 
                WHERE c.status_delete = 0 
                ORDER BY c.id_webinar DESC
            LIMIT ".$limit_post." 
            OFFSET ".$offset_end." ";

        $query_cek = $this->db->query($sql_cek_load_more)->result_array();

        if ($query_cek) { $load_more = 1; } else { $load_more = 0; }
        
        $response['data']       = $data;
        $response['offset']     = $offset_end;
        $response['load_more']  = $load_more;
        $response['status']     = 1;
        $response['message']    = 'Success';

        json_response($response);
    }

}