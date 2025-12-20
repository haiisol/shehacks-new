<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Website extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
    }
    
    public function index()
    {
        $data = $this->main_model->check_access('website');
        $cact = $this->main_model->check_access_action('website');

        $data['access_edit'] = $cact['access_edit'];
        
        $data['title']       = 'Setting Website';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']        = 'admin/setting/website';
        $this->load->view('admin/index', $data);
    }

    function get_data()
    {   
        $this->main_model->check_access('website');

        $query = $this->db->query("SELECT * FROM tb_admin_web WHERE id=1")->row_array();
        
        $link_logo          = $this->main_model->url_image($query['logo'], 'image-logo');
        $link_logo_sponsor  = $this->main_model->url_image($query['logo_sponsor'], 'image-logo');
        $link_favicon       = $this->main_model->url_image($query['favicon'], 'image-logo');

        $response['query']              = $query;
        $response['link_logo']          = $link_logo;
        $response['link_logo_sponsor']  = $link_logo_sponsor;
        $response['link_favicon']       = $link_favicon;

        json_response($response);
    }

    function edit_data()
    {   
        $this->main_model->check_access('website');
        $cact = $this->main_model->check_access_action('website');

        if ($cact['access_edit'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses edit.';
        } else {
    
            $id             = $this->input->post('id', TRUE);

            $this->load->library('form_validation');
            $row = array(
                "id"        => $id,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            if (!empty($_FILES['logo']['name'])) {
                $data['logo'] = $this->upload_image('logo'); 
            }

            if (!empty($_FILES['logo_sponsor']['name'])) {
                $data['logo_sponsor'] = $this->upload_image('logo_sponsor'); 
            }

            if (!empty($_FILES['favicon']['name'])) {
                $data['favicon'] = $this->upload_image('favicon'); 
            }
            
            $data['name']               = $this->input->post('name', TRUE);
            $data['email']              = $this->input->post('email', TRUE);
            $data['phone']              = $this->input->post('phone', TRUE);
            $data['contact_name']       = $this->input->post('contact_name', TRUE);
            $data['contact_person']     = $this->input->post('contact_person', TRUE);
            $data['address']            = $this->input->post('address', TRUE);
            $data['maps_address']       = $this->input->post('maps_address', TRUE);
            $data['whatsapp']           = $this->input->post('whatsapp', TRUE);
            $data['facebook']           = $this->input->post('facebook', TRUE);
            $data['twitter']            = $this->input->post('twitter', TRUE);
            $data['instagram']          = $this->input->post('instagram', TRUE);
            // $data['linkedin']           = $this->input->post('linkedin', TRUE);
            // $data['tiktok']             = $this->input->post('tiktok', TRUE);
            $data['youtube']            = $this->input->post('youtube', TRUE);
            $data['short_description']  = $this->input->post('short_description', TRUE);
            $data['meta_description']   = $this->input->post('meta_description', TRUE);
            $data['meta_keywords']      = $this->input->post('meta_keywords', TRUE);
            $data['register_button']    = $this->input->post('register_button', TRUE);
            $data['voting_running']     = $this->input->post('voting_running', TRUE);
            // $data['auth_key_firebase']  = $this->input->post('auth_key_firebase');
            // $data['setClientId']        = $this->input->post('setClientId');
            // $data['setClientSecret']    = $this->input->post('setClientSecret');

            $query = $this->main_model->update_data('tb_admin_web', $data, 'id', $id);

            if ($query) {
                $response['status']  = 1;
                $response['message'] = 'Data berhasil disimpan.';
            } else {
                $response['status']  = 0;
                $response['message'] = 'Gagal menyimpan data.';
            }
        }
        json_response($response);
    }

    private function upload_image($name)
    {
        $config['upload_path']  = './file_media/image-logo/';
        $config['allowed_types']= 'jpg|jpeg|png|webp';
        $config['file_name']    = $name.'_'.substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
        $config['quality']      = '60%';

        if ($this->input->post('param') == 'edit') {
            $id = $this->input->post('id');
            $query = $this->db->query("SELECT logo, logo_sponsor, favicon FROM tb_admin_web WHERE id=".$id." ")->row_array();

            if ($name == 'logo') {
                if($query['logo']) {
                    if (file_exists(FCPATH.'file_media/image-logo/'.$query['logo'])) {
                        $config['overwrite'] = true;
                        unlink(FCPATH.'file_media/image-logo/'.$query['logo']);
                    }
                }
            }

            if ($name == 'logo_sponsor') {
                if($query['logo_sponsor']) {
                    if (file_exists(FCPATH.'file_media/image-logo/'.$query['logo_sponsor'])) {
                        $config['overwrite'] = true;
                        unlink(FCPATH.'file_media/image-logo/'.$query['logo_sponsor']);
                    }
                }
            }

            if ($name == 'favicon') {
                if($query['favicon']) {
                    if (file_exists(FCPATH.'file_media/image-logo/'.$query['favicon'])) {
                        $config['overwrite'] = true;
                        unlink(FCPATH.'file_media/image-logo/'.$query['favicon']);
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
