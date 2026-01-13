<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Partner extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    public function Index()
    {
        $data = $this->main_model->check_access('content_home');
        
        $data['title']       = 'Data kategori partner';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']           = 'admin/content/home/partner';
        $this->load->view('admin/index', $data);
    }

    function _sql() 
    {
        $this->db->select('
            id_kategori, 
            nama,
            urutan
        ');
        $this->db->from('tb_partner_kategori');
        $this->db->where('status_delete', 0);
        
        return $this->db->get();
    }

    function datatables()
    {   
        $this->main_model->check_access('content_home');
        $valid_columns = array(
            1 => 'id_kategori',
            2 => 'nama_kategori',
        );

        $this->main_model->datatable($valid_columns);

        $query = $this->_sql();

        $no   = $this->input->get('start');
        $data = array();

        foreach($query->result_array() as $key) {
            $no++;

            $get_sub = $this->db->query("SELECT COUNT(id_partner) as total FROM tb_partner
                WHERE id_kategori = '".$key['id_kategori']."' AND status_delete = '0' ")->row_array();

            if ($get_sub) {
                $total_sub = $get_sub['total'];
            } else {
                $total_sub = 0;
            }
            

            $cact = $this->main_model->check_access_action('content_home');
            
            $url_sub = base_url().'admin/content/home/partner_list/tambah/'.encrypt_url($key['id_kategori']);

            $data[] = array(
                '<label class="checkbox-custome"><input type="checkbox" name="check-record" value="'.$key['id_kategori'].'" class="check-record"></label>',
                $no,
                character_limiter($key['nama'], 50),
                $total_sub.' Partner',
                $key['urutan'],
                '<div class="dropdown dropdown-action '.$cact['access_action'].' options ms-auto text-center">
                    <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" id="dropdown-action">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end" aria-labelledby="dropdown-action">
                        <a href="javascript:void(0)" class="dropdown-item edit-data '.$cact['access_edit'].'" data="'.$key['id_kategori'].'"><ion-icon name="pencil-sharp"></ion-icon> Edit</a>
                        <a href="'.$url_sub.'" class="dropdown-item '.$cact['access_edit'].'" data="'.$key['id_kategori'].'"><ion-icon name="list-sharp"></ion-icon> Tambah Logo Partner</a>
                        <a href="javascript:void(0)" class="dropdown-item '.$cact['access_delete'].'" id="delete-data" data="'.$key['id_kategori'].'"><ion-icon name="trash-sharp"></ion-icon> Delete</a>
                    </div>
                </div>'
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

    function cek_value() 
    {   
        $this->main_model->check_access('content_home');
        $cact = $this->main_model->check_access_action('content_home');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 

            $nama = $this->input->post('value', TRUE);

            $this->load->library('form_validation');
            $row = array(
                "nama"     => $nama,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('nama', 'nama', 'trim|required|callback_valid_huruf_angka_spasi');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $query = $this->db->select('id_kategori')
                    ->from('tb_partner_kategori')
                    ->where('nama', $nama, TRUE)
                    ->where('status_delete', '0')
                    ->get()
                    ->result();

            if ($query) {
                $response['status']  = 0;
                $response['message'] = '';
            } else {
                $response['status']  = 1;
                $response['message'] = '';
            }
        }

        json_response($response);
    }

    function add_data()
    {   
        $this->main_model->check_access('content_home');
        $cact = $this->main_model->check_access_action('content_home');

        if ($cact['access_add'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 

            $nama          = $this->input->post('nama', TRUE);
            $urutan        = $this->input->post('urutan', TRUE);

            $this->load->library('form_validation');
            $row = array(
                "nama"     => $nama,
                "urutan"   => $urutan,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('nama', 'nama', 'trim|required|callback_valid_huruf_angka_spasi');
            $this->form_validation->set_rules('urutan', 'urutan', 'trim|required|numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $data['nama']   = $nama;
            $data['urutan'] = $urutan;

            $query = $this->db->insert('tb_partner_kategori', $data);

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
        $this->main_model->check_access('content_home');
        $cact = $this->main_model->check_access_action('content_home');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 

            $id     = $this->input->get('id', TRUE);

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
                    ->from('tb_partner_kategori')
                    ->where('id_kategori', $id, TRUE)
                    ->get()
                    ->row_array();

            $response['id_kategori']    = $query['id_kategori'];
            $response['nama']           = $query['nama'];
            $response['urutan']         = $query['urutan'];
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

            $nama          = $this->input->post('nama', TRUE);
            $urutan        = $this->input->post('urutan', TRUE);
            $id            = $this->input->post('id', TRUE);

            $this->load->library('form_validation');
            $row = array(
                "nama"     => $nama,
                "urutan"   => $urutan,
                "id"       => $id,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('nama', 'nama', 'trim|required|callback_valid_huruf_angka_spasi');
            $this->form_validation->set_rules('urutan', 'urutan', 'trim|required|numeric');
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $data['nama']   = $nama;
            $data['urutan'] = $urutan;

            $query = $this->main_model->update_data('tb_partner_kategori', $data, 'id_kategori', $id);

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

    function detail_data()
    {
        $id = $this->input->post('id');

        $query = $this->db->query("
            SELECT j.* 
            FROM tb_partner_kategori j 
            WHERE j.id_kategori = ".$id."
        ")->row_array();

        $response['id_kategori']     = $query['id_kategori'];
        $response['nama']           = $query['nama'];
        $response['urutan']           = $query['urutan'];

        json_response($response);
    }

    function delete_data()
    {   
        $this->main_model->check_access('content_home');
        $cact = $this->main_model->check_access_action('content_home');

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
                $id = $this->input->post('id', TRUE);

                $query = $this->db->query("UPDATE tb_partner_kategori SET status_delete = 1 WHERE id_kategori = '".$id."' ");

                if($query) {

                    $this->db->query("UPDATE tb_partner SET status_delete = 1 WHERE id_kategori = '".$id."' ");

                    $response['status']  = 1;
                    $response['message'] = 'Data berhasil dihapus.';
                } else {
                    $response['status']  = 0;
                    $response['message'] = 'Gagal menghapus data.';
                }
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

                    $query = $this->db->query("UPDATE tb_partner_kategori SET status_delete = 1 WHERE id_kategori in (".$id_str.")");

                    if($query) {

                        $this->db->query("UPDATE tb_partner SET status_delete = 1 WHERE id_kategori in (".$id_str.") ");
                        
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