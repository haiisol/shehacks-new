<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_menu extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    public function Index()
    {
        $data = $this->main_model->check_access('user_role');
        
        $data['title']       = 'Data User Menu';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']           = 'admin/privilage/user_menu';
        $this->load->view('admin/index', $data);
    }

    function _sql() 
    {
        $this->db->select('*');
        $this->db->from('tb_admin_user_menu um');
        
        return $this->db->get();
    }

    function datatables()
    {
        $valid_columns = array(
            1 => 'id_menu',
            2 => 'nama_menu',
            3 => 'kode_menu',
        );

        $this->main_model->datatable($valid_columns);

        $query = $this->_sql();

        $no   = $this->input->get('start');
        $data = array();

        foreach($query->result_array() as $key) {
            $no++;

            $cact = $this->main_model->check_access_action('user_role');
            
            $data[] = array(
                '<label class="checkbox-custome"><input type="checkbox" name="check-record" value="'.$key['id_menu'].'" class="check-record"></label>',
                $no,
                character_limiter($key['nama_menu'], 20),
                character_limiter($key['kode_menu'], 20),
                '<div class="dropdown dropdown-action '.$cact['access_action'].' options ms-auto text-center">
                    <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" id="dropdown-action">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end" aria-labelledby="dropdown-action">
                        <a href="javascript:void(0)" class="dropdown-item edit-data '.$cact['access_edit'].'" data="'.$key['id_menu'].'"><ion-icon name="pencil-sharp"></ion-icon> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item '.$cact['access_delete'].'" id="delete-data" data="'.$key['id_menu'].'"><ion-icon name="trash-sharp"></ion-icon> Delete</a>
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

    function cek_value() 
    {
        $value = $this->input->post('value');

        $query = $this->db->query("SELECT um.id_menu FROM tb_admin_user_menu um WHERE um.nama_menu = '".$value."' ")->result();

        if ($query) {
            $response['status']  = 0;
            $response['message'] = '';
        } else {
            $response['status']  = 1;
            $response['message'] = '';
        }

        json_response($response);
    }

    function cek_value_code() 
    {
        $value = $this->input->post('value');

        $query = $this->db->query("SELECT um.id_menu FROM tb_admin_user_menu um WHERE um.kode_menu = '".$value."' ")->result();

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
        $data['nama_menu'] = $this->input->post('nama_menu');
        $data['kode_menu'] = $this->input->post('kode_menu');

        $query = $this->db->insert('tb_admin_user_menu', $data);

        if($query) {
            $response['status']  = 1;
            $response['message'] = 'Data berhasil ditambahkan.';
        } else {
            $response['status']  = 2;
            $response['message'] = 'Gagal menambah data.';
        }

        json_response($response);
    }

    function get_data()
    {
        $id = $this->input->post('id');

        $query = $this->db->query("SELECT * FROM tb_admin_user_menu WHERE id_menu=".$id."")->row_array();

        $response['id_menu']   = $query['id_menu'];
        $response['nama_menu'] = $query['nama_menu'];
        $response['kode_menu'] = $query['kode_menu'];

        json_response($response);
    }

    function edit_data()
    {   
        $id = $this->input->post('id');

        $data['nama_menu'] = $this->input->post('nama_menu');
        $data['kode_menu'] = $this->input->post('kode_menu');

        $query = $this->main_model->update_data('tb_admin_user_menu', $data, 'id_menu', $id);

        if($query) {
            $response['status']  = 3;
            $response['message'] = 'Data berhasil Disimpan.';
        } else {
            $response['status']  = 4;
            $response['message'] = 'Gagal menyimpan data.';
        }

        json_response($response);
    }

    function detail_data()
    {
        $id = $this->input->post('id');

        $query = $this->db->query("
            SELECT um.* 
            FROM tb_admin_user_menu um 
            WHERE um.id_menu = ".$id."
        ")->row_array();

        $response['id_menu']   = $query['id_menu'];
        $response['nama_menu'] = $query['nama_menu'];
        $response['kode_menu'] = $query['kode_menu'];

        json_response($response);
    }

    function delete_data()
    {
        $method = $this->input->post('method');

        if($method == 'single')
        {
            $id = $this->input->post('id');

            $query = $this->db->query("DELETE FROM tb_admin_user_menu WHERE id_menu = ".$id." ");

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

                $query = $this->db->query("DELETE FROM tb_admin_user_menu WHERE id_menu in (".$id_str.") ");

                if($query) {
                    $response['status']  = 2;
                    $response['message'] = 'Data berhasil dihapus.';
                } else {
                    $response['status']  = 0;
                    $response['message'] = 'Gagal menghapus data.';
                }
            }
        }

        json_response($response);
    }

}