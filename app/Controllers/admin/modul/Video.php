<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Video extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    function add_video($id_modul_enc)
    {   
        $data = $this->main_model->check_access('modul');

        $id_modul = decrypt_url($id_modul_enc);
        $get_modul = $this->db->query("SELECT id_modul, modul, kategori FROM edu_modul WHERE id_modul = '".$id_modul."' ")->row_array();

        if ($get_modul) {

            $data['modul']       =  $get_modul;

            $data['title']       = 'Video Modul : '.$get_modul['modul'];
            $data['description'] = '';
            $data['keywords']    = '';
            $data['page']           = 'admin/modul/video';
            $this->load->view('admin/index', $data);
        } 
        else {
            redirect('panel');
        }
    }

    function _sql() 
    {
        $id_modul = trim($this->input->get('id_modul'));

        $this->db->select('
            v.id_video,
            v.judul,
            v.jenis,
            v.durasi,
            v.url, 
            v.file_video, 
            m.modul, 
            m.kategori as kategori,
        ');
        $this->db->from('edu_video v');
        $this->db->join('edu_modul m', 'm.id_modul = v.id_modul', 'left');
        $this->db->where('v.id_modul', $id_modul);
        $this->db->where('v.status_delete', 0);
        
        $this->db->order_by('v.id_video DESC');

        return $this->db->get();
    }

    function datatables()
    {   
        $this->main_model->check_access('modul');
        
        $valid_columns = array(
            0 => 'id_video',
            1 => 'judul',
            2 => 'modul',
        );

        $this->main_model->datatable($valid_columns);

        $query = $this->_sql();

        $no   = $this->input->get('start');
        $data = array();

        foreach($query->result_array() as $key) {
            $no++;

            $cact = $this->main_model->check_access_action('modul');

            if ($key['judul']) {
                $judul = character_limiter($key['judul'], 40);
            } else {
                $judul = '-';
            }

            if ($key['modul']) {
                $modul = character_limiter($key['modul'], 15);
            } else {
                $modul = '-';
            }

            if ($key['jenis'] == 'url') {
                $file = "<a href='https://www.youtube.com/embed/".$key['url']."?autoplay=1' target='_blank'>Lihat Link</a>";
            } else {
                $url = base_url()."file_media/file-modul/video/".$key['file_video'];
                $file = "<a href='".$url."' target='_blank'>Lihat File</a>";
            }

            if ($key['durasi'] != '00:00:00') {
                $durasi = $key['durasi'];
            } else {
                $durasi = '-';
            }

            if ($key['kategori'] == 'Ideasi') {
                $kategori = '<span class="badge bg-danger">'.$key['kategori'].'</span> ';
            } else {
                $kategori = '<span class="badge bg-success">'.$key['kategori'].'</span>';
            }

            $data[] = array(
                '<label class="checkbox-custome"><input type="checkbox" name="check-record" value="'.$key['id_video'].'" class="check-record"></label>',
                $no,
                $judul,
                $kategori,
                $modul,
                $file,
                $durasi,
                '<div class="dropdown dropdown-action '.$cact['access_action'].' options ms-auto text-center">
                     <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" id="dropdown-action">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                        <a href="#" class="dropdown-item" id="edit-data" data="'.$key['id_video'].'">
                            <ion-icon name="create-sharp"></ion-icon>Edit</a>
                        <a href="#" class="dropdown-item '.$cact['access_delete'].'" id="delete-data" data="'.$key['id_video'].'">
                            <ion-icon name="trash-sharp"></ion-icon>Delete</a>
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


    function add_data()
    {   
        $this->main_model->check_access('modul');
        $cact = $this->main_model->check_access_action('modul');

        if ($cact['access_add'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 

            if (!empty($_FILES['file_video']['name'])) { 
                $data['file_video'] = $this->upload_file_video(); 
            }

            if (sanitize_input($this->input->post('durasi'))) {
                $data['durasi'] = sanitize_input($this->input->post('durasi'));
            }

            $data['id_modul']  = sanitize_input($this->input->post('id_modul'));
            $data['judul']     = sanitize_input($this->input->post('judul'));
            $data['jenis']     = sanitize_input($this->input->post('jenis'));
            $data['url']       = sanitize_input($this->input->post('url'));
            $data['status']    = 1;
            $data['user_post'] = $this->id_admin;

            $query = $this->db->insert('edu_video', $data);

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


    public function get_data()
    {   
        $this->main_model->check_access('modul');
        $cact = $this->main_model->check_access_action('modul');

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

            $query = $this->db->select('v.*, m.kategori')
                    ->from('edu_video v')
                    ->join('edu_modul m', 'm.id_modul = v.id_modul', 'LEFT')
                    ->where('v.id_video', $id)
                    ->get()
                    ->row_array();

            $response['id_video']   = $query['id_video'];
            $response['id_modul']   = $query['id_modul'];
            $response['kategori']   = $query['kategori'];
            $response['judul']      = $query['judul'];
            $response['jenis']      = $query['jenis'];
            $response['url']        = $query['url'];
            $response['file_video'] = $query['file_video'];
            $response['durasi']     = $query['durasi'];
        }

        json_response($response);
    }

    public function edit_data()
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

            if ($_FILES['file_video']['name']) {
                $data['file_video'] = $this->upload_file_video();
            }

            if (sanitize_input($this->input->post('durasi'))) {
                $data['durasi']     = sanitize_input($this->input->post('durasi'));
            }

            $data['judul']    = sanitize_input($this->input->post('judul'));
            $data['jenis']    = sanitize_input($this->input->post('jenis'));
            $data['url']      = sanitize_input($this->input->post('url'));

            $query = $this->main_model->update_data('edu_video', $data, 'id_video', $id);

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

    private function upload_file_video()
    {
        $config['upload_path']  = './file_media/file-modul/video/';
        $config['allowed_types']= 'mp4|mpg|mpeg|mov|avi|flv|wmv';
        $config['file_name']    = 'video_'.substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5);

        if ($this->input->post('param') == 'edit') {
            $id = $this->input->post('id');

            $query = $this->db->query("SELECT file_video FROM edu_video WHERE id_video = ".$id." ")->row_array();
            
            if($query['file_video']) {
                if (file_exists(FCPATH.'file_media/file-modul/video/'.$query['file_video'])) {
                    $config['overwrite'] = true;
                    unlink(FCPATH.'file_media/file-modul/video/'.$query['file_video']);
                }
            }
        }

        $this->upload->initialize($config);

        if ($this->upload->do_upload('file_video')) { 
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

                $this->delete_file_video($id);

                $data['status_delete'] = 1;
                $query = $this->main_model->update_data('edu_video', $data, 'id_video', $id);

                if($query) {
                    $response = 1;
                }
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

                    $query = $this->db->query("UPDATE edu_video SET status_delete = 1 WHERE id_video in (".$id_str.")");

                    if($query) {
                        $response = 2;
                    }
                    json_response($response);
                }
            }
        }
    }

    private function delete_file_video($id)
    {   
        $query = $this->db->query("SELECT * FROM edu_video WHERE id_video = '".$id."'")->row();
        if ($query->file_video != "")
        {   
            if (file_exists(FCPATH.'/file_media/file-modul/video/'.$query->file_video)) {
                $filename = explode(".", $query->file_video)[0];
                return array_map('unlink', glob(FCPATH."file_media/file-modul/video/$filename.*"));
            }
        }
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