<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Privacy_policy extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }
    
    public function index()
    {
        $data = $this->main_model->check_access('content_page');
        $cact = $this->main_model->check_access_action('content_page');

        $data['access_edit'] = $cact['access_edit'];

        $data['title']       = 'Data Privacy Policy';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']           = 'admin/content/privacy_policy';
        $this->load->view('admin/index', $data);
    }

    function get_data()
    {   
        $cact = $this->main_model->check_access_action('content_page');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 
            $query = $this->db->query("SELECT * FROM tb_content WHERE section = 'privacy_policy' ")->row_array();
            $response['query'] = $query;
        }

        json_response($response);
    }

    function edit_data()
    {   
        $this->main_model->check_access('content_page');
        $cact = $this->main_model->check_access_action('content_page');

        if ($cact['access_edit'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {  

            $content       = sanitize_input_textEditor($this->input->post('content'));
            $id            = (int) $this->input->post('id');

            $this->load->library('form_validation');
            $row = array(
                "content" => $content,
                "id"      => $id,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('content', 'content', 'trim|required');
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $data['content']     = $content;
            $data['date_update'] = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');
            
            $query = $this->main_model->update_data('tb_content', $data, 'id', $id);

            if($query) {
                $response['status']  = 1;
                $response['message'] = 'Data berhasil Disimpan.';
            } else {
                $response['status']  = 0;
                $response['message'] = 'Gagal menyimpan data.';
            }
        }

        json_response($response);
    }

    public function valid_huruf_angka_spasi($str) {
        if (preg_match('/^[a-zA-Z0-9 ]+$/', $str)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('valid_huruf_angka_spasi', 'Kolom {field} hanya boleh berisi huruf, angka, dan spasi.');
            return FALSE;
        }
    }

    public function valid_angka_koma($str) {

        if (is_numeric($str)) {
            return TRUE;
        }
    
        if (preg_match('/^\["[0-9]+"(,"[0-9]+")*\]$/', $str)) {
            return TRUE;
        }
    
        $this->form_validation->set_message('valid_angka_koma', 'Kolom {field} hanya boleh berisi angka atau array angka dengan koma.');
        return FALSE;
    }

}
