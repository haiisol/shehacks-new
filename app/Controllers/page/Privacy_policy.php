<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Privacy_policy extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
        $data['title']       = 'Privacy & Policy';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']        = 'page/privacy_policy';
        $this->load->view('index', $data);
    }

    function fetch_data()
    {
        $sql = "SELECT c.heading, c.content 
                FROM tb_content c 
                WHERE c.status_delete = 0 
                AND c.section = 'privacy_policy' 
                ORDER BY c.id DESC
                LIMIT 1 ";

        $query = $this->db->query($sql)->result_array();
        
        $data = array();

        foreach ($query as $key) {

            $row['heading'] = $key['heading'];
            $row['content'] = $key['content'];

            array_push($data, $row);
        }
        
        $response['data']    = $data;
        $response['status']  = 1;
        $response['message'] = 'Success';

        json_response($response);
    }
    
}