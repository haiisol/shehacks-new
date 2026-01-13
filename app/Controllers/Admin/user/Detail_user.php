<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Detail_user extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    function get_user($id_user_enc) 
    {      
        $this->main_model->check_access('data_user');

        $id_user = decrypt_url($id_user_enc);

        $this->db->select('
            u.*
        ');
        // b.nama_business
        $this->db->from('tb_user u');
        $this->db->where('u.id_user', $id_user);
        $query = $this->db->get()->row_array();

        if ($query['date_create'] == "0000-00-00 00:00:00") {
            $date_create = '-';
        } else {
            $date_create = time_ago_from_3($query['date_create']);
        }

        if ($query['date_update'] == "0000-00-00 00:00:00") {
            $date_update = '-';
        } else {
            $date_update = time_ago_from_3($query['date_update']);
        }

        $get_prov = $this->db->query("SELECT name FROM tb_master_province WHERE id = '".$query['provinsi']."' ")->row_array();
        if ($get_prov) {
            $provinsi = $get_prov['name'];
        } else {
            $provinsi = '-';
        }

        $get_kab = $this->db->query("SELECT name FROM tb_master_regencies WHERE id = '".$query['kabupaten']."' ")->row_array();
        if ($get_kab) {
            $kabupaten = $get_kab['name'];
        } else {
            $kabupaten = '-';
        }

        $get_pendidikan = $this->db->query("SELECT nama FROM tb_master_pendidikan WHERE id_pendidikan = '".$query['pendidikan']."' ")->row_array();
        if ($get_pendidikan) {
            $pendidikan = $get_pendidikan['nama'];
        } else {
            $pendidikan = '-';
        }   

        $get_informasi = $this->db->query("SELECT nama FROM tb_master_dapat_informasi WHERE id_informasi = '".$query['dapat_informasi']."' ")->row_array();
        if ($get_informasi) {
            $dapat_informasi = $get_informasi['nama'];
        } else {
            $dapat_informasi = '-';
        } 

        $response['id_user']            = $query['id_user'];
        $response['kode_user']          = $query['kode_user'];
        $response['nama']               = $query['nama'];
        $response['email']              = $query['email'];
        $response['telp']               = '62'.$query['telp'];
        $response['tanggal_lahir']      = date_ind($query['tanggal_lahir']);
        $response['umur']               = $query['umur'];
        $response['pendidikan']         = $pendidikan;
        $response['dapat_informasi']    = $dapat_informasi;
        $response['nama_startup']       = $query['nama_startup']; 
        $response['provinsi']           = $provinsi;
        $response['kabupaten']          = $kabupaten;
        $response['jumlah_anggota']     = $query['jumlah_anggota'];
        $response['problem_disekitar']  = $query['problem_disekitar'];
        $response['solusi_yang_dibuat'] = $query['solusi_yang_dibuat'];
        $response['date_create']        = $date_create;
        $response['date_update']        = $date_update;
        $response['foto']               = $this->main_model->url_image($query['foto'], 'image-user');

        if ($query['file_pitchdeck']) {
            $response['file_pitchdeck']     = base_url().'file_media/file-user/'.$query['file_pitchdeck'];
        } else {
            $response['file_pitchdeck']     = '';
        }
        

        return $response;
    }

    function detail($id_user_enc)
    {   
        $this->main_model->check_access('data_user');
        $cact = $this->main_model->check_access_action('data_user');

        if ($cact['access_view'] == 'd-none') {
            redirect('404');
        } else {

            $data = $this->main_model->check_access('data_user');
            
            $data['get_user'] = $this->get_user($id_user_enc);

            $data['title']       = 'Detail User';
            $data['description'] = '';
            $data['keywords']    = '';
            $data['page']           = 'admin/user/detail_user';
            $this->load->view('admin/index', $data);
        }
    }

    function detail_belajar($id_user_enc)
    {   
        $this->main_model->check_access('data_user');
        $cact = $this->main_model->check_access_action('data_user');

        if ($cact['access_view'] == 'd-none') {
            redirect('404');
        } else {   
            $data = $this->main_model->check_access('data_user');
            
            $data['get_user']       = $this->get_user($id_user_enc);
            $data['id_user']        = decrypt_url($id_user_enc);

            $data['modul']   = $this->db->query("SELECT p.id_modul, m.modul
                                            FROM edu_modul_user_progress p
                                            LEFT JOIN edu_modul m ON p.id_modul = m.id_modul
                                            WHERE p.id_user = '".$data['id_user']."'
                                            AND m.status_delete = 0")->result_array();


            $data['title']          = 'Detail User Pembelajarn';
            $data['description']    = '';
            $data['keywords']       = '';
            $data['page']           = 'admin/user/detail_belajar';
            $this->load->view('admin/index', $data);
        }
    }


}