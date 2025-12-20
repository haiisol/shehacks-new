<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    public function Index()
    {
        $data = $this->main_model->check_access('content_page');
        
        $data['title']       = 'Data FAQ';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']           = 'admin/content/faq';
        $this->load->view('admin/index', $data);
    }

    function _sql() 
    {
        $this->db->select('c.*');
        $this->db->from('tb_content c');
        $this->db->where('c.status_delete', 0);
        $this->db->where('c.section', 'faq');
        
        return $this->db->get();
    }

    function datatables()
    {   
        $this->main_model->check_access('content_page');

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

            $cact = $this->main_model->check_access_action('content_page');
            
            // status
            if ($key['status'] == 1) {
                $status = '<span class="badge rounded-pill bg-success">Aktif</span>';
            } else {
                $status = '<span class="badge rounded-pill bg-warning">Tidak Aktif</span> ';
            }

            // date update
            if ($key['date_update'] == '0000-00-00 00:00:00') {
                if ($key['date_create'] == '0000-00-00 00:00:00') {
                    $date_update = '-';
                } else {
                    $date_update = time_ago_from_3($key['date_create']);;
                }
            } else {
                $date_update = time_ago_from_3($key['date_update']);
            }

            $data[] = array(
                '<label class="checkbox-custome"><input type="checkbox" name="check-record" value="'.$key['id'].'" class="check-record"></label>',
                $no,
                '<a href="javascript:void(0)" class="detail-data" data="'.$key['id'].'">'.character_limiter($key['heading'], 25).'</a>',
                character_limiter($key['description'], 25),
                $status,
                $date_update,
                '<div class="dropdown dropdown-action '.$cact['access_action'].' options ms-auto text-center">
                    <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" id="dropdown-action">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end" aria-labelledby="dropdown-action">
                        <a href="javascript:void(0)" class="dropdown-item edit-data '.$cact['access_edit'].'" data="'.$key['id'].'"><ion-icon name="pencil-sharp"></ion-icon> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item change-status '.$cact['access_edit'].'" data="'.$key['id'].'" param="'.$key['status'].'"><ion-icon name="swap-horizontal-sharp"></ion-icon> Change Status</a>
                        <a href="javascript:void(0)" class="dropdown-item '.$cact['access_delete'].'" id="delete-data" data="'.$key['id'].'"><ion-icon name="trash-sharp"></ion-icon> Delete</a>
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
        $value = $this->input->get('value');

        $query = $this->db->query("SELECT c.id FROM tb_content c WHERE c.heading = '".$value."' ")->result();

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
        $this->main_model->check_access('content_page');
        $cact = $this->main_model->check_access_action('content_page');

        if ($cact['access_add'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {
            $heading       = sanitize_input($this->input->post('heading'));
            $description   = sanitize_input_textEditor($this->input->post('description'));

            $this->load->library('form_validation');
            $row = array(
                "heading"       => $heading,
                "description"   => $description,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('heading', 'heading', 'trim|required');
            $this->form_validation->set_rules('description', 'description', 'trim|required');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $data['heading']     = $heading;
            $data['description'] = $description;
            $data['section']     = 'faq';
            $data['status']      = sanitize_input($this->input->post('status'));
            $data['date_create'] = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');

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
        $this->main_model->check_access('content_page');
        $cact = $this->main_model->check_access_action('content_page');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {  

            $id = (int) $this->input->post('id');

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
                    ->from('tb_content')
                    ->where('id', $id, TRUE)
                    ->get()
                    ->row_array();

            $response['id']          = $query['id'];
            $response['heading']     = $query['heading'];
            $response['description'] = $query['description'];
            $response['status']      = $query['status'];
        }

        json_response($response);
    }

    function edit_data()
    {   
        $this->main_model->check_access('content_page');
        $cact = $this->main_model->check_access_action('content_page');

        if ($cact['access_edit'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {

            $heading       = sanitize_input($this->input->post('heading'));
            $description   = sanitize_input_textEditor($this->input->post('description'));
            $id            = (int) $this->input->post('id');

            $this->load->library('form_validation');
            $row = array(
                "heading"           => $heading,
                "description"       => $description,
                "id"                => $id,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('heading', 'heading', 'trim|required');
            $this->form_validation->set_rules('description', 'description', 'trim|required');
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $data['heading']     = $heading;
            $data['description'] = $description;
            $data['status']      = sanitize_input($this->input->post('status'));
            $data['date_update'] = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');

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
        $id = $this->input->get('id');

        $query = $this->db->query("
            SELECT c.* 
            FROM tb_content c 
            WHERE c.id = ".$id."
        ")->row_array();


        // status
        if ($query['status'] == 1) {
            $status = '<span class="badge rounded-pill bg-success">Aktif</span>';
        } else {
            $status = '<span class="badge rounded-pill bg-warning">Tidak Aktif</span> ';
        }

        // date update
        if ($query['date_update'] == "0000-00-00 00:00:00") {
            if ($query['date_create'] == "0000-00-00 00:00:00") {
                $date_update = '-';
            } else {
                $date = date_ind(date('Y-m-d', strtotime($query['date_create'])), 'full');
                $date_update = $date.' '.date('h.i', strtotime($query['date_create']));
            }
        } else {
            $date = date_ind(date('Y-m-d', strtotime($query['date_create'])), 'full');
            $date_update = $date.' '.date('h.i', strtotime($query['date_create']));
        }

        $response['id']          = $query['id'];
        $response['heading']     = $query['heading'];
        $response['description'] = $query['description'];
        $response['status']      = $status;
        $response['date']        = $date_update;

        json_response($response);
    }

    function delete_data()
    {   
        $this->main_model->check_access('content_page');
        $cact = $this->main_model->check_access_action('content_page');

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

                $query = $this->db->query("DELETE FROM tb_content WHERE id = ".$id." ");

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

                    $query = $this->db->query("DELETE FROM tb_content WHERE id in (".$id_str.") ");

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

    function change_status()
    {   
        $this->main_model->check_access('content_page');
        $cact = $this->main_model->check_access_action('content_page');

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

            $query = $this->main_model->update_data('tb_content', $data, 'id', $id);

            if($query) {

                $response['status'] = 1;

                if ($param == 1) {
                    $response['message'] = 'Berhasil me-nonaktifkan.';
                } 
                else if ($param == 0) {
                    $response['message'] = 'Berhasil meng-aktifkan.';
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

