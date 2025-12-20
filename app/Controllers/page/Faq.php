<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
        $data['title']       = 'Frequently Asked Question';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']        = 'page/faq';
        $this->load->view('index', $data);
    }

    
}