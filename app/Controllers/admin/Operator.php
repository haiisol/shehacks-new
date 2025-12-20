<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Operator extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    public function Index()
    {
        $data = $this->main_model->check_access('operator');
        
        $data['title']       = 'Data Operator';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']           = 'admin/operator';
        $this->load->view('admin/index', $data);
    }

    function _sql() 
    {
        $this->db->select('
            au.id_admin, 
            au.nama_admin, 
            au.email_admin, 
            au.photo, 
            au.id_role, 
            au.status,
            au.terakhir_login_admin
        ');
        $this->db->from('tb_admin_user au');
        $this->db->where('status_delete', 0);
        
        return $this->db->get();
    }

    function datatables()
    {   
        $this->main_model->check_access('operator');

        $valid_columns = array(
            1 => 'id_role',
            2 => 'role_admin',
            3 => 'akses_dashboard',
        );

        $this->main_model->datatable($valid_columns);

        $query = $this->_sql();

        $no   = $this->input->get('start');
        $data = array();

        foreach($query->result_array() as $key) {
            $no++;

            $cact = $this->main_model->check_access_action('operator');

            // photo
            $url_image = $this->main_model->url_image($key['photo'], 'image-admin');
            
            // role
            if ($key['id_role'] == 0) {
                $role = '<span class="badge bg-info">Admin Utama</span>';
            } else {
                $get_role = $this->db->query("SELECT id_role, role_admin FROM tb_admin_user_role WHERE id_role=".$key['id_role']."")->row_array();  

                if ($get_role) {
                    $role = '<span class="badge rounded-pill bg-primary">'.$get_role['role_admin'].'</span>';
                } else {
                    $role = '-';
                }
            }

            // terakhir login
            if ($key['terakhir_login_admin'] == "0000-00-00 00:00:00") {
                $terakhir_login_admin = '-';
            } else {
                $terakhir_login_admin = time_ago_from_3($key['terakhir_login_admin']);
            }

            // status
            if ($key['status'] == 1) {
                $status = '<span class="badge rounded-pill bg-danger">Non Active</span> ';
            } else {
                $status = '<span class="badge rounded-pill bg-success">Active</span>';
            }

            // additional action
            if ($key['id_role'] != 0) {
                $action = 
                    '<a href="javascript:void(0)" class="dropdown-item change-status '.$cact['access_edit'].'" data="'.$key['id_admin'].'" param="'.$key['status'].'">
                        <ion-icon name="eye-sharp"></ion-icon></i><span> Change status</span>
                    </a>
                    <a href="javascript:void(0)" class="dropdown-item '.$cact['access_delete'].'" id="delete-data" data="'.$key['id_admin'].'"><ion-icon name="trash-sharp"></ion-icon> Delete</a>';
            }
            
            $data[] = array(
                '<label class="checkbox-custome"><input type="checkbox" name="check-record" value="'.$key['id_admin'].'" class="check-record"></label>',
                $no,
                '<img src="'.$url_image.'" class="img-fluid">',
                character_limiter($key['nama_admin'], 20),
                character_limiter($key['email_admin'], 20),
                $role,
                $terakhir_login_admin,
                $status,
                '<div class="dropdown dropdown-action '.$cact['access_action'].' options ms-auto text-center">
                    <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" id="dropdown-action">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end" aria-labelledby="dropdown-action">
                        <a href="javascript:void(0)" class="dropdown-item edit-data '.$cact['access_edit'].'" data="'.$key['id_admin'].'"><ion-icon name="pencil-sharp"></ion-icon> Edit</a>
                        '.$action.'
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

    function cek_email() 
    {   
        $email_admin = $this->input->post('value', TRUE);

        $this->load->library('form_validation');
        $row = array(
            "email_admin"     => $email_admin,
        ); 

        $this->form_validation->set_data($row);
        $this->form_validation->set_rules('email_admin', 'email_admin', 'trim|required|valid_email');

        if ( $this->form_validation->run() === false ) {
            $response['status']  = 0;
            $response['message'] = validation_errors();
            json_response($response);
            return;
        }

        $query = $this->db->select('id')
                  ->from('tb_admin_user')
                  ->where('email_admin', $email_admin, TRUE)
                  ->get()
                  ->result();

        if ($query) {
            $response['status']  = 0;
            $response['message'] = '';
        } else {
            $response['status']  = 1;
            $response['message'] = '';
        }

        json_response($response);
    }

    function add_data()
    {   
        $this->main_model->check_access('operator');
        $cact = $this->main_model->check_access_action('operator');

        if ($cact['access_add'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {   
            $nama_admin             = sanitize_input($this->input->post('nama_admin'));
            $email_admin            = sanitize_input($this->input->post('email_admin'));
            $phone_admin            = (int) $this->input->post('phone_admin');
            $id_role                = (int) $this->input->post('id_role', TRUE);
            $password_admin         = $this->input->post('password_admin', TRUE);

            $this->load->library('form_validation');
            
            $row = array(
                "nama_admin"        => $nama_admin,
                "email_admin"       => $email_admin,
                "phone_admin"       => $phone_admin,
                "id_role"           => $id_role,
                "password_admin"    => $password_admin,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('nama_admin', 'nama_admin', 'trim|required|callback_valid_huruf_angka_spasi');
            $this->form_validation->set_rules('email_admin', 'email_admin', 'trim|required|valid_email');
            $this->form_validation->set_rules('phone_admin', 'phone_admin', 'trim|required|numeric');
            $this->form_validation->set_rules('id_role', 'id_role', 'trim|required|numeric');
            $this->form_validation->set_rules('password_admin', 'password_admin', 'trim|required');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }
            
            if (!empty($_FILES['photo']['name'])) {
                $data['photo'] = $this->upload_image('photo'); 
            }

            $data['nama_admin']     = $nama_admin;
            $data['email_admin']    = $email_admin;
            $data['phone_admin']    = $phone_admin;
            $data['id_role']        = $id_role;
            $data['role']           = 'Admin';
            $data['password_admin'] = md5($password_admin);
            $data['tanggal_admin']  = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');

            $query = $this->db->insert('tb_admin_user', $data);

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
        $this->main_model->check_access('operator');
        $cact = $this->main_model->check_access_action('operator');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {   

            $id     = $this->input->post('id', TRUE);

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
                    ->from('tb_admin_user')
                    ->where('id_admin', $id, TRUE)
                    ->get()
                    ->row_array();

            $response['id_admin']    = $query['id_admin'];
            $response['nama_admin']  = $query['nama_admin'];
            $response['email_admin'] = $query['email_admin'];
            $response['phone_admin'] = $query['phone_admin'];
            $response['id_role']     = $query['id_role'];
            $response['photo']       = $this->main_model->url_image_admin($query['photo']);
        }

        json_response($response);
    }

    function edit_data()
    {   
        $this->main_model->check_access('operator');
        $cact = $this->main_model->check_access_action('operator');

        if ($cact['access_edit'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {   
            $id                     = (int) $this->input->post('id');
            $nama_admin             = sanitize_input($this->input->post('nama_admin'));
            $email_admin            = sanitize_input($this->input->post('email_admin'));
            $phone_admin            = (int) $this->input->post('phone_admin');
            $id_role                = (int) $this->input->post('id_role', TRUE);
            $password_admin         = $this->input->post('password_admin', TRUE);

            $this->load->library('form_validation');
            
            $row = array(
                "id"                => $id,
                "nama_admin"        => $nama_admin,
                "email_admin"       => $email_admin,
                "phone_admin"       => $phone_admin,
                "id_role"           => $id_role,
                "password_admin"    => $password_admin,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');
            $this->form_validation->set_rules('nama_admin', 'nama_admin', 'trim|required|callback_valid_huruf_angka_spasi');
            $this->form_validation->set_rules('email_admin', 'email_admin', 'trim|required|valid_email');
            $this->form_validation->set_rules('phone_admin', 'phone_admin', 'trim|required|numeric');
            $this->form_validation->set_rules('id_role', 'id_role', 'trim|required|numeric');
            $this->form_validation->set_rules('password_admin', 'password_admin', 'trim');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            if (!empty($_FILES['photo']['name'])) {
                $data['photo'] = $this->upload_image('photo'); 
            }

            $data['nama_admin']     = $nama_admin;
            $data['email_admin']    = $email_admin;
            $data['phone_admin']    = $phone_admin;
            $data['id_role']        = $id_role;

            if ($password_admin) {
                $data['password_admin'] = md5($password_admin);
            }

            $query = $this->main_model->update_data('tb_admin_user', $data, 'id_admin', $id);

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
        $config['upload_path']  = './file_media/image-admin/';
        $config['allowed_types']= 'jpg|jpeg|png';
        $config['file_name']    = url_title(strtolower($this->input->post('nama_admin'))).'_'.substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
        $config['quality']      = '60%';

        if ($this->input->post('param') == 'edit') {
            $id = $this->input->post('id');
            $query = $this->db->query("SELECT photo FROM tb_admin_user WHERE id_admin=".$id." ")->row_array();

            if($query['photo']) {
                if (file_exists(FCPATH.'file_media/image-admin/'.$query['photo'])) {
                    $config['overwrite'] = true;
                    unlink(FCPATH.'file_media/image-admin/'.$query['photo']);
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
        $this->main_model->check_access('akun_email');
        $cact = $this->main_model->check_access_action('akun_email');

        if ($cact['access_delete'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {  
            $method = $this->input->post('method', TRUE);

            $this->load->library('form_validation');
            $row = array(
                "method"        => $method,
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

            if($method == 'single')
            {
                $id = $this->input->post('id');

                // $query = $this->db->query("UPDATE tb_admin_user SET status_delete = 1 WHERE id_admin = '".$id."' ");
                $this->delete_single_image($id);
                $query = $this->db->query("DELETE FROM tb_admin_user WHERE id_admin = ".$id." ");

                if($query) {
                    $response['status']  = 1;
                    $response['message'] = 'Data berhasil dihapus.';
                } else {
                    $response['status']  = 0;
                    $response['message'] = 'Gagal menghapus data.';
                }
            }
            else
            {
                $json = $this->input->post('id');
                $id = array();

                if (strlen($json) > 0) {
                    $id = json_decode($json);
                }

                if (count($id) > 0) {
                    $id_str = "";
                    $id_str = implode(',', $id);

                    // $query = $this->db->query("UPDATE tb_admin_user SET status_delete = 1 WHERE id_admin in (".$id_str.")");
                    $this->delete_multiple_image($id_str);
                    $query = $this->db->query("DELETE FROM tb_admin_user WHERE id_admin in (".$id_str.") ");

                    if($query) {
                        $response['status']  = 2;
                        $response['message'] = 'Data berhasil dihapus.';
                    } else {
                        $response['status']  = 0;
                        $response['message'] = 'Gagal menghapus data.';
                    }
                }
            }
        }

        json_response($response);
    }

    private function delete_single_image($id)
    {
        $query = $this->db->query("SELECT photo FROM tb_admin_user WHERE id_admin = ".$id." ")->row_array();

        if ($query['photo']) {
            if (file_exists(FCPATH.'file_media/image-admin/'.$query['photo'])) {
                $filename = explode(".", $query['photo'])[0];
                array_map('unlink', glob(FCPATH."file_media/image-admin/$filename.*"));
            }
        }
    }

    private function delete_multiple_image($id)
    {   
        $return = '';
        $query = $this->db->query("SELECT photo FROM tb_admin_user WHERE id_admin in (".$id.") ")->result_array();

        foreach ($query as $key) {
            if ($key['photo']) {
                if (file_exists(FCPATH.'file_media/image-admin/'.$key['photo'])) {
                    $filename = explode(".", $key['photo'])[0];
                    array_map('unlink', glob(FCPATH."file_media/image-admin/$filename.*"));
                }
            }
        }
        return $return;
    }


    function change_status()
    {   
        $this->main_model->check_access('akun_email');
        $cact = $this->main_model->check_access_action('akun_email');

        if ($cact['access_edit'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {   

            $param          = $this->input->post('param', TRUE);
            $id             = $this->input->post('id', TRUE);

            $this->load->library('form_validation');
            $row = array(
                "param"     => $param,
                "id"        => $id,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('param', 'param', 'trim|required|callback_valid_huruf_angka_spasi');
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            if ($param == 1) {
                $data['status'] = 0;
            } 
            else if ($param == 0) {
                $data['status'] = 1;
            }

            $query = $this->main_model->update_data('tb_admin_user', $data, 'id_admin', $id);

            if($query) {

                $response['status'] = 1;

                if ($param == 1) {
                    $response['message'] = 'Berhasil me-nonaktifkan user.';
                } 
                else if ($param == 0) {
                    $response['message'] = 'Berhasil meng-aktifkan user.';
                }
            } 
            else {
                $response['status']  = 0;
                $response['message'] = 'Gagal mengubah status.';
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

