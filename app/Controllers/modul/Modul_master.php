<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Modul_master extends CI_Controller {
	
    public function __construct(){
        parent::__construct();
        $this->main_model->logged_in_front();
    }
    
	function pelajari($slug, $id_modul_enc)
	{
        $id_modul = decrypt_url($id_modul_enc);

        $get_modul = $this->db->query("SELECT id_modul, modul FROM edu_modul m WHERE id_modul = ".$id_modul." ")->row_array();

        if ($get_modul) {
            
            $data['id_modul']     = $id_modul;
            
            $data['title']       = ucwords($get_modul['modul']);
            $data['description'] = ucwords($get_modul['modul']);
            $data['keywords']    = '';
            $data['page']        = 'modul/master_modul';
            $this->load->view('index', $data);
        } 
        else {
            redirect('');
        }
    }

    function fetch_data_info() 
    {
        $id_user = key_auth();

        $get_user = $this->db->query("
            SELECT u.nama, u.email, u.kategori_user as package_name  
            FROM tb_user u  
            WHERE u.id_user = ".$id_user." ")->row_array();
        
      
        $photo_user = base_url().'assets/backoffice/images/no-image-user.png';

        $response['user_photo']   = $photo_user;
        $response['user_name']    = $get_user['nama'];
        $response['user_email']   = $get_user['email'];
        $response['user_package'] = $get_user['package_name'];

        json_response($response);
    }


    function fetch_data_video()
    {   
        $id_modul = $this->input->post('id_modul');
        $id_param = $this->input->post('id');
        $param    = $this->input->post('param');

        $query = $this->db->query("SELECT * FROM edu_video WHERE id_video = ".$id_param." AND id_modul = ".$id_modul." AND status_delete = 0")->row_array();

        if ($query['jenis'] == 'url') {
            $url_video = 'https://www.youtube.com/embed/'.$query['url'].'?autoplay=1'; 
        } else {
            $url_video = base_url().'file_media/file-modul/video/'.$query['file_video'];
        }

        $response['id_video']  = $query['id_video'];
        $response['judul']     = $query['judul'];
        $response['jenis']     = $query['jenis'];
        $response['url_video'] = $url_video;

        if($query) {
            $response['status']  = 1;
            $response['message'] = '';
            
            $this->submit_progress($id_modul, $id_param, $param);
        } 
        else {
            $response['status']  = 0;
            $response['message'] = '';
        }

        json_response($response);
    }

    function fetch_data_quiz()
    {
        $this->session->unset_userdata('offset');
        $this->session->unset_userdata('skor');

        $id_modul = $this->input->post('id_modul');
        $param    = $this->input->post('param');

        $limit  = 1;
        $offset = 0;

        $get_quiz = $this->db->query("SELECT id_quiz, pertanyaan FROM quiz WHERE id_modul = ".$id_modul." AND status_delete = 0 LIMIT ".$limit." OFFSET ".$offset." ")->row_array();
        
        $get_quiz_selection = $this->db->query("SELECT * FROM quiz_jawaban WHERE id_quiz = ".$get_quiz['id_quiz']." AND status_delete = 0 ")->result_array();

        $quiz_selection = array();
        
        foreach ($get_quiz_selection as $key) {
            $row['id_jawaban'] = $key['id_jawaban'];
            $row['jawaban']    = $key['jawaban'];

            array_push($quiz_selection, $row);
        }

        $this->submit_progress($id_modul, '', $param);

        $data['id_quiz']        = $get_quiz['id_quiz'];
        $data['quiz_question']  = $get_quiz['pertanyaan'];
        $data['quiz_selection'] = $quiz_selection;
        $data['quiz_param']     = $param;

        $response['status']     = 1;
        $response['message']    = '';
        $response['data']       = $data;

        json_response($response);
    }

    function submit_quiz()
    {   
        $skor       = $this->session->userdata('skor');
        $id_modul   = $this->input->post('id_modul');
        $id_user    = $this->input->post('id_user');
        $id_quiz    = $this->input->post('id_quiz');
        $id_jawaban = $this->input->post('id_jawaban');
        $quiz_param = $this->input->post('quiz_param');
        
        if ($skor) { 
            $id_skor = $skor;
        } 
        else { 
            $data_skor['id_user']     = $id_user;
            $data_skor['id_modul']    = $id_modul;
            $data_skor['jenis_quiz']  = $quiz_param;
            $data_skor['tanggal']     = date('Y-m-d H:i:s');

            $this->db->insert('quiz_skor_user', $data_skor);
            $id_skor = $this->db->insert_id();

            $session = array('skor' => $id_skor);
            $this->session->set_userdata($session);
        }
            
        
        if ($id_jawaban) {

            $get_quiz_jawaban = $this->db->query("SELECT jawaban FROM quiz_jawaban WHERE id_jawaban = ".$id_jawaban." AND status_delete = 0")->row_array();
            $jawaban = $get_quiz_jawaban['jawaban'];

            $cek_jawaban_benar = $this->db->query("SELECT answer FROM quiz WHERE id_quiz = ".$id_quiz." ")->row_array();

            if ($cek_jawaban_benar['answer'] == $id_jawaban) {
                $status_jawaban = 'TRUE';
            } else {
                $status_jawaban = 'FALSE';
            }
        } else {
            $jawaban        = '';
            $status_jawaban = 'FALSE';
        }
        
        $data['id_skor']        = $id_skor;
        $data['id_quiz']        = $id_quiz;
        $data['id_jawaban']     = $id_jawaban;
        $data['jawaban']        = $jawaban;
        $data['status_jawaban'] = $status_jawaban;

        $query = $this->db->insert('quiz_jawaban_user', $data);


        if($query) {

            $count_quiz = $this->db->query("SELECT count(id_quiz) as total FROM quiz WHERE id_modul = ".$id_modul." AND status_delete = 0")->row_array();

            $baris = 0;
            if ($this->session->userdata('offset')) {
                $baris = $this->session->userdata('offset');
            }

            $cek_offset = $baris+1;

            if ($cek_offset == $count_quiz['total']) {
                $response['status']  = 1;
                $response['message'] = '';
                $response['data']    = $this->fetch_data_quiz_skor($id_modul, $id_user, $quiz_param);

            } else {
                $response['status']  = 2;
                $response['message'] = '';
                $response['data']   = $this->fetch_data_quiz_next($id_modul, $quiz_param);
            }
        } 
        else {
            $response['status']  = 0;
            $response['message'] = '';
        }
        
        json_response($response);
    }

    function fetch_data_quiz_next($id_modul, $quiz_param)
    {
        $limit  = 1;
        $baris  = $this->session->userdata('offset');

        if ($baris) { $offset = $baris+1; } else { $offset = 1; }
        
        $session['offset'] = $offset;
        $this->session->set_userdata($session);

        $get_quiz = $this->db->query("SELECT id_quiz, pertanyaan FROM quiz WHERE id_modul = ".$id_modul." AND status_delete = 0 LIMIT ".$limit." OFFSET ".$offset." ")->row_array();

        $get_quiz_selection = $this->db->query("SELECT * FROM quiz_jawaban WHERE id_quiz = ".$get_quiz['id_quiz']." AND status_delete = 0 ")->result_array();

        $quiz_selection = array();
        
        foreach ($get_quiz_selection as $key) {
            $row['id_jawaban'] = $key['id_jawaban'];
            $row['jawaban']    = $key['jawaban'];

            array_push($quiz_selection, $row);
        }

        $response['id_quiz']        = $get_quiz['id_quiz'];
        $response['quiz_question']  = $get_quiz['pertanyaan'];
        $response['quiz_selection'] = $quiz_selection;
        $response['quiz_param']     = $quiz_param;

        return $response;
    }

    function fetch_data_quiz_skor($id_modul, $id_user, $quiz_param)
    { 
        $query = $this->db->query("SELECT * FROM quiz_skor_user WHERE id_user = ".$id_user." AND id_modul = ".$id_modul." ORDER BY id_skor DESC LIMIT 1")->row_array();

        $total = $this->db->query("SELECT count(id_jawaban_user) as quiz FROM quiz_jawaban_user WHERE id_skor = ".$query['id_skor']." ")->row_array();
        $benar = $this->db->query("SELECT count(id_jawaban_user) as total FROM quiz_jawaban_user WHERE id_skor = ".$query['id_skor']." AND status_jawaban = 'TRUE' ")->row_array();
        $salah = $this->db->query("SELECT count(id_jawaban_user) as total FROM quiz_jawaban_user WHERE id_skor = ".$query['id_skor']." AND status_jawaban = 'FALSE' ")->row_array();

        $data['tanggal']    = date('d F Y H:i', strtotime($query['tanggal']));
        $data['benar']      = $benar['total'];
        $data['salah']      = $salah['total'];
        $data['skor']       = round($benar['total']/$total['quiz']*100);
        $data['id_modul']   = $id_modul;
        $data['param']      = $quiz_param;

        $data_skor['skor'] = $data['skor'];
        $this->main_model->update_data('quiz_skor_user', $data_skor, 'id_skor', $query['id_skor']);

        if ($quiz_param == 'POST - TEST') {

            $cek_progress = $this->db->query("SELECT id FROM edu_modul_user_progress 
                WHERE id_user = ".$id_user." AND id_modul = ".$id_modul." 
                ORDER BY id DESC LIMIT 1")->row_array();

            if ($cek_progress) {
                if ($data['skor'] >= '60') {
                    $data_sertifikat['date_sertifikat'] = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d');
                    $this->main_model->update_data('edu_modul_user_progress', $data_sertifikat, 'id', $cek_progress['id']);
                }
            }

        }
        

        return $data;
    }



    function modul_get_data()
    {   
        $id    = $this->input->post('id');
        $query = $this->db->query("SELECT m.* FROM edu_modul m WHERE m.id_modul = '".$id."'  ")->row_array();

        $img_url = base_url().'/assets/files/modul/'.$query['cover'];

        $data = ''; 
        if ($query['cover'] != '') {
            $data   .= '<img class="mb-2 mt-2" src="'.$img_url.'" alt="Cover Modul">';
        }

        $data   .= '<div class="separator"></div><p class="mt-3">'.$query['deskripsi_modul'].'</p>';

        if($query) {
            $response['success']    = 1;
            $response['data']       = $data;
        } else {
            $response['success']    = 0;
            $response['data']       = $data;
        }

        json_response($response);
    }

    function check_learn_progress()
    {
        $id_modul   = $this->input->post('id_modul');
        $id_user    = $this->input->post('id_user');
        $data_skor  = "";

        $get_modul_progress_cek_complete = $this->db->query("
            SELECT id FROM edu_modul_progress e
            WHERE e.id_user = ".$id_user." 
            AND e.id_modul = ".$id_modul." 
            AND e.id_quiz != 0
            AND e.quiz_pre != 0
            AND e.quiz_post !=0
            ORDER BY id DESC 
            LIMIT 1 ")->row_array();

        $get_modul_progress = $this->db->query("
            SELECT e.* 
            FROM edu_modul_progress e 
            LEFT JOIN edu_modul m ON m.id_modul = e.id_modul
            WHERE e.id_user = ".$id_user." 
            AND e.id_modul = ".$id_modul." 
            AND m.status_delete = 0
            ORDER BY id DESC 
            LIMIT 1 ")->row_array();

        $cek_total_progress_video = $this->db->query("SELECT count(e.id) as total 
                FROM edu_modul_progress e 
                LEFT JOIN edu_modul m ON e.id_modul = m.id_modul
                WHERE e.id_user = ".$id_user." 
                AND m.id_modul = ".$id_modul."
                AND m.status_delete = 0 
                AND e.id_video != 0 ")->row_array();

        $total_i_video = $cek_total_progress_video['total'];
        
        if (empty($get_modul_progress_cek_complete)) {
            if (empty($get_modul_progress)) {
                $i_post     = 0;
                $status   = 0;
                $id_param = '';
                $param    = 'PRE - TEST';
            } 
            else {
                $status = 1;

                if ($get_modul_progress['quiz_pre'] == 1 AND $get_modul_progress['quiz_post'] == 1) {
                    $param    = 'POST - TEST';
                    $id_param = $id_modul;
                    $i_post   = 0;
                } 
                else {
                    if ($get_modul_progress['id_video'] != 0) {

                        $cek_total_progress_video = $this->db->query("SELECT count(e.id) as total 
                                FROM edu_modul_progress e 
                                LEFT JOIN edu_modul m ON e.id_modul = m.id_modul
                                WHERE e.id_user = ".$id_user." 
                                AND m.id_modul = ".$id_modul."
                                AND m.status_delete = 0 
                                AND e.id_video != 0 ")->row_array();

                        $total_i_video = $cek_total_progress_video['total'];

                        $param    = 'VIDEO';
                        $id_param = $get_modul_progress['id_video'];
                        $i_post   = $total_i_video;
                    }
                    else if ($get_modul_progress['id_quiz'] != 0) {

                        if ($get_modul_progress['quiz_pre'] != 0) {
                            $param    = 'PRE - TEST';
                            $id_param = $id_modul;
                            $i_post   = 0;

                        } 
                        else {
                            $i_post     = 0;
                            $param      = 'POST - TEST';
                            $id_param   = $id_modul;
                        }
                    }
                }
        
            }
        } else {

            $get_modul_progress_cek_complete_quiz_post = $this->db->query("
                SELECT id_skor  
                FROM quiz_skor_user e 
                WHERE e.id_user = ".$id_user." 
                AND e.id_modul = ".$id_modul." 
                AND e.jenis_quiz = 'POST - TEST'
                AND e.skor > 60
                ORDER BY id_skor DESC
                LIMIT 1 ")->row_array();

            $get_modul_video = $this->db->query("
                SELECT count(id_video) as total 
                FROM edu_video
                WHERE id_modul = ".$id_modul." 
                AND status_delete = 0 ")->row_array();

            $status     = 1;
            $i_post     = $get_modul_video['total']+1;

            $param      = 'POST - TEST';
            $id_param   = $id_modul;

            if ($get_modul_progress_cek_complete_quiz_post) {
                $data_skor  = $this->fetch_data_quiz_skor($id_modul, $id_user, 'POST - TEST');
            } 

        }

        $response['status']   = $status;
        $response['id_param'] = $id_param;
        $response['param']    = $param;
        $response['i_post']   = $i_post;
        $response['data_skor']  = $data_skor;

        json_response($response);
    }

    function cek_trigger_menu()
    { 
        $param      = $this->input->post('param');
        $id_modul   = $this->input->post('id_modul');
        $id         = $this->input->post('id');
        $i_post     = 0;
        $get_video = $this->db->query("SELECT COUNT(id_video) as total FROM edu_video v WHERE v.id_modul = ".$id_modul." AND v.status_delete = 0 ")->row_array();
        if ($param == 'VIDEO') {
            $get_video_row = $this->db->query("SELECT id_video FROM edu_video v WHERE v.id_modul = ".$id_modul." AND v.status_delete = 0 ")->result_array();
            $row = 0;
            foreach ($get_video_row as $key) {
                $row++;
                if ($key['id_video'] == $id) {
                    $i_post         = $row;
                } 
            } 
        } else {
            $total_video    = $get_video['total'];
            $i_post         = $total_video + 1;
        }

        $response['status']    = 1;
        $response['success']   = 'success';
        $response['i_post']    = $i_post;

        json_response($response);
        
    }

    function cek_data_modul()
    {    
        $i_post   = $this->input->post('i_post');
        $id_modul = $this->input->post('id_modul');

        $get_modul  = $this->db->query("SELECT m.* FROM edu_modul m WHERE m.id_modul = ".$id_modul." AND m.status_delete = 0 ")->result_array();

        $array = array();

        // PRE TEST
        $get_quiz_pre = $this->db->query("SELECT q.* FROM quiz q WHERE q.id_modul = ".$id_modul." AND q.status_delete = 0 ")->result_array();

        if ($get_quiz_pre) {

            $row1['id_param']    = '';
            $row1['menu_active'] = 'menu-qpr-'.$id_modul;
            $row1['param']       = 'PRE - TEST';

            array_push($array, $row1);
        }

        // VIDEO
        $get_video = $this->db->query("SELECT v.* FROM edu_video v WHERE v.id_modul = ".$id_modul." AND v.status_delete = 0 ")->result_array();

        if ($get_quiz_pre) {
            foreach ($get_video as $key_video) { 

                $row2['id_param']    = $key_video['id_video'];
                $row2['menu_active'] = 'menu-vid'.$key_video['id_video'].'-'.$id_modul;
                $row2['param']       = 'VIDEO';

                array_push($array, $row2);
            }
        }

        // POST TEST
        $get_quiz_post = $this->db->query("SELECT q.* FROM quiz q WHERE q.id_modul = ".$id_modul." AND q.status_delete = 0 ")->result_array();

        if ($get_quiz_post) {

            $row3['id_param']    = '';
            $row3['menu_active'] = 'menu-qpo-'.$id_modul;
            $row3['param']       = 'POST - TEST';

            array_push($array, $row3);
        }


        if ($i_post == 0) {
            $i_plus   = $i_post+1;
            $i_array  = $array[1];
        } else {
            $i_plus   = $i_post+1;
            $i_array  = $array[$i_post+1];
        }
        
        $response['status']    = 1;
        $response['data']      = $array;
        $response['i_post']    = $i_plus;
        $response['data_post'] = $i_array;

        json_response($response);
    }

    function submit_progress($id_modul, $id_param , $param)
    {
        $id_user = key_auth();
        
        if ($param == 'VIDEO') 
        { 
            $get_edu_modul_progress = $this->db->query("
                SELECT * 
                FROM edu_modul_progress 
                WHERE id_user = ".$id_user." 
                AND id_modul = ".$id_modul." 
                AND id_video = ".$id_param." ")->row_array();    

            if (empty($get_edu_modul_progress)) {
                
                $datav['id_user']  = $id_user;
                $datav['id_modul'] = $id_modul;
                $datav['id_video'] = $id_param;
                $datav['date_create'] = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');
                $this->db->insert('edu_modul_progress', $datav);
            }
        }
        else 
        {
            $get_edu_modul_progress2 = $this->db->query("
                SELECT * 
                FROM edu_modul_progress 
                WHERE id_user = ".$id_user." 
                AND id_modul = ".$id_modul." 
                AND id_quiz = 1 ")->row_array();

            if (empty($get_edu_modul_progress2)) {

                $dataq['id_user']  = $id_user;
                $dataq['id_modul'] = $id_modul;
                $dataq['id_quiz']  = 1;
                $dataq['quiz_pre'] = 1;
                $dataq['date_create'] = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');
                $this->db->insert('edu_modul_progress', $dataq);
            } 
            else {
                if ($param == 'PRE - TEST') {
                    $datapr['quiz_pre'] = 1;
                    $datapr['date_update'] = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');
                    $this->main_model->update_data('edu_modul_progress', $datapr, 'id', $get_edu_modul_progress2['id']);
                }
                else if ($param == 'POST - TEST') {
                    $datapo['quiz_post'] = 1;
                    $datapo['date_update'] = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');
                    $this->main_model->update_data('edu_modul_progress', $datapo, 'id', $get_edu_modul_progress2['id']);
                }

            }
        }

        // Update progress
        $get_video = $this->db->query("SELECT COUNT(id_video) as total FROM edu_video WHERE id_modul = ".$id_modul." AND status_delete = 0 ")->row();

        $get_modul = $this->db->query("SELECT * FROM edu_modul WHERE id_modul = ".$id_modul." AND status_delete = 0 ")->row();

        if ($get_modul->status_quiz == 1) {
            $quiz = 1;
        } else {
            $quiz = 0;
        }

        $get_video_progress = $this->db->query("
            SELECT COUNT(id) as total 
            FROM edu_modul_progress 
            LEFT JOIN edu_video ON edu_modul_progress.id_video = edu_video.id_video
            WHERE edu_modul_progress.id_user = ".$id_user."
            AND edu_modul_progress.id_modul = ".$id_modul."
            AND edu_video.status_delete = 0 ")->row();

        $total_param_modul = $get_video->total+$quiz; 
        $total_progress    = $get_video_progress->total+$quiz; 
        $total             = $total_progress/$total_param_modul*100;

        $cek_user_progress = $this->db->query("SELECT * FROM edu_modul_user_progress WHERE id_modul = ".$id_modul." AND id_user = ".$id_user." ")->row();

        if ($cek_user_progress) 
        {
            $cekid                       = $cek_user_progress->id;
            $data_progress['persentase'] = $total;
            $data_progress['date_update'] = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');


            $this->main_model->update_data('edu_modul_user_progress', $data_progress, 'id', $cekid);
        } 
        else 
        {

            $data_progress['id_modul']   = $id_modul;
            $data_progress['id_user']    = $id_user;
            $data_progress['persentase'] = $total;
            $data_progress['date_create']= date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');

            $this->db->insert('edu_modul_user_progress', $data_progress);
        }
    }   

    function unset_quiz()
    {   
        $this->session->unset_userdata('offset');
        $this->session->unset_userdata('skor');
    }
    

    function clear() 
    {
        $this->db->query("DELETE FROM quiz_jawaban_user");
        $this->db->query("DELETE FROM quiz_skor_user");
        $this->db->query("DELETE FROM edu_modul_progress");
        $this->db->query("DELETE FROM edu_modul_user_progress");
    }

}