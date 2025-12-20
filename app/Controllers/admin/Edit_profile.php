<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_profile extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
    }

    public function Index() 
    {
        $id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
        $row_user = $this->db->query("SELECT * FROM tb_admin_user WHERE id_admin = '".$id_admin."' ")->row_array();
     
        $data['data']        = $row_user;
        $data['title']       = 'Edit profile';
        $data['description'] = '';
        $data['keywords']    = '';

        $data['page']           = 'admin/profile';
        $this->load->view('admin/index', $data);
    
    }

    function get_data()
    {
        $id = decrypt_url($this->session->userdata('key_auth_admin'));
        $query = $this->db->query("SELECT * FROM tb_admin_user WHERE id_admin='".$id."'")->row_array();
        
        $link_photo =  $this->main_model->url_image_admin($query['photo']);      

        $response['data']       = $query;
        $response['link_photo'] = $link_photo;

        json_response($response);
    }

    function edit_data()
    {   
        $id = $this->input->post('id_admin');

        if ($_FILES["photo"]["name"]) { 
            $data['photo'] = $this->upload_photo(); 
        }

        $data['nama_admin']     = $this->input->post('nama');
        $data['phone_admin']    = $this->input->post('phone');

        $status_logout = 0;
        if ($this->input->post('password')) {
            $status_logout = 1;
            $data['password_admin'] = md5($this->input->post('password'));
        }

        $query = $this->main_model->update_data('tb_admin_user', $data, 'id_admin', $id);

        if($query) {
            if ($status_logout == 0) {
                $response = 1;
            } else {
                $response = 2;
            }
            
        } else {
            $response = 0;
        }
        json_response($response);
    }

    function upload_photo()
    {
        $config['upload_path']  = './file_media/image_admin/';
        $config['allowed_types']= 'jpg|jpeg|png';
        $config['file_name']    = url_title(strtolower($this->input->post('nama'))).'_'.substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
        $config['quality']      = '60%';

        $id = $this->input->post('id_admin');
        $query = $this->db->query("SELECT photo FROM tb_admin_user WHERE id_admin='".$id."'")->row_array();

        if($query) {
            if($query['photo']) {
                if (file_exists(FCPATH.'./file_media/image_admin/'.$query['photo'])){
                    $config['overwrite'] = true;
                    unlink(FCPATH.'file_media/image_admin/'.$query['photo']);
                }
            }
        }
        
        $this->upload->initialize($config);

        if ($this->upload->do_upload('photo')) 
        {
            return $this->upload->data('file_name');
        }
        return "";
    }

}