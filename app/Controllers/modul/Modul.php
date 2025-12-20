<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Modul extends CI_Controller {
	
    public function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_front();
        $this->main_model->unset_log_redirect();
    }
    
    public function index()
	{
        
    }

	function detail_modul($slug, $id_modul_enc)
	{
        $id_modul  = decrypt_url($id_modul_enc);
        $get_modul = $this->db->query("SELECT id_modul, modul FROM edu_modul WHERE id_modul = ".$id_modul." ")->row_array();

        if ($get_modul) {
            
            $data['id_modul_enc'] = $id_modul_enc;

            $data['title']       = $get_modul['modul'];
            $data['description'] = '';
            $data['keywords']    = '';
            $data['page']        = 'modul/modul_detail';
            $this->load->view('index', $data);   
        } 
        else {
            redirect('');
        }
    }

    function fetch_data_modul_detail()
	{
        $id_modul  = decrypt_url($this->input->post('id_modul_enc'));

        $sql = "
            SELECT 
            m.*, 
            u.photo, 
            u.nama_admin,
            m.kategori as kategori
            FROM edu_modul m 
            LEFT JOIN tb_admin_user u ON u.id_admin = m.user_post 
            WHERE m.id_modul = ".$id_modul." ";

        $get_modul = $this->db->query($sql)->row_array();
        
        $get_quiz  = $this->db->query("SELECT COUNT(id_quiz) as total FROM quiz WHERE id_modul = ".$id_modul." AND status_delete = 0 ")->row_array();
        
        $total_video = $this->db->query("SELECT COUNT(id_video) as total FROM edu_video WHERE id_modul = ".$id_modul." AND status_delete = 0 ")->row_array();
        $get_video   = $this->db->query("SELECT id_video, judul, durasi FROM edu_video WHERE id_modul = ".$id_modul." AND status_delete = 0 ")->result_array();
        
        $data_video = array();
        
        foreach ($get_video as $key_video) {
            
            $row_video['id_video']     = $key_video['id_video'];
            $row_video['judul_video']  = $key_video['judul'];
            $row_video['durasi_video'] = $key_video['durasi'];
            
            array_push($data_video, $row_video);
        }

        $response['id_modul']        = $get_modul['id_modul'];
        $response['url_detail']      = url_modul_detail($get_modul['modul'], $get_modul['id_modul']);
        $response['url_edukasi']     = url_modul_edukasi($get_modul['modul'], $get_modul['id_modul']);
        $response['modul']           = $get_modul['modul'];
        $response['deskripsi_modul'] = $get_modul['deskripsi_modul'];
        $response['date_create']     = $get_modul['date_create'];
        $response['cover']           = $this->main_model->url_image($get_modul['cover'], 'file-modul');
        $response['total_video']     = $total_video['total'];
        $response['nama_admin']      = $get_modul['nama_admin'];
        $response['photo_admin']     = $this->main_model->url_image_admin($get_modul['photo'], 'image-admin');
        $response['package_name']    = $get_modul['kategori'];
        $response['pretest']         = $get_quiz['total'];
        $response['posttest']        = $get_quiz['total'];
        $response['sertifikat']      = 1;
        $response['data_video']      = $data_video;

        json_response($response);
    }
    
    
    function get_data_modul($id_modul) 
    {
        return $this->db->query("
            SELECT 
            m.*
            FROM edu_modul m 
            WHERE id_modul = '".$id_modul."'
        ")->row();
    }

    function fetch_data_modul($slug, $id_modul)
    {
        $dc_id_modul = base64_decode($id_modul);

        $this->db->query("UPDATE edu_modul SET views=views + 1 WHERE id_modul = '".$dc_id_modul."'");
        
        $data['id_user']     = key_auth();
        $data['modul'] = $this->db->query("SELECT m.* , k.kategori 
                                           FROM edu_modul m 
                                           LEFT JOIN edu_kategori k ON m.id_kategori = k.id_kategori 
                                           WHERE m.id_modul = '".$dc_id_modul."' 
                                        ")->row_array();

        $data['ulasan'] = $this->db->query("SELECT ratting_modul.*,  user_apps.nama_depan 
                                            FROM ratting_modul
                                            LEFT JOIN user_apps ON ratting_modul.id_user = user_apps.id_user
                                            WHERE ratting_modul.id_modul='".$dc_id_modul."' AND ratting_modul.tampilkan_ulasan = 1 ")->result_array();
        $data['modul_bestseller']  = $this->db->query("SELECT edu_modul.*, admin_user.nama_admin   
                                     FROM request_order 
                                     LEFT JOIN edu_modul ON request_order.id_modul = edu_modul.id_modul 
                                     LEFT JOIN admin_user ON admin_user.id_admin = edu_modul.user_post 
                                     WHERE (request_order.id_privilage = 2 OR request_order.id_privilage = 3)
                                     AND edu_modul.status_delete = 0 
                                     AND request_order.status = 'Approved'
                                     AND edu_modul.id_modul IS NOT NULL
                                     GROUP BY request_order.id_modul
                                     ORDER BY COUNT(request_order.id_modul) DESC")->result_array();

        $data['data_flashsale'] = $this->main_model->get_data_flashsale_modul($dc_id_modul);
            
        $get_modul = $this->db->query("SELECT * FROM edu_modul WHERE id_modul='".$dc_id_modul."'")->row();

        $data['title']       = $get_modul->modul;
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']        = 'front/modul/modul_detail';
        $this->load->view('index', $data);
    }

}