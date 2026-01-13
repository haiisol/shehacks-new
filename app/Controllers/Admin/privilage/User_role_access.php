<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_role_access extends CI_Controller {

    private $numbering_row = 0;

	function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    function menu($id_enc)
    {   
        $this->main_model->check_access('user_role');

        $id_role    = decrypt_url($id_enc);
        $id         = (int) $id_role; 

        $get_user_role = $this->db->select('*')
                          ->from('tb_admin_user_role')
                          ->where('id_role', $id, TRUE)
                          ->get()
                          ->row_array();

        if ($get_user_role) {

            $data['user_role'] = $get_user_role;

            $this->session->set_userdata('id_role_setting' , $id);

            $data['title']       = 'Setting Menu Access';
            $data['description'] = '';
            $data['keywords']    = '';
            $data['page']        = 'admin/privilage/user_role_access';
            $this->load->view('admin/index', $data);
        } 
        else {
            redirect('admin/privilage/user_role');
        }
    }

    function _sql() 
    {
        $this->db->select('aum.id_menu, aum.nama_menu');
        $this->db->from('tb_admin_user_menu aum');
        $this->db->limit('?');
        
        return $this->db->get();
    }

    function datatables()
    {   
        $this->main_model->check_access('user_role');

        $valid_columns = array(
            0 => 'id_menu',
        );

        $this->main_model->datatable($valid_columns);

        $query = $this->_sql();

        $no   = $this->input->get('start');
        $data = array();

        foreach($query->result_array() as $key) {
            $no++;

            $id_role = $this->session->userdata('id_role_setting');

            $get_user = $this->db->query("SELECT * FROM tb_admin_user_privilage WHERE id_menu = ".$key['id_menu']." AND id_role = ".$id_role." ")->row_array();

            // view
            if ($get_user) {
                if ($get_user['view_data'] == 1) {
                    $param_view_data_checked = 'checked';
                } else {
                    $param_view_data_checked = '';
                }
            } else {
                $param_view_data_checked = '';
            }

            // create
            if ($get_user) {
                if ($get_user['create_data'] == 1) {
                    $param_create_data_checked = 'checked';
                } else {
                    $param_create_data_checked = '';
                }
            } else {
                $param_create_data_checked = '';
            }

            // edit
            if ($get_user) {
                if ($get_user['edit_data'] == 1) {
                    $param_edit_data_checked = 'checked';
                } else {
                    $param_edit_data_checked = '';
                }
            } else {
                $param_edit_data_checked = '';
            }

            // delete
            if ($get_user) {
                if ($get_user['delete_data'] == 1) {
                    $param_delete_data_checked = 'checked';
                } else {
                    $param_delete_data_checked = '';
                }
            } else {
                $param_delete_data_checked = '';
            }

            $data[] = array(
                $no,
                strip_tags($key['nama_menu']),
                '<a href="javascript:void(0)" class="trigg-checkbox" data="'.$key['id_menu'].'" param="view_data"><label class="checkbox-custome"><input type="checkbox" name="view_data" class="check-record" '.$param_view_data_checked.'></label></a>',
                '<a href="javascript:void(0)" class="trigg-checkbox" data="'.$key['id_menu'].'" param="create_data"><label class="checkbox-custome"><input type="checkbox" name="create_data" class="check-record" '.$param_create_data_checked.'></label></a>',
                '<a href="javascript:void(0)" class="trigg-checkbox" data="'.$key['id_menu'].'" param="edit_data"><label class="checkbox-custome"><input type="checkbox" name="edit_data" class="check-record" '.$param_edit_data_checked.'></label></a>',
                '<a href="javascript:void(0)" class="trigg-checkbox" data="'.$key['id_menu'].'" param="delete_data"><label class="checkbox-custome"><input type="checkbox" name="delete_data" class="check-record" '.$param_delete_data_checked.'></label></a>'
            );
        }

        $response = array(
            "draw"            => intval($this->input->get("draw")),
            "recordsTotal"    => $this->_sql()->num_rows(),
            "recordsFiltered" => $this->_sql()->num_rows(),
            "data"            => $data
        );

        json_response($response);
    }


    function menu_edit() 
    {   
        $cact = $this->main_model->check_access_action('user_role');

        if ($cact['access_edit'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {   
            $id_role                = $this->input->post('id_role', TRUE);
            $id_menu                = $this->input->post('id_menu', TRUE);
            $param                  = $this->input->post('param', TRUE);

            $this->load->library('form_validation');
            
            $row = array(
                "id_role"        => $id_role,
                "id_menu"        => $id_menu,
                "param"          => $param
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('id_role', 'id_role', 'trim|required|numeric');
            $this->form_validation->set_rules('id_menu', 'id_menu', 'trim|required|numeric');
            $this->form_validation->set_rules('param', 'param', 'trim|required|callback_valid_huruf_angka_underscore');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            if ($id_role) {

                $get_privilage = $this->db->select('*')
                            ->from('tb_admin_user_privilage')
                            ->where('id_menu', $id_menu)
                            ->where('id_role', $id_role)
                            ->get()
                            ->row_array();

                if ($get_privilage) {

                    if ($param == 'view_data') {
                        if ($get_privilage['view_data'] == 0) {
                            $data['view_data'] = 1;
                        } else {
                            $data['view_data'] = 0;
                        }
                    } 
                    elseif ($param == 'create_data') {
                        if ($get_privilage['create_data'] == 0) {
                            $data['create_data'] = 1;
                        } else {
                            $data['create_data'] = 0;
                        }
                    } 
                    elseif ($param == 'edit_data') {
                        if ($get_privilage['edit_data'] == 0) {
                            $data['edit_data'] = 1;
                        } else {
                            $data['edit_data'] = 0;
                        }
                    } 
                    elseif ($param == 'delete_data') {
                        if ($get_privilage['delete_data'] == 0) {
                            $data['delete_data'] = 1;
                        } else {
                            $data['delete_data'] = 0;
                        }
                    } 

                    if ($data) {
                        $query = $this->main_model->update_data('tb_admin_user_privilage', $data, 'id_privilage', $get_privilage['id_privilage']);
                    }
                } 
                else {

                    if ($param == 'view_data') {
                    $data['view_data'] = 1;
                    } elseif ($param == 'create_data') {
                    $data['create_data'] = 1;
                    } elseif ($param == 'edit_data') {
                    $data['edit_data'] = 1;
                    } elseif ($param == 'delete_data') {
                    $data['delete_data'] = 1;
                    }

                    $data['id_menu'] = $id_menu;
                    $data['id_role'] = $id_role;

                    $query = $this->db->insert('tb_admin_user_privilage', $data);
                }

                if ($query) {
                    $response['status']  = 1;
                    $response['message'] = 'Berhasil menyimpan perubahan.';
                } else {
                    $response['status']  = 0;
                    $response['message'] = 'Gagal menyimpan perubahan.';
                }
            } 
            else {
                $response['status']  = 0;
                $response['message'] = 'Gagal, silahkan coba lagi';
            }
        }
        
        json_response($response);
    }

    public function valid_huruf_angka_underscore($str) {
        if (preg_match('/^[a-zA-Z0-9 _]+$/', $str)) { // Tambahkan _ dalam karakter yang diperbolehkan
            return TRUE;
        } else {
            $this->form_validation->set_message('valid_huruf_angka_spasi', 'Kolom {field} hanya boleh berisi huruf angka dan underscore.');
            return FALSE;
        }
    }

}

