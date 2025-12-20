<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Program extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {

        $data['title']       = '';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']        = 'page/program';
        $this->load->view('index', $data);
    }


}