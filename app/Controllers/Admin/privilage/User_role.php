<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_role extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }


    public function Index()
    {
        $data = $this->main_model->check_access('user_role');
        
        $data['title']       = 'Data User Role';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']           = 'admin/privilage/user_role';
        $this->load->view('admin/index', $data);
    }

    function _sql() 
    {
        $this->db->select('ur.id_role, ur.role_admin, ur.akses_dashboard');
        $this->db->from('tb_admin_user_role ur');
        
        return $this->db->get();
    }

    function datatables()
    {   
        $this->main_model->check_access('user_role');

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

            $cact = $this->main_model->check_access_action('user_role');

            if ($key['akses_dashboard']) {
                $akses_dashboard = $key['akses_dashboard'];
            } else {
                $akses_dashboard = '-';
            }

            $url_akses = base_url().'admin/privilage/user_role_access/menu/'.encrypt_url($key['id_role']);                    

            $data[] = array(
                '<label class="checkbox-custome"><input type="checkbox" name="check-record" value="'.$key['id_role'].'" class="check-record"></label>',
                $no,
                character_limiter($key['role_admin'], 20),
                $akses_dashboard,
                '<div class="dropdown dropdown-action '.$cact['access_action'].' options ms-auto text-center">
                    <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" id="dropdown-action">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end" aria-labelledby="dropdown-action">
                        <a href="javascript:void(0)" class="dropdown-item edit-data '.$cact['access_edit'].'" data="'.$key['id_role'].'"><ion-icon name="pencil-sharp"></ion-icon> Edit</a>
                        <a href="'.$url_akses.'" class="dropdown-item '.$cact['access_edit'].'" target="_blank" "><ion-icon name="eye-sharp"></ion-icon> Hak Akses</a>
                        <a href="javascript:void(0)" class="dropdown-item '.$cact['access_delete'].'" id="delete-data" data="'.$key['id_role'].'"><ion-icon name="trash-sharp"></ion-icon> Delete</a>
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
        $this->main_model->check_access('user_role');
        $cact = $this->main_model->check_access_action('user_role');

        if ($cact['access_add'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {   
            $role_admin             = sanitize_input($this->input->post('role_admin'));
            $akses_dashboard        = $this->input->post('akses_dashboard', TRUE);

            $this->load->library('form_validation');
            
            $row = array(
                "role_admin"        => $role_admin,
                "akses_dashboard"   => $akses_dashboard,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('role_admin', 'role_admin', 'trim|required');
            $this->form_validation->set_rules('akses_dashboard', 'akses_dashboard', 'trim|required|callback_valid_huruf_angka_spasi');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $data['role_admin']      = $role_admin;
            $data['akses_dashboard'] = $akses_dashboard;

            $query = $this->db->insert('tb_admin_user_role', $data);

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
        $this->main_model->check_access('user_role');
        $cact = $this->main_model->check_access_action('user_role');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';

            json_response($response);

        } else {     
            $this->main_model->check_access('user_role');

            $id = $this->input->post('id', TRUE);

            $this->load->library('form_validation');
            $row = array(
                "id" => $id,
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
                    ->from('tb_admin_user_role')
                    ->where('id_role', $id, TRUE)
                    ->get()
                    ->row_array();

            json_response($query);
        }
    }

    function edit_data()
    {   
        $cact = $this->main_model->check_access_action('user_role');

        if ($cact['access_edit'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {   
            $id                     = $this->input->post('id_edit', TRUE);
            $role_admin             = sanitize_input($this->input->post('role_admin'));
            $akses_dashboard        = $this->input->post('akses_dashboard', TRUE);

            $this->load->library('form_validation');
            
            $row = array(
                "id"                => $id,
                "role_admin"        => $role_admin,
                "akses_dashboard"   => $akses_dashboard
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');
            $this->form_validation->set_rules('role_admin', 'role_admin', 'trim|required');
            $this->form_validation->set_rules('akses_dashboard', 'akses_dashboard', 'trim|required|callback_valid_huruf_angka_spasi');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }
        
            $data['role_admin']      = $role_admin;
            $data['akses_dashboard'] = $akses_dashboard;

            $query = $this->main_model->update_data('tb_admin_user_role', $data, 'id_role', $id);

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


    function delete_data()
    {
        $this->main_model->check_access('user_role');
        $cact = $this->main_model->check_access_action('user_role');

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

                $query = $this->db->query("DELETE FROM tb_admin_user_role WHERE id_role = ".$id." ");

                if($query) {
                    $this->db->query("DELETE FROM tb_admin_user_privilage WHERE id_role = ".$id." ");
                }

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
            
                    $query = $this->db->query("DELETE FROM tb_admin_user_role WHERE id_role in (".$id_str.") ");

                    if($query) {
                        $this->db->query("DELETE FROM tb_admin_user_privilage WHERE id_role in (".$id_str.")' ");
                    }

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