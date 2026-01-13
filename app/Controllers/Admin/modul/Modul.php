<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Modul extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    public function Index()
    {
        $data = $this->main_model->check_access('modul');
        
        $data['title']       = 'Data Modul';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']           = 'admin/modul/modul';
        $this->load->view('admin/index', $data);
    }


    function _sql() 
    {
        $fil_kategori = trim($this->input->get('fil_kategori') ?? '');

        $this->db->select('m.id_modul, m.modul, m.cover, m.status_quiz, m.kategori as kategori');
        $this->db->from('edu_modul m');
        $this->db->where('m.status_delete', 0);

        if ($fil_kategori) {
            $this->db->where('m.kategori', $fil_kategori);
        }

        $this->db->order_by('m.id_modul DESC');

        return $this->db->get();
    }

    function datatables()
    {   
        $this->main_model->check_access('modul');
        
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
            1 => 'm.id_modul',
            3 => 'm.modul',
        );

        $this->main_model->datatable($valid_columns);

        $query = $this->_sql();

        $no   = $this->input->get('start');
        $data = array();

        foreach($query->result_array() as $key) {
            $no++;

            $cact = $this->main_model->check_access_action('modul');

            $url_add_video = base_url().'admin/modul/video/add_video/'.encrypt_url($key['id_modul']);

            if ($key['modul']) {
                $modul = character_limiter($key['modul'], 30);
            } else {
                $modul = '-';
            }

            if ($key['kategori'] == 'Ideasi') {
                $kategori = '<span class="badge bg-danger">'.$key['kategori'].'</span> ';
            } else {
                $kategori = '<span class="badge bg-success">'.$key['kategori'].'</span>';
            }

            if ($key['status_quiz'] == 1) {
                $quiz = '<span class="badge rounded-pill bg-success">Yes</span> ';
            } else {
                $quiz = '<span class="badge rounded-pill bg-danger">No</span>';
            }

            $count_video = $this->db->query("SELECT COUNT(id_video) as total FROM edu_video WHERE id_modul = ".$key['id_modul']." ")->row_array();

            $data[] = array(
                '<label class="checkbox-custome"><input type="checkbox" name="check-record" value="'.$key['id_modul'].'" class="check-record"></label>',
                $no,
                $kategori,
                $modul,
                $quiz,
                $count_video['total'],
                '<div class="dropdown dropdown-action '.$cact['access_action'].' options ms-auto text-center">
                     <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" id="dropdown-action">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                        <a href="#" class="dropdown-item" id="edit-data" data="'.$key['id_modul'].'">
                            <ion-icon name="create-sharp"></ion-icon>Edit</a>
                        <a href="'.$url_add_video.'" target="_blank" class="dropdown-item">
                           <ion-icon name="add-circle-sharp"></ion-icon>Tambah Video</a>
                        <a href="#" class="dropdown-item '.$cact['access_delete'].'" id="delete-data" data="'.$key['id_modul'].'">
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
        $this->main_model->check_access('modul');
        $cact = $this->main_model->check_access_action('modul');

        if ($cact['access_add'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {  

            if (!empty($_FILES['cover']['name'])) { 
                $data['cover'] = $this->upload_image(); 
            }

            $data['kategori']        = sanitize_input($this->input->post('kategori'));
            $data['modul']           = sanitize_input($this->input->post('modul'));
            $data['deskripsi_modul'] = sanitize_input($this->input->post('deskripsi_modul'));
            $data['status_quiz']     = sanitize_input($this->input->post('status_quiz'));
            $data['user_post']       = $this->id_admin;
            $data['date_create']     = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');

            $query = $this->db->insert('edu_modul', $data);

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
        $this->main_model->check_access('modul');
        $cact = $this->main_model->check_access_action('modul');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {  

            $id     = (int) $this->input->get('id');

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
                    ->from('edu_modul')
                    ->where('id_modul', $id)
                    ->get()
                    ->row_array();

            $cover = $this->main_model->url_image($query['cover'], 'file-modul');

            $data['id_modul']        = $query['id_modul'];
            $data['kategori']        = $query['kategori'];
            $data['modul']           = $query['modul'];
            $data['cover']           = $cover;
            $data['deskripsi_modul'] = $query['deskripsi_modul'];
            $data['status_quiz']     = $query['status_quiz'];

        }

        json_response($data);
    }


    function edit_data()
    {   
        $this->main_model->check_access('modul');
        $cact = $this->main_model->check_access_action('modul');

        if ($cact['access_edit'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {  

            $id     = $this->input->post('id', TRUE);

            $this->load->library('form_validation');
            $row = array(
                "id"   => $id,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            if (!empty($_FILES['cover']['name'])) { 
                $data['cover'] = $this->upload_image(); 
            }

            $data['kategori']        = sanitize_input($this->input->post('kategori'));
            $data['modul']           = sanitize_input($this->input->post('modul'));
            $data['deskripsi_modul'] = sanitize_input($this->input->post('deskripsi_modul'));
            $data['status_quiz']     = sanitize_input($this->input->post('status_quiz'));

            $query = $this->main_model->update_data('edu_modul', $data, 'id_modul', $id);

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
        $config['upload_path']  = './file_media/file-modul/';
        $config['allowed_types']= 'jpg|jpeg|png';
        $config['file_name']    = 'modul_cover_'.substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5);

        if ($this->input->post('param') == 'edit') {
            $id = $this->input->post('id');
            
            $query = $this->db->query("SELECT cover FROM edu_modul WHERE id_modul = ".$id." ")->row_array();
            
            if($query['cover']) {
                if (file_exists(FCPATH.'/file_media/file-modul/'.$query['cover'])) {
                    $config['overwrite'] = true;
                    unlink(FCPATH.'/file_media/file-modul/'.$query['cover']);
                }
            }
        }
        
        $this->upload->initialize($config);

        if ($this->upload->do_upload('cover')) {
            return $this->upload->data('file_name');
        }
        return '';
    }

    function delete_data()
    {   
        $this->main_model->check_access('modul');
        $cact = $this->main_model->check_access_action('modul');

        if ($cact['access_delete'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
            json_response($response);
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

                $this->delete_edu_ebook($id);

                $get_quiz = $this->db->query("SELECT * FROM quiz WHERE id_modul = '".$id."'")->row();

                $data_delete['status_delete']  = 1 ;

                if($get_quiz) {

                    $this->main_model->update_data('quiz_jawaban', $data_delete, 'id_quiz', $get_quiz->id_quiz);
                    $this->main_model->update_data('quiz', $data_delete, 'id_modul', $id);

                }
                
                $this->main_model->update_data('edu_ebook', $data_delete, 'id_modul', $id);
                $query =  $this->main_model->update_data('edu_modul', $data_delete, 'id_modul', $id);

                $response['status'] = 1;

                json_response($response);
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

                    $this->db->query("UPDATE quiz SET status_delete = 1 WHERE id_modul in (".$id_str.")");
                    $this->db->query("UPDATE edu_ebook SET status_delete = 1 WHERE id_modul in (".$id_str.")");
                    $query = $this->db->query("UPDATE edu_modul SET status_delete = 1 WHERE id_modul in (".$id_str.")");

                    $response['status'] = 2;

                    json_response($response);
                }
            }
        }
    }


    private function delete_edu_ebook($id)
    {   
        $yes = "";
        $query = $this->db->query("SELECT * FROM edu_ebook WHERE id_modul = '".$id."'")->result_array();
        foreach ($query as $key) {
            if ($key['file_pdf'])
            {
                $filename = explode(".", $key['file_pdf'])[0];
                $yes = array_map('unlink', glob(FCPATH."file_media/file-modul/ebook/$filename.*"));
            }
        }
        return $yes;
    }

    private function delete_edu_ebook_all($id)
    {
        $yes = "";
        $cek = $this->db->query("SELECT * FROM edu_ebook WHERE id_modul in (".$id.") ")->result_array();
        foreach ($cek as $key) {
            if ($key['file_pdf'])
            {
                $filename = explode(".", $key['file_pdf'])[0];
                $yes = array_map('unlink', glob(FCPATH."file_media/file-modul/ebook/$filename.*"));
            }
        }
            return $yes;
    }

    
    function get_modul()
    {
        $id  = $this->input->post('id');

      
        $get_modul = $this->db->query("SELECT * FROM edu_modul WHERE kategori = '".$id."'  AND status_delete = '0' ORDER BY id_modul DESC")->result();

        $show = '<option value="">Pilih Modul</pilih>';

        foreach ($get_modul as $row) {
            $show .= '<option value="'.$row->id_modul.'">'.$row->modul.'</option>';
        }

        echo $show;
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

