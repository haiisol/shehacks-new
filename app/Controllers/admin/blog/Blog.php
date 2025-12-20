<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    public function Index()
    {
        $data = $this->main_model->check_access('blog');
        
        $data['title']       = 'Data Blog';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']           = 'admin/blog/blog';
        $this->load->view('admin/index', $data);
    }

    function _sql() 
    {
        $fil_kategori = trim($this->input->get('fil_kategori') ?? '');

        $this->db->select('
            b.id_blog,
            b.judul, 
            b.gambar,
            b.id_admin,
            b.date_create,
            b.date_update,
            k.nama as nama_kategori
        ');
        $this->db->from('tb_blog b');
        $this->db->join('tb_blog_kategori k', 'b.id_blog_kategori = k.id_blog_kategori', 'left');

        if ($fil_kategori) {
            $this->db->where('b.id_blog_kategori', $fil_kategori);
        }
        
        return $this->db->get();
    }

    function datatables()
    {   
        $this->main_model->check_access('blog');
          
        $this->load->library('form_validation');
        $row = array(
            "fil_kategori" => $this->input->get('fil_kategori', TRUE),
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
            1 => 'b.id_blog',
            3 => 'b.judul',
            4 => 'nama_kategori',
            5 => 'b.date_create',
        );

        $this->main_model->datatable($valid_columns);

        $query = $this->_sql();

        $no   = $this->input->get('start');
        $data = array();

        foreach($query->result_array() as $key) {
            $no++;

            $cact = $this->main_model->check_access_action('blog');
            
            // image
            $url_image = $this->main_model->url_image($key['gambar'], 'file-blog');

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

            // viewer
            $get_viewer = $this->db->query("SELECT SUM(hits) as total FROM tb_analytic_blog WHERE id_blog=".$key['id_blog']." ")->row_array();

            $url_edit   = base_url().'admin/blogs/edit/'.encrypt_url($key['id_blog']);

            $data[] = array(
                '<label class="checkbox-custome"><input type="checkbox" name="check-record" value="'.$key['id_blog'].'" class="check-record"></label>',
                $no,
                '<img src="'.$url_image.'" class="img-fluid">',
                character_limiter($key['judul'], 35),
                character_limiter($key['nama_kategori'], 20),
                number_format($get_viewer['total'], 0,",","."),
                $date_update,
                '<div class="dropdown dropdown-action '.$cact['access_action'].' options ms-auto text-center">
                    <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" id="dropdown-action">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end" aria-labelledby="dropdown-action">
                        <a href="'.$url_edit.'" class="dropdown-item '.$cact['access_edit'].'"><ion-icon name="pencil-sharp"></ion-icon> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item '.$cact['access_delete'].'" id="delete-data" data="'.$key['id_blog'].'"><ion-icon name="trash-sharp"></ion-icon> Delete</a>
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

    public function tulis($param, $id) 
    {   
        $param_id                       = decrypt_url($id);
        $data['admin']                  = $this->db->query("SELECT id_admin, nama_admin FROM tb_admin_user WHERE id_admin = '".$this->id_admin."' ")->row_array();

        $data['param_newsletter']       = $param;
        $data['param_id']               = $param_id;
        $data['title']                  = 'Blog '.$param;
        $data['description']            = '';
        $data['keywords']               = '';

        if ($param == 'tambah') {

            $cact = $this->main_model->check_access_action('blog');

            if ($cact['access_add'] == 'd-none') {
                redirect('admin/blog/blog');
                $this->session->set_flashdata('callout-danger', 'Gagal tidak memiliki akses.');
            } else {
                
                $data['page']           = 'admin/blog/artikel_edit';
                $this->load->view('admin/index', $data);
            
            }

        } else {

            $cact = $this->main_model->check_access_action('blog');

            if ($cact['access_edit'] == 'd-none') {
                redirect('admin/blog/blog');
                $this->session->set_flashdata('callout-danger', 'Gagal tidak memiliki akses.');
            } else {

                $cek = $this->db
                        ->where('id_blog', $param_id)
                        ->get('tb_blog')
                        ->row_array();

                if (empty($cek)) {
                    redirect('admin/blog/blog');
                    $this->session->set_flashdata('callout-danger', 'Artikel tidak ditemukan.');
                } else {
                    $data['page']           = 'admin/blog/artikel_edit';
                    $this->load->view('admin/index', $data);
                }
            }

        }
        
    }

    function cek_value() 
    {   
        $this->main_model->check_access('blog');
        $cact = $this->main_model->check_access_action('blog');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {

            $judul = $this->input->post('value', TRUE);

            $this->load->library('form_validation');
            $row = array(
                "judul"     => $judul,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('judul', 'judul', 'trim|required|callback_valid_huruf_angka_spasi');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $query = $this->db->select('*')
                    ->from('tb_blog')
                    ->where('judul', $judul, TRUE)
                    ->get()
                    ->result();

            if ($query) {
                $response['status']  = 0;
                // $response = 0;
            } else {
                $response['status']  = 1;
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


            if (!empty($_FILES["gambar"]["name"])) { 
                $data['gambar'] = $this->upload_image('gambar'); 
            }

            $data['judul']              = sanitize_input($this->input->post('judul'));
            $data['gambar_sumber']      = sanitize_input($this->input->post('gambar_sumber'));
            $data['gambar_keterangan']  = sanitize_input($this->input->post('gambar_keterangan'));
            $data['slug']               = strtolower(url_title(sanitize_input($this->input->post('judul'))));
            $data['id_blog_kategori']   = sanitize_input($this->input->post('id_kategori'));
            $data['id_admin']           = $this->id_admin;
            $data['deskripsi']          = $this->input->post('deskripsi');
            $data['date_create']        = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');
            
            $query      = $this->db->insert('tb_blog', $data);
            $id_blog    = $this->db->insert_id();

                //Tags
                $tags = sanitize_input($this->input->post('tags'));

                if ($tags) {
                    $array_split = explode(',', $tags);

                    foreach ($array_split as $key => $value) {
                        $cek_tb_tags = $this->db->query("SELECT id_tags FROM tb_blog_tags WHERE tags='".$value."'")->row_array();

                        if ($cek_tb_tags) {
                            
                            $data_tags['id_blog']   = $id_blog;
                            $data_tags['id_tags']   = $cek_tb_tags['id_tags'];

                            $this->db->insert('tb_blog_rel_tags', $data_tags);

                        } else {

                            $insert_tags['tags']      = $value;

                            $this->db->insert('tb_blog_tags', $insert_tags);
                            $id_insert_tags  = $this->db->insert_id();

                            $data_tags['id_blog']= $id_blog;
                            $data_tags['id_tags']      = $id_insert_tags;

                            $this->db->insert('tb_blog_rel_tags', $data_tags);

                        }
                        
                    }
                }
            
            if($query) {
                $response['status']  = 1;
                $response['message'] = 'Success, Tambah Data.';
            } else {
                $response['status']  = 2;
                $response['message'] = 'Gagal, Tambah Data.';
            }
            
        }

        json_response($response);
    }

    private function upload_image($name)
    {
        $config['upload_path']  = './file_media/file-blog/';
        $config['allowed_types']= 'jpg|jpeg|png';
        $config['file_name']    = strtolower(url_title($this->input->post('judul'))).'-'.substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

        if ($this->input->post('param') == 'edit') {
            $id = $this->input->post('id_data');
            $query = $this->db->query("SELECT gambar FROM tb_blog WHERE id_blog = ".$id." ")->row_array();
            
            if($query['gambar']) {
                if (file_exists(FCPATH.'file_media/file-blog/'.$query['gambar'])) {
                    unlink(FCPATH.'file_media/file-blog/'.$query['gambar']);
                }
            }
        }
        
        $this->upload->initialize($config);

        if ($this->upload->do_upload($name)) {
            return $this->upload->data('file_name');
        }
        return '';
    }

    function get_data()
    {   
        $this->main_model->check_access('blog');
        $cact = $this->main_model->check_access_action('blog');

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
                    ->from('tb_blog')
                    ->where('id_blog', $id)
                    ->get()
                    ->row_array();
            
            //Tags
            $query_tags = $this->db->select('t.tags')
                    ->from('tb_blog_rel_tags r')
                    ->join('tb_blog_tags t', 'r.id_tags = t.id_tags', 'LEFT')
                    ->where('r.id_blog', $id)
                    ->get()
                    ->result_array();

            if ($query_tags) {

                $tags_array = '';
                foreach ($query_tags as $key) {
                    $tags_array .= $key['tags'];
                    $tags_array .= ',';
                }

            } else {
                $tags_array = '';
            }
            

            if ($query['id_admin'] != 0) {
                $get_penulis = $this->db->query("SELECT nama_admin FROM tb_admin_user WHERE id_admin ='".$query['id_admin']."'")->row_array();
                if ($get_penulis) {
                    $penulis = $get_penulis['nama_admin'];
                } else {
                    $penulis = ' - ';
                }
            } else {
                $penulis = ' - ';
            }
            
            $response['data']            = $query;
            $response['penulis']         = $penulis;
            $response['array_tags']      = $tags_array;
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

            $id     = $this->input->post('id_data', TRUE);

            $this->load->library('form_validation');
            $row = array(
                "id_data"   => $id,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('id_data', 'id_data', 'trim|required|numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            if (!empty($_FILES["gambar"]["name"])) { 
                $data['gambar'] = $this->upload_image('gambar'); 
            }

            $data['gambar_sumber']      = sanitize_input($this->input->post('gambar_sumber'));
            $data['gambar_keterangan']  = sanitize_input($this->input->post('gambar_keterangan'));
            $data['judul']              = sanitize_input($this->input->post('judul'));
            $data['id_blog_kategori']   = sanitize_input($this->input->post('id_kategori'));
            $data['deskripsi']          = $this->input->post('deskripsi');

            $query = $this->main_model->update_data('tb_blog', $data, 'id_blog', $id);

            //Tags
            $tags = sanitize_input($this->input->post('tags'));

            if ($tags) {

                $array_split = explode(',', $tags);

                    //Seleksi tags untuk ada atau tidak
                    foreach ($array_split as $key => $value) {

                        $cek_tb_tags = $this->db
                            ->select('id_tags')
                            ->from('tb_blog_tags')
                            ->where('tags', $value)
                            ->get()
                            ->row_array();

                        if ($cek_tb_tags) {
                            
                            $cek_tb_rel = $this->db
                                ->select('r.id_rel_tags')
                                ->from('tb_blog_rel_tags r')
                                ->where([
                                    'r.id_blog' => $id,
                                    'r.id_tags' => $cek_tb_tags['id_tags']
                                ])
                                ->get()
                                ->row_array();
                            

                            if (empty($cek_tb_rel)) {
                                $data_tags['id_blog']    = $id;
                                $data_tags['id_tags']      = $cek_tb_tags['id_tags'];

                                $this->db->insert('tb_blog_rel_tags', $data_tags);
                            }


                        } else {

                            $insert_tags['tags']      = $value;

                            $this->db->insert('tb_blog_tags', $insert_tags);
                            $id_insert  = $this->db->insert_id();

                            $data_tags['id_blog']= $id;
                            $data_tags['id_tags']  = $id_insert;

                            $this->db->insert('tb_blog_rel_tags', $data_tags);

                        }
                        
                    }

                    //Delete ketika tidak ada
                    $id_str  = "'";
                    $id_str .= str_replace(",", "','", $tags);
                    $id_str .= "'";

                    $this->db->select('r.id_rel_tags, t.id_tags, t.tags');
                    $this->db->from('tb_blog_rel_tags r');
                    $this->db->join('tb_blog_tags t', 't.id_tags = r.id_tags', 'left');
                    $this->db->where('r.id_blog', $id);
                    $this->db->where_not_in('t.tags', $id_str);
                    $array_delete = $this->db->get()->result_array();

                    foreach ($array_delete as $key_delete) {
                        $this->db->query("DELETE FROM tb_blog_rel_tags WHERE id_rel_tags = '".$key_delete['id_rel_tags']."' ");
                    }


            } else {
                $this->db->query("DELETE FROM tb_blog_rel_tags WHERE id_blog = '".$id."' ");
            }


            if($query) {
                $response['status']  = 3;
                $response['message'] = 'Success, Edit Data.';
            } else {
                $response['status']  = 4;
                $response['message'] = 'Gagal, Edit Data.';
            }
        }

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

                $this->delete_single_image($id);
                $query = $this->db->query("DELETE FROM tb_blog WHERE id_blog = ".$id." ");

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

                    $this->delete_multiple_image($id_str);
                    $query = $this->db->query("DELETE FROM tb_blog WHERE id_blog in (".$id_str.") ");

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

    private function delete_single_image($id)
    {
        $query = $this->db->query("SELECT gambar FROM tb_blog WHERE id_blog = ".$id." ")->row_array();

        if ($query) {
            if ($query['gambar']) {
                if (file_exists(FCPATH.'file_media/file-blog/'.$query['gambar'])) {
                    $filename = explode(".", $query['gambar'])[0];
                    array_map('unlink', glob(FCPATH."file_media/file-blog/$filename.*"));
                }
            }
        }
    }

    private function delete_multiple_image($id)
    {   
        $return = '';
        $query = $this->db->query("SELECT gambar FROM tb_blog WHERE id_blog in (".$id.") ")->result_array();

        foreach ($query as $key) {
            if ($key['gambar']) {
                if (file_exists(FCPATH.'file_media/file-blog/'.$key['gambar'])) {
                    $filename = explode(".", $key['gambar'])[0];
                    array_map('unlink', glob(FCPATH."file_media/file-blog/$filename.*"));
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

