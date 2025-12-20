<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Partner_list extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    public function tambah($id_kategori_enc)
    {
        $data = $this->main_model->check_access('content_home');
        
        $id_kategori  = decrypt_url($id_kategori_enc);

        $get_kat = $this->db->query("SELECT id_kategori, nama FROM tb_partner_kategori
                WHERE id_kategori = '".$id_kategori."' ")->row_array();

        if ($get_kat) {

            $data['id_kategori_enc']    = $id_kategori_enc;
            $data['title']              = 'List Partner - '.$get_kat['nama'];
            $data['description']        = '';
            $data['keywords']           = '';
            $data['page']               = 'admin/content/home/partner_list';
            $this->load->view('admin/index', $data);
        } else {
            redirect('');
        }
        
    }

    function _sql($id_kategori) 
    {
        $this->db->select('
            id_partner, 
            nama,
            image,
            urutan
        ');
        $this->db->from('tb_partner');
        $this->db->where('status_delete', 0);
        $this->db->where('id_kategori', $id_kategori);
        
        return $this->db->get();
    }

    function datatables($id_kategori_barang)
    {   
        $this->main_model->check_access('content_home');
        $valid_columns = array(
            1 => 'id_partner',
            2 => 'name',
        );

        $this->main_model->datatable($valid_columns);

        $query = $this->_sql($id_kategori_barang);

        $no   = $this->input->get('start');
        $data = array();

        foreach($query->result_array() as $key) {
            $no++;

            // image
            $url_image = $this->main_model->url_image($key['image'], 'image-content');

            $cact = $this->main_model->check_access_action('content_home');

            $data[] = array(
                '<label class="checkbox-custome"><input type="checkbox" name="check-record" value="'.$key['id_partner'].'" class="check-record"></label>',
                $no,
                '<img src="'.$url_image.'" class="img-fluid">',
                character_limiter($key['nama'], 50),
                $key['urutan'],
                '<div class="dropdown dropdown-action '.$cact['access_action'].' options ms-auto text-center">
                    <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" id="dropdown-action">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end" aria-labelledby="dropdown-action">
                        <a href="javascript:void(0)" class="dropdown-item edit-data '.$cact['access_edit'].'" data="'.$key['id_partner'].'"><ion-icon name="pencil-sharp"></ion-icon> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item '.$cact['access_delete'].'" id="delete-data" data="'.$key['id_partner'].'"><ion-icon name="trash-sharp"></ion-icon> Delete</a>
                    </div>
                </div>'
            );
        }

        $response = array(
            "draw"            => intval($this->input->get("draw")),
            "recordsTotal"    => $this->_sql($id_kategori_barang)->num_rows(),
            "recordsFiltered" => $this->_sql($id_kategori_barang)->num_rows(),
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
                    ->from('tb_partner')
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
            $id_kategori   = $this->input->post('id_kategori', TRUE);
            $url           = $this->input->post('url', TRUE);

            $this->load->library('form_validation');
            $row = array(
                "nama"     => $nama,
                "urutan"   => $urutan,
                "id_kategori"   => $id_kategori,
                // "url"       => $url,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('nama', 'nama', 'trim|required|callback_valid_huruf_angka_spasi');
            $this->form_validation->set_rules('urutan', 'urutan', 'trim|required|numeric');
            $this->form_validation->set_rules('id_kategori', 'id_kategori', 'trim|required|numeric');
            // $this->form_validation->set_rules('url', 'url', 'trim|required|numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $data['nama']   = $nama;
            $data['urutan'] = $urutan;
            $data['id_kategori'] = $id_kategori;
            $data['url']    = $url;

            if (!empty($_FILES['image']['name'])) {
                $data['image'] = $this->upload_image('image');
            }
            
            $query = $this->db->insert('tb_partner', $data);

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
                    ->from('tb_partner')
                    ->where('id_partner', $id, TRUE)
                    ->get()
                    ->row_array();


            $response['id_partner']             = $query['id_partner'];
            $response['nama']                   = $query['nama'];
            $response['url']                    = $query['url'];
            $response['urutan']                 = $query['urutan'];
            $response['image']                  = $this->main_model->url_image($query['image'], 'image-content');   
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
            $id_kategori   = $this->input->post('id_kategori', TRUE);
            $url           = $this->input->post('url', TRUE);
            $id            = $this->input->post('id', TRUE);

            $this->load->library('form_validation');
            $row = array(
                "nama"     => $nama,
                "urutan"   => $urutan,
                "id_kategori"   => $id_kategori,
                "url"       => $url,
                "id"        => $id,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('nama', 'nama', 'trim|required|callback_valid_huruf_angka_spasi');
            $this->form_validation->set_rules('urutan', 'urutan', 'trim|required|numeric');
            $this->form_validation->set_rules('id_kategori', 'id_kategori', 'trim|required|numeric');
            // $this->form_validation->set_rules('url', 'url', 'trim|required');
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $data['nama']   = $nama;
            $data['urutan'] = $urutan;
            $data['id_kategori'] = $id_kategori;
            $data['url']    = $url;

            if (!empty($_FILES['image']['name'])) {
                $data['image'] = $this->upload_image('image');
            }

            $query = $this->main_model->update_data('tb_partner', $data, 'id_partner', $id);

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
        $config['file_name']    = 'Partner-'.substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
        $config['quality']      = '60%';

        if ($this->input->post('param') == 'edit') {
            $id = $this->input->post('id');
            $query = $this->db->query("SELECT c.image FROM tb_partner c WHERE id_partner = ".$id." ")->row_array();

            if ($name == 'image') {
                if($query['image']) {
                    if (file_exists(FCPATH.'file_media/image-content/'.$query['image'])) {
                        $config['overwrite'] = true;
                        unlink(FCPATH.'file_media/image-content/'.$query['image']);
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

    function detail_data()
    {
        $id = $this->input->post('id');

        $query = $this->db->query("
            SELECT j.* 
            FROM tb_partner j 
            WHERE j.id_partner = ".$id."
        ")->row_array();

        $response['id_partner']         = $query['id_partner'];
        $response['nama']               = $query['nama'];
        $response['urutan']             = $query['urutan'];
        $response['image']              = $this->main_model->url_image($query['image'], 'image-content');

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

                $this->delete_single_image($id);
                $query = $this->db->query("UPDATE tb_partner SET status_delete = 1 WHERE id_partner = '".$id."' ");

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
                $json = $this->input->post('id', TRUE);
                $id = array();

                if (strlen($json) > 0) {
                    $id = json_decode($json);
                }

                if (count($id) > 0) {
                    $id_str = "";
                    $id_str = implode(',', $id);

                    $query = $this->db->query("UPDATE tb_partner SET status_delete = 1 WHERE id_partner in (".$id_str.")");

                    if($query) {

                        $this->delete_multiple_image($id_str);

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
        $query = $this->db->query("SELECT image FROM tb_partner WHERE id_partner = ".$id." ")->row_array();

        if ($query['image']) {
            if (file_exists(FCPATH.'file_media/image-content/'.$query['image'])) {
                $filename = explode(".", $query['image'])[0];
                array_map('unlink', glob(FCPATH."file_media/image-content/$filename.*"));
            }
        }
    }

    private function delete_multiple_image($id)
    {
        $return = '';
        $query = $this->db->query("SELECT image FROM tb_partner WHERE id_partner in (".$id.") ")->result_array();

        foreach ($query as $key) {
            if ($key['image']) {
                if (file_exists(FCPATH.'file_media/image-content/'.$key['image'])) {
                    $filename = explode(".", $key['image'])[0];
                    array_map('unlink', glob(FCPATH."file_media/image-content/$filename.*"));
                }
            }
        }
        return $return;
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