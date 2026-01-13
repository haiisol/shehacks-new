<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rangkaian_event extends CI_Controller {

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
        $data['page']           = 'admin/content/home/rangkaian_event';
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

            $query = $this->db->query("SELECT * FROM tb_content WHERE section = 'home_rangkaian_event' ")->row_array();

            $response['id']      = $query['id'];
            $response['heading'] = $query['heading'];
            $response['subheading'] = $query['subheading'];
            // $response['content'] = $query['content'];
            $response['image']   = $this->main_model->url_image($query['image'], 'image-content');
            $response['image_2']   = $this->main_model->url_image($query['image_2'], 'image-content');
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

            if (!empty($_FILES['image_2']['name'])) {
                $data['image_2'] = $this->upload_image('image_2');
            }

            $data['heading']            = $this->input->post('heading');
            $data['subheading']        = $this->input->post('subheading');
            // $data['content']            = $this->input->post('content');
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
        $config['file_name']    = 'Shehacks-event-'.substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
        $config['quality']      = '60%';

        if ($this->input->post('param') == 'edit') {
            $id = $this->input->post('id');
            $query = $this->db->query("SELECT c.image, c.image_2 FROM tb_content c WHERE id=".$id." ")->row_array();

            if ($name == 'image') {
                if($query['image']) {
                    if (file_exists(FCPATH.'file_media/image-content/'.$query['image'])) {
                        $config['overwrite'] = true;
                        unlink(FCPATH.'file_media/image-content/'.$query['image']);
                    }
                }
            }

            if ($name == 'image_2') {
                if($query['image_2']) {
                    if (file_exists(FCPATH.'file_media/image-content/'.$query['image_2'])) {
                        $config['overwrite'] = true;
                        unlink(FCPATH.'file_media/image-content/'.$query['image_2']);
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

}
