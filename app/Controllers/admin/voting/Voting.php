<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Voting extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    public function Index()
    {
        $data = $this->main_model->check_access('voting');
        
        $data['title']       = 'Data Voting';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']           = 'admin/voting/voting';
        $this->load->view('admin/index', $data);
    }


    function _sql() 
    {
        $fil_kategori = trim($this->input->get('fil_kategori', TRUE) ?? '');

        $this->db->select('m.id_voting, m.nama_founders, m.nama_usaha, m.bidang_usaha, m.kategori, m.logo , m.video_upload ');
        $this->db->from('tb_voting m');
        $this->db->where('m.status_delete', 0);

        if ($fil_kategori) {
            $this->db->where('m.kategori', $fil_kategori);
        }

        $this->db->order_by('m.id_voting DESC');

        return $this->db->get();
    }

    function datatables()
    {   
        $this->main_model->check_access('voting');

        $this->load->library('form_validation');
        $row = array(
            "fil_kategori"     => $this->input->get('fil_kategori', TRUE),
        ); 

        $this->form_validation->set_data($row);
        $this->form_validation->set_rules('fil_kategori', 'fil_kategori', 'trim|alpha_numeric');

        if ( $this->form_validation->run() === false ) {
            $response['status']  = 0;
            $response['message'] = validation_errors();
            json_response($response);
            return;
        }
        
        $valid_columns = array(
            1 => 'm.id_voting',
            4 => 'm.nama_founders',
            5 => 'm.nama_usaha',
        );

        $this->main_model->datatable($valid_columns);

        $query = $this->_sql();

        $no   = $this->input->get('start');
        $data = array();

        foreach($query->result_array() as $key) {
            $no++;

            $cact = $this->main_model->check_access_action('voting');

            $url_image = $this->main_model->url_image($key['logo'], 'image-logo');

            if ($key['nama_founders']) {
                $nama_founders = character_limiter($key['nama_founders'], 30);
            } else {
                $nama_founders = '-';
            }

            if ($key['nama_usaha']) {
                $nama_usaha = character_limiter($key['nama_usaha'], 30);
            } else {
                $nama_usaha = '-';
            }

            if ($key['kategori'] == 'Ideasi') {
                $kategori = '<span class="badge bg-danger">'.$key['kategori'].'</span> ';
            } else {
                $kategori = '<span class="badge bg-success">'.$key['kategori'].'</span>';
            }

            if ($key['video_upload'] == '') {
                $file = "-";
            } else {
                $file = "<a href='https://www.youtube.com/embed/".$key['video_upload']."?autoplay=1' target='_blank'>Lihat Link</a>";
            }

            $data[] = array(
                '<label class="checkbox-custome"><input type="checkbox" name="check-record" value="'.$key['id_voting'].'" class="check-record"></label>',
                $no,
                '<img src="'.$url_image.'" class="img-fluid">',
                $kategori,
                $nama_founders,
                $nama_usaha,
                $file,
                '<div class="dropdown dropdown-action '.$cact['access_action'].' options ms-auto text-center">
                     <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" id="dropdown-action">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                        <a href="#" class="dropdown-item '.$cact['access_edit'].'" id="edit-data" data="'.$key['id_voting'].'">
                            <ion-icon name="create-sharp"></ion-icon>Edit</a>
                        <a href="#" class="dropdown-item '.$cact['access_delete'].'" id="delete-data" data="'.$key['id_voting'].'">
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
        $this->main_model->check_access('voting');
        $cact = $this->main_model->check_access_action('voting');

        if ($cact['access_add'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 
            
            if (!empty($_FILES['logo']['name'])) { 
                $data['logo'] = $this->upload_image(); 
            }

            $data['kategori']               = sanitize_input($this->input->post('kategori'));
            $data['nama_founders']          = sanitize_input($this->input->post('nama_founders'));
            $data['nama_usaha']             = sanitize_input($this->input->post('nama_usaha'));
            $data['bidang_usaha']           = sanitize_input($this->input->post('bidang_usaha'));
            $data['description']            = sanitize_input_textEditor($this->input->post('description'));
            $data['domisili']               = sanitize_input($this->input->post('domisili'));
            $data['video_upload']           = sanitize_input($this->input->post('video_upload'));
            $data['date_create']            = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');

            $query = $this->db->insert('tb_voting', $data);

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
        $this->main_model->check_access('voting');
        $cact = $this->main_model->check_access_action('voting');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 

            $this->load->library('form_validation');
            $row = array(
                "id"     => (int) $this->input->get('id'),
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $id = (int) $this->input->get('id');

            $query = $this->db->select('*')
                    ->from('tb_voting')
                    ->where('id_voting', $id)
                    ->get()
                    ->row_array();

            $logo = $this->main_model->url_image($query['logo'], 'image-logo');

            $data['id_voting']              = $query['id_voting'];
            $data['kategori']               = $query['kategori'];
            $data['nama_founders']          = $query['nama_founders'];
            $data['nama_usaha']             = $query['nama_usaha'];
            $data['bidang_usaha']           = $query['bidang_usaha'];
            $data['video_upload']           = $query['video_upload'];
            $data['logo']                   = $logo;
            $data['description']            = $query['description'];
            $data['domisili']               = $query['domisili'];
        }

        json_response($data);
    }


    function edit_data()
    {   
        $this->main_model->check_access('voting');
        $cact = $this->main_model->check_access_action('voting');
        $id = (int) $this->input->post('id');

        if ($cact['access_edit'] == 'd-none') {
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

            if (!empty($_FILES['logo']['name'])) { 
                $data['logo'] = $this->upload_image(); 
            }

            $data['kategori']               = sanitize_input($this->input->post('kategori'));
            $data['nama_founders']          = sanitize_input($this->input->post('nama_founders'));
            $data['nama_usaha']             = sanitize_input($this->input->post('nama_usaha'));
            $data['bidang_usaha']           = sanitize_input($this->input->post('bidang_usaha'));
            $data['description']            = sanitize_input_textEditor($this->input->post('description'));
            $data['domisili']               = sanitize_input($this->input->post('domisili'));
            $data['video_upload']           = sanitize_input($this->input->post('video_upload'));

            $query = $this->main_model->update_data('tb_voting', $data, 'id_voting', $id);

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

    function upload_image()
    {
        $config['upload_path']  = './file_media/image-logo/';
        $config['allowed_types']= 'jpg|jpeg|png';
        $config['file_name']    = 'logo-voting-'.substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5);

        if ($this->input->post('param') == 'edit') {
            $id = $this->input->post('id');
            
            $query = $this->db->query("SELECT logo FROM tb_voting WHERE id_voting = ".$id." ")->row_array();
            
            if($query['logo']) {
                if (file_exists(FCPATH.'/file_media/image-logo/'.$query['logo'])) {
                    $config['overwrite'] = true;
                    unlink(FCPATH.'/file_media/image-logo/'.$query['logo']);
                }
            }
        }
        
        $this->upload->initialize($config);

        if ($this->upload->do_upload('logo')) {
            return $this->upload->data('file_name');
        }
        return '';
    }

    function delete_data()
    {   
        $this->main_model->check_access('voting');
        $cact = $this->main_model->check_access_action('voting');

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
                
                $this->main_model->update_data('tb_voting', $data_delete, 'id_voting', $id);

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

                    $query = $this->db->query("UPDATE tb_voting SET status_delete = 1 WHERE id_voting in (".$id_str.")");

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
        $query = $this->db->query("SELECT * FROM tb_voting WHERE id_voting = '".$id."'")->result_array();
        foreach ($query as $key) {
            if ($key['logo'])
            {
                $filename = explode(".", $key['logo'])[0];
                $yes = array_map('unlink', glob(FCPATH."file_media/image-logo/$filename.*"));
            }
        }
        return $yes;
    }

    private function delete_file_all($id)
    {
        $yes = "";
        $cek = $this->db->query("SELECT * FROM tb_voting WHERE id_voting in (".$id.") ")->result_array();
        foreach ($cek as $key) {
            if ($key['logo'])
            {
                $filename = explode(".", $key['logo'])[0];
                $yes = array_map('unlink', glob(FCPATH."file_media/image-logo/$filename.*"));
            }
        }
            return $yes;
    }


}

