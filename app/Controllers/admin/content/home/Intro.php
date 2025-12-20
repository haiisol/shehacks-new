<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Intro extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }
    
    public function index()
    {
        $data = $this->main_model->check_access('content_home');
        $cact = $this->main_model->check_access_action('content_home');

        $data['access_edit'] = $cact['access_edit'];

        $data['title']       = 'Data Rangkaian Event';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']           = 'admin/content/home/intro';
        $this->load->view('admin/index', $data);
    }

    function get_data()
    {   
        $this->main_model->check_access('content_home');
        $cact = $this->main_model->check_access_action('content_home');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {

            $query = $this->db->query("SELECT * FROM tb_content WHERE section = 'home_intro' ")->row_array();

            $response['id']         = $query['id'];
            $response['heading']    = $query['heading'];
            $response['subheading'] = $query['subheading'];
            $response['button_text'] = $query['button_text'];
            $response['button_url'] = $query['button_url'];
            $response['content']    = $query['content'];
            $response['image']      = $this->main_model->url_image($query['image'], 'image-content');
        }

        json_response($response);
    }

    function edit_data()
    {   
        $this->main_model->check_access('content_home');
        $cact = $this->main_model->check_access_action('content_home');

        if ($cact['access_edit'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {  

            $id             = (int) $this->input->post('id');

            $this->load->library('form_validation');
            $row = array(
                "id"            => $id
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            if (!empty($_FILES['image']['name'])) {
                $data['image'] = $this->upload_image('image');
            }

            $data['heading']            = sanitize_input($this->input->post('heading'));
            $data['subheading']         = sanitize_input($this->input->post('subheading'));
            $data['button_text']        = sanitize_input($this->input->post('button_text'));
            $data['button_url']         = sanitize_input_textEditor($this->input->post('button_url'));
            $data['content']            = sanitize_input_textEditor($this->input->post('content'));
            $data['date_update']        = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');

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

    private function upload_image($name)
    {
        $config['upload_path']  = './file_media/image-content/';
        $config['allowed_types']= 'jpg|jpeg|png';
        $config['file_name']    = 'Intro-home-'.substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
        $config['quality']      = '60%';

        if ($this->input->post('param') == 'edit') {
            $id = $this->input->post('id');
            $query = $this->db->query("SELECT c.image FROM tb_content c WHERE id=".$id." ")->row_array();

            if ($name == 'image') {
                if($query['image']) {
                    if (file_exists(FCPATH.'file_media/image-content/'.$query['image'])) {
                        $config['overwrite'] = true;
                        unlink(FCPATH.'file_media/image-content/'.$query['image']);
                    }
                }
            }
        }
        
        $this->upload->initialize($config);

        if ($this->upload->do_upload($name)) {
            return $this->upload->data('file_name');
        }
        return '';
    }

    public function valid_huruf_angka_spasi($str) {
        if (preg_match('/^[a-zA-Z0-9 :;#?]+$/', $str)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('valid_huruf_angka_spasi', 'Kolom {field} hanya boleh berisi huruf, angka, spasi dan (:;#).');
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
