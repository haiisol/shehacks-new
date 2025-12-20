<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Impact_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
        $data['title']       = 'Impact Report';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']        = 'page/impact_report';
        $this->load->view('index', $data);
    }

}