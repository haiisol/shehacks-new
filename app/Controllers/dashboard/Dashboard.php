<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->main_model->logged_in_front();
    }
    
    public function index()
    {   
        $id_user             = key_auth();
        $data['data_user']   = $this->db->query("SELECT kategori_user, nama, pp_nama FROM tb_user WHERE id_user=".$id_user." ")->row_array();
        $data['title']       = '';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']        = 'dashboard/dashboard_index';
        $this->load->view('index', $data);
    }

    function get_query_user() 
    {
        $id_user = key_auth();
        $query   = $this->db->query("SELECT * FROM tb_user WHERE id_user=".$id_user." ")->row_array();

        return $query;
    }

    // ---------------------------  popup-event ---------------------------
    function get_modal_event() 
    {   
        $channel_check = $this->session->userdata('channel_check');
        
        $id_user = key_auth();
        $query   = $this->db->query("SELECT channel FROM tb_user WHERE id_user = ".$id_user." AND channel LIKE '%2024%' ")->row_array();

        if ($query) {
            $response['status']  = 0;
            json_response($response);

        } else {

            if ($channel_check) {
                $response['status']  = 0;
                json_response($response);
            } else {
                $response['status']  = 1;
                json_response($response);
            }

        }
        
        return $query;
    }

    function close_modal_event() 
    {   
        $session['channel_check'] = TRUE;
        $this->session->set_userdata($session);
        
        $response['status'] = 1;
        json_response($response);
    }

    function generate_channel() 
    {
        $id_user  = key_auth();
        $get_data = $this->db->query("SELECT channel FROM tb_user WHERE id_user = ".$id_user." ")->row_array();

        if ($get_data) {

            $channel            = $get_data['channel'];
            $akhir              = $channel.', 2025';
            $data['channel']    = $akhir;

            $query = $this->main_model->update_data('tb_user', $data, 'id_user', $id_user);

            if ($query) {
                $response['status']     = 1;
                $response['redirect']   = base_url().'dashboard?profile';
                json_response($response);
            } else {
                $response['status']  = 0;
                json_response($response);
            }
            

        } else {
            $response['status']  = 0;
            json_response($response);
        }

        // return $query;
    }

    // ---------------------------  end popup-event ---------------------------

    function get_page()
    {
        $param_pg = $this->input->get('param_pg');
        
        // $data['user'] = $this->get_query_user();
        $data['id_user'] = encrypt_url(key_auth());

        if ($param_pg == 'dashboard') {
            $response['param_pg']    = $param_pg;
            $response['url']         = 'dashboard';

            $response['title']       = 'Dashboard';
            $response['description'] = '';
            $response['keywords']    = '';
            $response['page']        = $this->load->view('dashboard/pg_dashboard', $data, TRUE);
            json_response($response);
        } 

        elseif ($param_pg == 'profile') {
            $response['param_pg']    = $param_pg;
            $response['url']         = 'dashboard?profile';

            $response['title']       = 'Profile';
            $response['description'] = '';
            $response['keywords']    = '';
            $response['page']        = $this->load->view('dashboard/pg_profile', $data, TRUE);
            json_response($response);
        }

        elseif ($param_pg == 'pilot_project') {
            $response['param_pg']    = $param_pg;
            $response['url']         = 'dashboard?pilot_project';

            $response['title']       = 'Pilot Project';
            $response['description'] = '';
            $response['keywords']    = '';
            $response['page']        = $this->load->view('dashboard/pg_pilot_project', $data, TRUE);
            json_response($response);
        }

        elseif ($param_pg == 'password') {
            $response['param_pg']    = $param_pg;
            $response['url']         = 'dashboard?password';

            $response['title']       = 'Change Password';
            $response['description'] = '';
            $response['keywords']    = '';
            $response['page']        = $this->load->view('dashboard/pg_password', $data, TRUE);
            json_response($response);
        }
    }

    function fecth_data_dashboard()
    {
        $id_user = key_auth();

        $sql = "
            SELECT u.nama, u.email 
            FROM tb_user u 
            WHERE u.id_user = ".$id_user." ";

        $query = $this->db->query($sql)->row_array();

        $response['nama']   = $query['nama'];
        $response['email']  = $query['email'];

        json_response($response);
    }


