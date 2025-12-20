<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Video extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    public function Index()
    {
        $data = $this->main_model->check_access('content_home');
        
        $data['title']       = 'Data Video';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']           = 'admin/content/home/video';
        $this->load->view('admin/index', $data);
    }

    function _sql() 
    {
        $this->db->select('
            id, 
            heading,
            video
        ');
        $this->db->from('tb_content');
        $this->db->where('status_delete', 0);
        $this->db->where('section', 'home_video');
        
        return $this->db->get();
    }

    function datatables()
    {   
        $this->main_model->check_access('content_home');
        
        $valid_columns = array(
            1 => 'id',
            2 => 'heading',
        );

        $this->main_model->datatable($valid_columns);

        $query = $this->_sql();

        $no   = $this->input->get('start');
        $data = array();

        foreach($query->result_array() as $key) {
            $no++;
            
            $cact = $this->main_model->check_access_action('content_home');

            $url_image = 'http://img.youtube.com/vi/'.$key['video'].'/mqdefault.jpg';

            $data[] = array(
                '<label class="checkbox-custome"><input type="checkbox" name="check-record" value="'.$key['id'].'" class="check-record"></label>',
                $no,
                '<img src="'.$url_image.'" class="img-fluid">',
                character_limiter($key['heading'], 70),
                '<div class="dropdown dropdown-action '.$cact['access_action'].' options ms-auto text-center">
                    <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" id="dropdown-action">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end" aria-labelledby="dropdown-action">
                        <a href="javascript:void(0)" class="dropdown-item edit-data '.$cact['access_edit'].'" data="'.$key['id'].'"><ion-icon name="pencil-sharp"></ion-icon> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item '.$cact['access_delete'].'" id="delete-data" data="'.$key['id'].'"><ion-icon name="trash-sharp"></ion-icon> Delete</a>
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

            $heading = $this->input->post('value', TRUE);

            $this->load->library('form_validation');
            $row = array(
                "heading"     => $heading,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('heading', 'heading', 'trim|required|callback_valid_huruf_angka_spasi');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $query = $this->db->select('id')
                    ->from('tb_content')
                    ->where('heading', $heading, TRUE)
                    ->where('status_delete', '0')
                    ->where('section', 'home_video')
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

            $heading          = $this->input->post('heading', TRUE);
            $video       = $this->input->post('video', TRUE);

            $this->load->library('form_validation');
            $row = array(
                "heading"     => $heading,
                "video"  => $video,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('heading', 'heading', 'trim|required|callback_valid_huruf_angka_spasi');
            $this->form_validation->set_rules('video', 'video', 'trim|required');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $data['heading']            = $heading;
            $data['video']              = $video;
            $data['section']            = 'home_video';

            $query = $this->db->insert('tb_content', $data);

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
                    ->from('tb_content')
                    ->where('id', $id, TRUE)
                    ->get()
                    ->row_array();

            $response['id']                 = $query['id'];
            $response['heading']            = $query['heading'];
            $response['video']              = $query['video'];
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

            $heading            = $this->input->post('heading', TRUE);
            $video              = $this->input->post('video', TRUE);
            $id                 = $this->input->post('id', TRUE);

            $this->load->library('form_validation');
            $row = array(
                "heading"       => $heading,
                "video"         => $video,
                "id"            => $id,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('heading', 'heading', 'trim|required|callback_valid_huruf_angka_spasi');
            $this->form_validation->set_rules('video', 'video', 'trim|required');
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $data['heading']            = $heading;
            $data['video']              = $video;

            $query = $this->main_model->update_data('tb_content', $data, 'id', $id);

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
            FROM tb_content j 
            WHERE j.id = ".$id."
        ")->row_array();

        $response['id']         = $query['id'];
        $response['heading']    = $query['heading'];
        $response['video']      = $query['video'];

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
                $id = $this->input->post('id');

                $query = $this->db->query("UPDATE tb_content SET status_delete = 1 WHERE id = '".$id."' ");

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

                    $query = $this->db->query("UPDATE tb_content SET status_delete = 1 WHERE id in (".$id_str.")");

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