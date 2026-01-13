<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Blog_kategori extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    public function Index()
    {
        $data = $this->main_model->check_access('blog');
        
        $data['title']       = 'Data Blog Kategori';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']           = 'admin/blog/blog_kategori';
        $this->load->view('admin/index', $data);
    }

    function _sql() 
    {
        $this->db->select('bk.*');
        $this->db->from('tb_blog_kategori bk');
        $this->db->where('bk.status_delete', 0);
        
        return $this->db->get();
    }

    function datatables()
    {   
        $this->main_model->check_access('blog');
        
        $valid_columns = array(
            1 => 'id_blog_kategori',
            2 => 'nama',
        );
        
        $this->main_model->datatable($valid_columns);

        $query = $this->_sql();

        $no   = $this->input->get('start');
        $data = array();

        foreach($query->result_array() as $key) {
            $no++;

            $cact = $this->main_model->check_access_action('blog');
            
            $data[] = array(
                '<label class="checkbox-custome"><input type="checkbox" name="check-record" value="'.$key['id_blog_kategori'].'" class="check-record"></label>',
                $no,
                character_limiter($key['nama'], 20),
                '<div class="dropdown dropdown-action '.$cact['access_action'].' options ms-auto text-center">
                    <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" id="dropdown-action">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end" aria-labelledby="dropdown-action">
                        <a href="javascript:void(0)" class="dropdown-item edit-data '.$cact['access_edit'].'" data="'.$key['id_blog_kategori'].'"><ion-icon name="pencil-sharp"></ion-icon> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item '.$cact['access_delete'].'" id="delete-data" data="'.$key['id_blog_kategori'].'"><ion-icon name="trash-sharp"></ion-icon> Delete</a>
                    </div>
                </div>'
            );
        }

        $response['draw']            = intval($this->input->get('draw'));
        $response['recordsTotal']    = $this->_sql()->num_rows();
        $response['recordsFiltered'] = $this->_sql()->num_rows();
        $response['data']            = $data;

        json_response($response);
    }

    function cek_value() 
    {   
        $this->main_model->check_access('blog');
        $cact = $this->main_model->check_access_action('blog');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {

            $nama = sanitize_input($this->input->get('value'));

            $this->load->library('form_validation');
            $row = array(
                "nama" => $nama,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('nama', 'nama', 'trim|required|callback_valid_huruf_angka_spasi');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $query = $this->db->select('id_blog_kategori')
                    ->from('tb_blog_kategori')
                    ->where('nama', $nama)
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
        $this->main_model->check_access('blog');
        $cact = $this->main_model->check_access_action('blog');

        if ($cact['access_add'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {

            $nama = sanitize_input($this->input->post('nama'));

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

            $data['nama'] = $nama;

            $query = $this->db->insert('tb_blog_kategori', $data);

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
        $this->main_model->check_access('blog');
        $cact = $this->main_model->check_access_action('blog');

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
                    ->from('tb_blog_kategori')
                    ->where('id_blog_kategori', $id)
                    ->get()
                    ->row_array();

            $response['id_blog_kategori'] = $query['id_blog_kategori'];
            $response['nama']             = $query['nama'];
        }

        json_response($response);
    }

    function edit_data()
    {   
        $this->main_model->check_access('blog');
        $cact = $this->main_model->check_access_action('blog');

        if ($cact['access_edit'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {

            $id     = $this->input->post('id', TRUE);
            $nama   = sanitize_input($this->input->post('nama'));

            $this->load->library('form_validation');
            $row = array(
                "nama"      => $nama,
                "id"        => $id,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('nama', 'nama', 'trim|required|callback_valid_huruf_angka_spasi');
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $data['nama'] = $nama;

            $query = $this->main_model->update_data('tb_blog_kategori', $data, 'id_blog_kategori', $id);

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
                  ->from('tb_blog_kategori')
                  ->where('id_blog_kategori', $id, TRUE)
                  ->get()
                  ->row_array();

        $response['id_blog_kategori'] = $query['id_blog_kategori'];
        $response['nama']             = $query['nama'];

        json_response($response);
    }

    function delete_data()
    {   
        $this->main_model->check_access('blog');
        $cact = $this->main_model->check_access_action('blog');

        if ($cact['access_delete'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {  

            $method = $this->input->post('method', TRUE);
            $id     = $this->input->post('id');

            $this->load->library('form_validation');
            $row = array(
                "method"        => $method,
                "id"            => $id,
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

                //$query = $this->db->query("UPDATE tb_blog_kategori SET status_delete = 1 WHERE id_blog_kategori = '".$id."' ");
                
                $this->db->where('id_blog_kategori', $id);
                $query = $this->db->update('tb_blog_kategori', ['status_delete' => 1]);

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

                    $this->db->where_in('id_blog_kategori', $id_str);
                    $query = $this->db->update('tb_blog_kategori', ['status_delete' => 1]);
                    
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