// --------------------------- profile ---------------------------
    function fetch_data_profile() 
    {
        $id_user = key_auth();

        $sql = "
            SELECT 
            u.* 
            FROM tb_user u 
            WHERE u.id_user = ".$id_user." ";

        $query = $this->db->query($sql)->row_array();

        $response['token']              = $query['token'];
        $response['kategori_user']      = $query['kategori_user'];
        $response['nama']               = $query['nama'];
        $response['telp']               = $query['telp'];
        $response['email']              = $query['email'];
        $response['tanggal_lahir']      = DateTime::createFromFormat('Y-m-d', trim($query['tanggal_lahir']))->format('d/m/Y');
        $response['pendidikan']         = $query['pendidikan'];
        $response['jenis_kelamin']      = $query['jenis_kelamin'];
        $response['dapat_informasi']    = $query['dapat_informasi'];
        $response['provinsi']           = $query['provinsi'];
        $response['kabupaten']          = $query['kabupaten'];
        $response['problem_disekitar']  = $query['problem_disekitar'];
        $response['solusi_yang_dibuat'] = $query['solusi_yang_dibuat'];
        $response['nama_komunitas']     = $query['nama_komunitas'];
        $response['jumlah_anggota_komunitas'] = $query['jumlah_anggota_komunitas'];
        $response['jabatan_komunitas']  = $query['jabatan_komunitas'];
        $response['akun_komunitas']     = $query['akun_komunitas'];
    
        $response['nama_startup']           = $query['nama_startup'];
        $response['jumlah_anggota']         = $query['jumlah_anggota'];

        $response['pp_background_masalah']  = $query['pp_background_masalah'];
        $response['pp_nama']                = $query['pp_nama'];
        $response['pp_deskripsi']           = $query['pp_deskripsi'];
        $response['pp_timeline']            = $query['pp_timeline'];
        $response['pp_target']              = $query['pp_target'];
        $response['pp_potential_partner']   = $query['pp_potential_partner'];
        $response['pp_kebutuhan_ahli']      = $query['pp_kebutuhan_ahli'];
        $response['pp_pembeda']             = $query['pp_pembeda'];

        $response['file_pitchdeck']         = $query['file_pitchdeck'];
        $response['file_profile_komunitas'] = $query['file_profile_komunitas'];
        $response['file_analisa_skorlife']  = $query['file_analisa_skorlife'];
        $response['file_pengajuan_kegiatan']= $query['file_pengajuan_kegiatan'];
        $response['url_file_pitchdeck']     = base_url().'file_media/file-user/'.$query['file_pitchdeck'];
        $response['url_file_profile_komunitas'] = base_url().'file_media/file-user/'.$query['file_profile_komunitas'];
        $response['url_file_analisa_skorlife']  = base_url().'file_media/file-user/'.$query['file_analisa_skorlife'];
        $response['url_file_pengajuan_kegiatan']= base_url().'file_media/file-user/'.$query['file_pengajuan_kegiatan'];

        json_response($response);
    }

    function cek_phone()
    {   
        $row = array(
            "value"     => $this->input->post('value', TRUE),
        ); 

        $this->form_validation->set_data($row);
        $this->form_validation->set_rules('value', 'value', 'trim|required|numeric');

        if ( $this->form_validation->run() === false ) {
            $response['status']  = 0;
            $response['message'] = validation_errors();
            json_response($response);
            return;
        }
            
        $id_user = key_auth();
        $value   = $this->input->post('value', TRUE);
        $value   = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

        $this->db->select('telp');
        $this->db->where('id_user', $id_user, TRUE);
        $query = $this->db->get('tb_user')->row_array();
        
        if ($query['telp'] == $value) {
            $response['status']  = 1;
            $response['message'] = '';
        } 
        else {
            $this->db->select('id_user');
            $this->db->where('telp', $value, TRUE);
            $cek_telp = $this->db->get('tb_user')->result_array();

            if ($cek_telp) {
                $response['status']  = 0;
                $response['message'] = '';
            } else {
                $response['status']  = 1;
                $response['message'] = '';
            }
        }

        json_response($response);
    }

    function cek_file_pitchdeck()
    {
        $id_user = key_auth();

        $query = $this->db->query("SELECT file_pitchdeck FROM tb_user WHERE id_user = ".$id_user." ")->row_array();
        
        if ($query['file_pitchdeck']) {
            $response['status']  = 0;
            $response['message'] = '';
        } else {
            $response['status']  = 1;
            $response['message'] = '';
        }

        json_response($response);
    }

    function cek_file_pengajuan_kegiatan()
    {
        $id_user = key_auth();

        $query = $this->db->query("SELECT file_pengajuan_kegiatan FROM tb_user WHERE id_user = ".$id_user." ")->row_array();
        
        if ($query['file_pengajuan_kegiatan']) {
            $response['status']  = 0;
            $response['message'] = '';
        } else {
            $response['status']  = 1;
            $response['message'] = '';
        }

        json_response($response);
    }

    function post_update_profile()
    {
        $id_user = key_auth();

        $this->db->select('id_user');
        $this->db->from('tb_user');
        $this->db->where('id_user', $id_user);
        $this->db->where('token', $this->input->post('token_user'));
        $query_cek = $this->db->get()->row_array();

        if ($query_cek) {

            $tanggal_lahir      = sanitize_input($this->input->post('tanggal_lahir'));
            $nama               = sanitize_input($this->input->post('nama'));
            $telp               = sanitize_input($this->input->post('telp'));
            $pendidikan         = sanitize_input($this->input->post('pendidikan'));
            $dapat_informasi    = sanitize_input($this->input->post('dapat_informasi'));
            $jenis_kelamin      = sanitize_input($this->input->post('jenis_kelamin'));
            $provinsi           = sanitize_input($this->input->post('provinsi'));

            $this->load->library('form_validation');
            $row = array(
                "tanggal_lahir"     => $tanggal_lahir,
                "nama"              => $nama,
                "telp"              => $telp,
                "pendidikan"        => $pendidikan,
                "dapat_informasi"   => $dapat_informasi,
                "jenis_kelamin"     => $jenis_kelamin,
                "provinsi"          => $provinsi,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('tanggal_lahir', 'tanggal_lahir', 'trim|required|callback_sanitize_input');
            $this->form_validation->set_rules('nama', 'nama', 'trim|required|callback_sanitize_input');
            $this->form_validation->set_rules('telp', 'telp', 'trim|required|callback_sanitize_input');
            $this->form_validation->set_rules('pendidikan', 'pendidikan', 'trim|required|callback_sanitize_input');
            $this->form_validation->set_rules('dapat_informasi', 'dapat_informasi', 'trim|required|callback_sanitize_input');
            $this->form_validation->set_rules('jenis_kelamin', 'jenis_kelamin', 'trim|required|callback_sanitize_input');
            $this->form_validation->set_rules('provinsi', 'provinsi', 'trim|required|numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $tgl_lahir = DateTime::createFromFormat('d/m/Y', trim($tanggal_lahir))->format('Y-m-d');

            $data['nama']               = $nama;
            $data['telp']               = $telp;
            $data['tanggal_lahir']      = $tgl_lahir ;
            $data['umur']               = $this->main_model->get_umur($tgl_lahir);
            $data['pendidikan']         = $pendidikan;
            $data['jenis_kelamin']      = $jenis_kelamin;
            $data['dapat_informasi']    = $dapat_informasi;
            $data['provinsi']           = $provinsi;

            if ($this->input->post('kategori_user_pilihan')) {
                $data['kategori_user']      = $this->input->post('kategori_user_pilihan', TRUE);
                $kategori_user              = $this->input->post('kategori_user_pilihan', TRUE);
            } else {
                $cek_kategori = $this->db->query("SELECT kategori_user FROM tb_user WHERE id_user = ".$id_user." ")->row_array();
                $kategori_user              = $cek_kategori['kategori_user'];
            }

            if ($kategori_user == 'Ideasi') {
                $data['problem_disekitar']  = sanitize_input($this->input->post('problem_disekitar'));
                $data['solusi_yang_dibuat'] = sanitize_input($this->input->post('solusi_yang_dibuat'));
                $data['jumlah_anggota']     = (int) $this->input->post('jumlah_anggota', TRUE);
            } elseif ($kategori_user == 'MVP') {
                $data['nama_startup']       = sanitize_input($this->input->post('nama_startup'));
                $data['jumlah_anggota']     = (int) $this->input->post('jumlah_anggota', TRUE);

                if ($_FILES['file_pitchdeck']['name']) {
                    $filename = 'Pitchdeck-'.strtolower(url_title(sanitize_input($this->input->post('nama_startup'))));
                    $data['file_pitchdeck'] = $this->upload_file_pdf('file_pitchdeck', $filename);
                }

            } else {
                $data['kabupaten']                  = (int) $this->input->post('kabupaten', TRUE);
                $data['nama_komunitas']             = sanitize_input($this->input->post('nama_komunitas'));
                $data['jumlah_anggota_komunitas']   = sanitize_input($this->input->post('jumlah_anggota_komunitas'));
                $data['jabatan_komunitas']          = sanitize_input($this->input->post('jabatan_komunitas'));
                $data['akun_komunitas']             = sanitize_input($this->input->post('akun_komunitas'));
    
                if ($_FILES['file_pengajuan_kegiatan']['name']) {
                    $filename = 'Pengajuan-kegiatan-'.strtolower(url_title(sanitize_input($this->input->post('nama_komunitas'))));
                    $data['file_pengajuan_kegiatan'] = $this->upload_file_pdf('file_pengajuan_kegiatan', $filename);
                }
    
                if ($_FILES['file_analisa_skorlife']['name']) {
                    $filename = 'Analisa-skorlife-'.strtolower(url_title(sanitize_input($this->input->post('nama_komunitas'))));
                    $data['file_analisa_skorlife'] = $this->upload_file_image('file_analisa_skorlife', $filename);
                }
    
                if ($_FILES['file_profile_komunitas']['name']) {
                    $filename = 'Profile-komunitas-'.strtolower(url_title(sanitize_input($this->input->post('nama_komunitas'))));
                    $data['file_profile_komunitas'] = $this->upload_file_pdf('file_profile_komunitas', $filename);
                }
            }
            

            $query = $this->main_model->update_data('tb_user', $data, 'id_user', $id_user);

            if ($query) {
                $response['status']  = 1;
                $response['message'] = 'Perubahan berhasil disimpan.';
            } 
            else {
                $response['status']  = 0;
                $response['message'] = 'Gagal menyimpan perubahan.';
            }

            $response['kategori'] = $kategori_user;

            json_response($response);
        } 
        else {
            redirect('logout');
        }
    }

    public function sanitize_input($str)
    {
        $str = strip_tags($str); // Menghapus tag HTML/JS
        $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8'); // Konversi simbol HTML agar tidak dieksekusi
        return $str;
    }

    function post_update_pilot_project()
    {
        $id_user = key_auth();

        $id_user    = (int) $id_user; 
        $token_user = $this->input->post('token_user', TRUE); 

        $query_cek = $this->db->select('id_user')
            ->from('tb_user')
            ->where('id_user', $id_user)
            ->where('token', $token_user)
            ->get()
            ->row_array();

        if ($query_cek) {

            $data['pp_background_masalah']  = $this->input->post('pp_background_masalah', TRUE);
            $data['pp_nama']                = $this->input->post('pp_nama', TRUE);
            $data['pp_deskripsi']           = $this->input->post('pp_deskripsi', TRUE);
            $data['pp_deskripsi']           = $this->input->post('pp_deskripsi', TRUE);
            $data['pp_target']              = $this->input->post('pp_target', TRUE);
            $data['pp_timeline']            = $this->input->post('pp_timeline', TRUE);
            $data['pp_potential_partner']   = $this->input->post('pp_potential_partner', TRUE);
            $data['pp_kebutuhan_ahli']      = $this->input->post('pp_kebutuhan_ahli', TRUE);
            $data['pp_pembeda']             = $this->input->post('pp_potential_partner', TRUE);

            $query = $this->main_model->update_data('tb_user', $data, 'id_user', $id_user);

            if ($query) {
                $response['status']  = 1;
                $response['message'] = 'Perubahan berhasil disimpan.';
            } 
            else {
                $response['status']  = 0;
                $response['message'] = 'Gagal menyimpan perubahan.';
            }

            json_response($response);
        } 
        else {
            redirect('logout');
        }
    }

    private function upload_file_pdf($name, $filename)
    {
        $kode_user    = strtoupper(substr(uniqid(), 7)).date('my');

        $config['upload_path']   = './file_media/file-user/';
        $config['allowed_types'] = 'pdf|ppt|pptx';
        $config['file_name']     = $filename.'-'.$kode_user;

        $this->upload->initialize($config);

        if ($this->upload->do_upload($name)) { 
            return $this->upload->data('file_name'); 
        } else {
            return '';
        }
    }

    private function upload_file_image($name, $filename)
    {
        $kode_user    = strtoupper(substr(uniqid(), 7)).date('my');

        $config['upload_path']   = './file_media/file-user/';
        $config['allowed_types'] = 'jpg|jpeg|png|webp';
        $config['file_name']     = $filename.'-'.$kode_user;

        $this->upload->initialize($config);

        if ($this->upload->do_upload($name)) { 
            return $this->upload->data('file_name'); 
        } else {
            return '';
        }
    }
// --------------------------- end profile ---------------------------


// --------------------------- change password --------------------------- 
    function post_change_password()
    {
        $id_user = key_auth();
        
        if ($id_user) {

            $password_konfirmasi  = $this->input->post('password_konfirmasi');

            $this->load->library('form_validation');
            $row = array(
                "password_konfirmasi"             => $password_konfirmasi
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('password_konfirmasi', 'password_konfirmasi', 'trim|required|callback_password_conf');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['password_konfirmasi']  = $password_konfirmasi;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $data['password']       = md5($password_konfirmasi);
            $data['password_text']  = $password_konfirmasi;

            $query = $this->main_model->update_data('tb_user', $data, 'id_user', $id_user);

            if ($query) {
                $response['status']  = 1;
                $response['message'] = 'Password berhasil diperbarui. Silahkan login kembali menggunakan password baru Anda';
            } 
            else {
                $response['status']  = 0;
                $response['message'] = 'Gagal mengganti password.';
            }

            json_response($response);
        }
        else {
            redirect('');
        }
    }

    public function password_conf($password)
    {
        // Minimal 8 karakter + huruf besar + huruf kecil + angka + simbol (tanpa ! = < > " ])
        $has_upper   = preg_match('/[A-Z]/', $password);
        $has_lower   = preg_match('/[a-z]/', $password);
        $has_number  = preg_match('/[0-9]/', $password);
        $has_symbol  = preg_match('/[^a-zA-Z0-9]/', $password);
        $has_invalid = preg_match('/[!=<>\"\]]/', $password); // simbol yang DILARANG

        if (
            strlen($password) >= 8 &&
            $has_upper &&
            $has_lower &&
            $has_number &&
            $has_symbol &&
            !$has_invalid
        ) {
            return TRUE;
        } else {
            $this->form_validation->set_message(
                'password_conf',
                'Password harus minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, simbol, dan tidak boleh mengandung karakter: ! = < > " ]'
            );
            return FALSE;
        }
    } 
// --------------------------- end change password --------------------------- 

// --------------------------- get modul ---------------------------
    function _sql_data_modul($limit, $start)
    {
        $search = trim($this->input->post('search') ?? '');

        $id_user = key_auth();
        $get_user = $this->db->query("SELECT kategori_user as kategori FROM tb_user WHERE id_user = ".$id_user." ")->row_array();

        $this->db->select('m.id_modul, m.modul, m.cover, m.deskripsi_modul, m.date_create');
        $this->db->from('edu_modul m');
        $this->db->where('m.kategori', $get_user['kategori']);
        $this->db->where('m.status_delete', '0');

        if($search != '') {
            $this->db->where('m.modul LIKE "%'.$search.'%"');
        }

        $this->db->order_by('m.id_modul', 'ASC');
        $this->db->limit($limit, $start);

        $query = $this->db->get();

        return $query;
    }

    function fetch_data_modul() 
    {   
        $id_user = key_auth();

        $limit    = $this->input->post('limit');
        $start    = $this->input->post('start');

        $data = array();

        $query = $this->_sql_data_modul($limit, $start);

        foreach ($query->result_array() as $key) {
            
            $cek_sertif = $this->db->query("SELECT skor
                FROM quiz_skor_user 
                WHERE id_modul = ".$key['id_modul']."
                AND jenis_quiz = 'POST - TEST'
                AND id_user = ".$id_user."
                ")->result_array();
            
             // AND skor > '60'

            $get_video = $this->db->query("SELECT COUNT(id_video) as total FROM edu_video WHERE id_modul = ".$key['id_modul']." ")->row_array();

            $row['id_modul']        = $key['id_modul'];
            $row['url_detail']      = url_modul_detail($key['modul'], $key['id_modul']);
            $row['url_edukasi']     = url_modul_edukasi($key['modul'], $key['id_modul']);
            $row['modul']           = $key['modul'];
            $row['deskripsi_modul'] = strip_tags($key['deskripsi_modul']);
            $row['date_create']     = $key['date_create'];
            $row['cover']           = $this->main_model->url_image($key['cover'], 'file-modul');
            $row['total_video']     = $get_video['total'];

            if ($cek_sertif) {
                $row['url_sertifikat']     = base_url()."dashboard/show_sertifikat?user=".encrypt_url($id_user).'&modul='.encrypt_url($key['id_modul']);
            } else {
                $row['url_sertifikat']     = "";
            }
            

            array_push($data, $row);
        }

        // handle load more
        $start_next = $start+$limit;
        $query_next = $this->_sql_data_modul($limit, $start_next);

        if ($query_next->num_rows() >= intval($limit)) { $load_more = 1; } else { $load_more = 0; }
        

        $response['data']      = $data;
        $response['load_more'] = $load_more;

        $response['status']  = 1;
        $response['message'] = 'Success';

        json_response($response);
    }
// --------------------------- get modul ---------------------------

function get_data_user() 
{
    $id_user = key_auth();
    $query = $this->db->query("SELECT nama FROM tb_user WHERE id_user = '".$id_user."'")->row();

    return $query;
}

public function show_sertifikat()
    {   

        $user_encryp  = $this->input->get('user', TRUE);
        $modul_encryp = $this->input->get('modul', TRUE);

        $id_user  = decrypt_url($user_encryp);
        $id_modul = decrypt_url($modul_encryp);

        if (!empty($user_encryp) && !empty($modul_encryp)) {

            $cek_2 = $this->db->query('SELECT * FROM edu_modul_user_progress WHERE id_user = "'.$id_user.'" AND id_modul = "'.$id_modul.'" ')->row();

            if ($cek_2) {
                $modul = $this->db->query("SELECT * FROM edu_modul WHERE id_modul = '".$id_modul."'  ")->row_array();

                $data_user  = $this->get_data_user();
                $nama       = strtoupper($data_user->nama);
                $nama_modul = word_wrap(strtoupper($modul['modul']), 65);

                $text_modul     = 'Telah menyelesaikan modul "'.$modul['modul'].'"';
                $text_modul_2   = 'dari SheHacks 2025';
                $date       = date('d F Y', strtotime($cek_2->date_sertifikat));

                $get_web    = $this->db->query("SELECT image_sertifikat FROM tb_admin_web WHERE id = 1")->row_array();

                $base64     = $get_web['image_sertifikat'];
                $response_verify = base64_decode($base64);

                $image        = ImageCreateFromString($response_verify);
                $color_black  = imageColorAllocate($image, 0, 0, 0);
                // $font_bold = "assets/front/font/sertifikat/baskerville-bold-bt.ttf";
                $font_bold    = "assets/front/font/sertifikat/IndosatBold-Bold.ttf";
                $font_medium  = "assets/front/font/sertifikat/IndosatMedium-Medium.ttf";
                $font_tanggal = "assets/front/font/sertifikat/Poppins-Regular.ttf";

                $font_size_lg = 48;
                $font_size_md = 25;
                $font_size_sm = 10.5;

                // definisikan lebar gambar agar posisi teks selalu ditengah berapapun jumlah hurufnya
                $image_width = imagesx($image);

                // conf nama lengkap
                $text_box    = imagettfbbox($font_size_lg, 0, $font_bold, $nama);
                $text_width  = $text_box[2]-$text_box[0]; 
                $text_height = $text_box[3]-$text_box[1];
                $x           = ($image_width/2) - ($text_width/2);

                // conf judul modul
                $text_box2    = imagettfbbox($font_size_sm, 0, $font_bold, $nama_modul);
                $text_width2  = $text_box2[2]-$text_box2[0]; 
                $text_height2 = $text_box2[3]-$text_box2[1];
                $x2           = ($image_width/2) - ($text_width2/2);

                // conf tanggal terbit
                $text_box3    = imagettfbbox($font_size_sm, 0, $font_tanggal, $date);
                $text_width3  = $text_box3[2]-$text_box3[0]; 
                $text_height3 = $text_box3[3]-$text_box3[1];
                $x3           = ($image_width/2) - ($text_width3/2);

                // generate sertifikat beserta namanya
                imagettftext($image, $font_size_lg, 0, 715, 720, $color_black, $font_bold, $nama);
                imagettftext($image, $font_size_md, 0, 715, 805, $color_black, $font_medium, $text_modul);
                imagettftext($image, $font_size_md, 0, 715, 855, $color_black, $font_medium, $text_modul_2);
                // imagettftext($image, $font_size_sm, 0, $x3, 400, $color_black, $font_tanggal, $date);

                // tampilkan di browser
                header("Content-type: image/jpeg");
                imagejpeg($image);
                imagedestroy($image);
            } else {
                redirect("");
            }
            
        } else {
            redirect("");
        }
        
        
    }

}
