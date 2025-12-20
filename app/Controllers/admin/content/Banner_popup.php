<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Banner_popup extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
    }
    
    public function index()
    {
        $data = $this->main_model->check_access('content_home');
        $cact = $this->main_model->check_access_action('content_home');

        $data['access_edit'] = $cact['access_edit'];
        
        $data['title']       = 'Banner Popup';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']        = 'admin/content/banner_popup';
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

            $query = $this->db->query("SELECT c.* FROM tb_content c WHERE c.section = 'banner_popup' ")->row_array();

            $response['id']         = $query['id'];
            $response['button_url'] = $query['button_url'];
            $response['status']     = $query['status'];
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

            $id = $this->input->post('id');

            if (!empty($_FILES['image']['name'])) {
                $data['image'] = $this->upload_image('image');
            }
            
            $data['button_url']  = sanitize_input_textEditor($this->input->post('button_url'));
            $data['status']      = sanitize_input_textEditor($this->input->post('status'));
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

    private function upload_image($name)
    {
        $config['upload_path']  = './file_media/image-content/';
        $config['allowed_types']= 'jpg|jpeg|png|webp';
        $config['file_name']    = 'banner-popup-'.substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
        $config['quality']      = '60%';

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
        
        $this->upload->initialize($config);

        if ($this->upload->do_upload($name)) {
            return $this->upload->data('file_name');
        }
        return '';
    }

}
