<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Startup extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    public function Index()
    {
        $data = $this->main_model->check_access('startup');

        $get_sector = $this->db->query("SELECT * FROM tb_master_sector WHERE status_delete = 0 ORDER BY nama ASC ")->result_array();

        $data['sector'] = $get_sector;
        
        $data['title']       = 'Data Startup';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']           = 'admin/startup/startup';
        $this->load->view('admin/index', $data);
    }


    function _sql() 
    {
        $this->db->select('m.id_startup, m.startup_name, m.founders_name, m.sort_description, m.description, m.logo, m.id_sector, m.period ');
        $this->db->from('tb_startup m');
        $this->db->where('m.status_delete', 0);
        $this->db->order_by('m.id_startup DESC');

        return $this->db->get();
    }

    function datatables()
    {   
        $this->main_model->check_access('startup');
        
        $valid_columns = array(
            1 => 'm.id_startup',
            3 => 'm.startup_name',
            4 => 'm.founders_name',
        );

        $this->main_model->datatable($valid_columns);

        $query = $this->_sql();

        $no   = $this->input->get('start');
        $data = array();

        foreach($query->result_array() as $key) {
            $no++;

            $cact = $this->main_model->check_access_action('startup');

            $url_image = $this->main_model->url_image($key['logo'], 'image-content');

            if ($key['founders_name']) {
                $founders_name = character_limiter($key['founders_name'], 30);
            } else {
                $founders_name = '-';
            }

            if ($key['startup_name']) {
                $startup_name = character_limiter($key['startup_name'], 30);
            } else {
                $startup_name = '-';
            }

            if ($key['id_sector'] != 0) {
                $get_sector = $this->db->query("SELECT * FROM tb_master_sector WHERE id_sector = ".$key['id_sector']." ")->row_array();
                if ($get_sector) {
                    $sector = $get_sector['nama'];
                } else {
                    $sector = '-';
                }
            } else {
                $sector = '-';
            }

            $data[] = array(
                '<label class="checkbox-custome"><input type="checkbox" name="check-record" value="'.$key['id_startup'].'" class="check-record '.$cact['access_delete'].'"></label>',
                $no,
                '<img src="'.$url_image.'" class="img-fluid">',
                $startup_name,
                $founders_name,
                $sector,
                $key['period'],
                '<div class="dropdown dropdown-action '.$cact['access_action'].' options ms-auto text-center">
                     <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" id="dropdown-action">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                        <a href="#" class="dropdown-item '.$cact['access_edit'].'" id="edit-data" data="'.$key['id_startup'].'">
                            <ion-icon name="create-sharp"></ion-icon>Edit</a>
                        <a href="#" class="dropdown-item '.$cact['access_delete'].'" id="delete-data" data="'.$key['id_startup'].'">
                            <ion-icon name="trash-sharp"></ion-icon>Delete</a>
                    </div>
                </div>'
            );
        }

        $response['draw']            = intval($this->input->get("draw"));
        $response['recordsTotal']    = $this->_sql()->num_rows();
        $response['recordsFiltered'] = $this->_sql()->num_rows();
        $response['data']            = $data;

        json_response($response);
    }

    function add_data()
    {   
        $this->main_model->check_access('startup');
        $cact = $this->main_model->check_access_action('startup');

        if ($cact['access_add'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 

            if (!empty($_FILES['logo']['name'])) { 
                $data['logo'] = $this->upload_image('logo'); 
            }

            if (!empty($_FILES['thumbnail']['name'])) { 
                $data['thumbnail'] = $this->upload_image('thumbnail'); 
            }

            $data['id_sector']              = sanitize_input($this->input->post('id_sector'));
            $data['period']                 = sanitize_input($this->input->post('period'));
            $data['founders_name']          = sanitize_input($this->input->post('founders_name'));
            $data['founders_url']           = sanitize_input($this->input->post('founders_url'));
            $data['url_label']              = sanitize_input($this->input->post('url_label'));
            $data['url']                    = sanitize_input($this->input->post('url'));
            $data['startup_name']           = sanitize_input($this->input->post('startup_name'));
            $data['slug']                   = strtolower(url_title(sanitize_input($this->input->post('startup_name'))));
            $data['sort_description']       = sanitize_input($this->input->post('sort_description'));
            $data['description']            = sanitize_input_textEditor($this->input->post('description'));
            $data['date_create']            = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');

            $query = $this->db->insert('tb_startup', $data);

            if($query) {
                $response['status']  = 1;
                $response['message'] = 'Data berhasil ditambahkan.';
            } else {
                $response['status']  = 2;
                $response['message'] = 'Gagal menambah data.';
            }
        }

        json_response($response);
    }


    function get_data()
    {   
        $this->main_model->check_access('startup');
        $cact   = $this->main_model->check_access_action('startup');
        $id     = (int) $this->input->get('id');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 

            $this->load->library('form_validation');
            $row = array(
                "id"     => $id,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $query = $this->db->select('*')
                    ->from('tb_startup')
                    ->where('id_startup', $id)
                    ->get()
                    ->row_array();

            $logo       = $this->main_model->url_image($query['logo'], 'image-content');
            $thumbnail  = $this->main_model->url_image($query['thumbnail'], 'image-content');

            $data['id_startup']             = $query['id_startup'];
            $data['id_sector']              = $query['id_sector'];
            $data['period']                 = $query['period'];
            $data['founders_name']          = $query['founders_name'];
            $data['founders_url']           = $query['founders_url'];
            $data['url_label']              = $query['url_label'];
            $data['url']                    = $query['url'];
            $data['startup_name']           = $query['startup_name'];
            $data['logo']                   = $logo;
            $data['thumbnail']              = $thumbnail;
            $data['description']            = $query['description'];
            $data['sort_description']       = $query['sort_description'];
        }

        json_response($data);
    }


    function edit_data()
    {   
        $this->main_model->check_access('startup');
        $cact = $this->main_model->check_access_action('startup');

        if ($cact['access_edit'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 

            $this->load->library('form_validation');
            $row = array(
                "id"     => $this->input->post('id', TRUE),
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $id = $this->input->post('id', TRUE);

            if (!empty($_FILES['logo']['name'])) { 
                $data['logo'] = $this->upload_image('logo'); 
            }

            if (!empty($_FILES['thumbnail']['name'])) { 
                $data['thumbnail'] = $this->upload_image('thumbnail'); 
            }

            $data['id_sector']              = sanitize_input($this->input->post('id_sector'));
            $data['period']                 = sanitize_input($this->input->post('period'));
            $data['founders_name']          = sanitize_input($this->input->post('founders_name'));
            $data['founders_url']           = sanitize_input($this->input->post('founders_url'));
            $data['url_label']              = sanitize_input($this->input->post('url_label'));
            $data['url']                    = sanitize_input($this->input->post('url'));
            $data['startup_name']           = sanitize_input($this->input->post('startup_name'));
            $data['slug']                   = strtolower(url_title(sanitize_input($this->input->post('startup_name'))));
            $data['sort_description']       = sanitize_input($this->input->post('sort_description'));
            $data['description']            = sanitize_input_textEditor($this->input->post('description'));
            $data['date_update']            = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');

            $query = $this->main_model->update_data('tb_startup', $data, 'id_startup', $id);

            if($query) {
                $response['status']  = 3;
                $response['message'] = 'Data berhasil Disimpan.';
            } else {
                $response['status']  = 4;
                $response['message'] = 'Gagal menyimpan data.';
            }
        }

        json_response($response);
    }

    private function upload_image($name)
    {
        $config['upload_path']  = './file_media/image-content/';
        $config['allowed_types']= 'jpg|jpeg|png';
        $config['file_name']    = 'Img-startup-'.$name.'-'.substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5);
        $config['quality']      = '60%';

        if ($this->input->post('param') == 'edit') {
            $id = $this->input->post('id');
            
            $query = $this->db->query("SELECT logo, thumbnail FROM tb_startup WHERE id_startup = ".$id." ")->row_array();
            
            if ($name == 'logo') {
                if($query['logo']) {
                    if (file_exists(FCPATH.'file_media/image-content/'.$query['logo'])) {
                        $config['overwrite'] = true;
                        unlink(FCPATH.'file_media/image-content/'.$query['logo']);
                    }
                }
            }

            if ($name == 'thumbnail') {
                if($query['thumbnail']) {
                    if (file_exists(FCPATH.'file_media/image-content/'.$query['thumbnail'])) {
                        $config['overwrite'] = true;
                        unlink(FCPATH.'file_media/image-content/'.$query['thumbnail']);
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

    function delete_data()
    {   
        $this->main_model->check_access('startup');
        $cact = $this->main_model->check_access_action('startup');

        if ($cact['access_delete'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';

            json_response($response);

        } else { 

            $this->load->library('form_validation');
            $row = array(
                "method"        => $this->input->post('method', TRUE),
                "id"            => $this->input->post('id', TRUE),
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('method', 'method', 'trim|required|alpha_numeric');
            $this->form_validation->set_rules('id', 'id', 'trim|required|callback_valid_angka_koma');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $method = $this->input->post('method', TRUE);

            if($method == 'single')
            {
                $id = $this->input->post('id', TRUE);

                $this->delete_file($id);

                $data_delete['status_delete']  = 1 ;
                
                $this->main_model->update_data('tb_startup', $data_delete, 'id_startup', $id);

                $response['status'] = 1;

                json_response($response);
            }
            else
            {
                $json = $this->input->post('id', TRUE);
                $id = array();

                if (strlen($json) > 0) {
                    $id = json_decode($json);
                }

                if (count($id) > 0) {
                    $id_str = "";
                    $id_str = implode(',', $id);

                    $this->db->query("UPDATE tb_startup SET status_delete = 1 WHERE id_startup in (".$id_str.")");

                    $response['status'] = 2;
                    
                    json_response($response);
                }
            }
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

    private function delete_file($id)
    {   
        $yes = "";
        $query = $this->db->query("SELECT * FROM tb_startup WHERE id_startup = '".$id."'")->result_array();
        foreach ($query as $key) {
            if ($key['logo'])
            {
                $filename = explode(".", $key['logo'])[0];
                $yes = array_map('unlink', glob(FCPATH."file_media/image-content/$filename.*"));
            }

            if ($key['thumbnail'])
            {
                $filename = explode(".", $key['thumbnail'])[0];
                $yes = array_map('unlink', glob(FCPATH."file_media/image-content/$filename.*"));
            }
        }
        return $yes;
    }

    private function delete_file_all($id)
    {
        $yes = "";
        $cek = $this->db->query("SELECT * FROM tb_startup WHERE id_startup in (".$id.") ")->result_array();
        foreach ($cek as $key) {
            if ($key['logo'])
            {
                $filename = explode(".", $key['logo'])[0];
                $yes = array_map('unlink', glob(FCPATH."file_media/image-content/$filename.*"));
            }

            if ($key['thumbnail'])
            {
                $filename = explode(".", $key['thumbnail'])[0];
                $yes = array_map('unlink', glob(FCPATH."file_media/image-content/$filename.*"));
            }
        }
            return $yes;
    }


}

