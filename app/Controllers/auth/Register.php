<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller {

    function __construct() { 
        parent::__construct();
    } 

    public function index()
    {
        $web = $this->main_model->get_admin_web();

        if ($web['register_button'] == 'true') {
            
            if($this->session->userdata('logged_in_front') == TRUE) {
                redirect('dashboard');
            } 
            else {
                $data['title']       = 'Register';
                $data['description'] = '';
                $data['keywords']    = '';
                $data['page']        = 'auth/register';
                $this->load->view('index', $data);
            }
        } 
        else {
            redirect('');
        }   
    }

    function announcement()
    {
        $web = $this->main_model->get_admin_web();

        if($this->session->userdata('logged_in_front') == TRUE) {
            redirect('dashboard');
        } 
        else {
            $data['title']       = 'Register 2004';
            $data['description'] = '';
            $data['keywords']    = '';
            $data['page']        = 'auth/register_announcement';
            $this->load->view('index', $data);
        }
    }

    function verifikasi()
    {   
        $token_user = $this->input->get('token_user');
        $token_verf = $this->input->get('token_verf');

        if (!empty($token_user)) {

            if (!empty($token_verf)) {

                $query_ver = $this->db->query("SELECT id_user, id_verifikasi, status_verifikasi FROM tb_user_verifikasi_email 
                    WHERE token_user = '".$token_user."'
                    AND token_verifikasi = '".$token_verf."' ")->row_array();

                if ($query_ver['status_verifikasi'] == 'false') {

                    $data_update_ver['status_verifikasi']      = 'true';

                    $this->db->where('id_verifikasi', $query_ver['id_verifikasi']);
                    $this->db->update('tb_user_verifikasi_email', $data_update_ver);

                    //set session
                    $session['id_user']         = $query_ver['id_user'];
                    $session['logged_in_front'] = TRUE;
                    $this->session->set_userdata($session);
                    
                    //update last login
                    $data_update['last_login']  = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');
                    $data_update['status']      = '1';

                    $this->db->where('id_user', $query_ver['id_user']);
                    $this->db->update('tb_user', $data_update);

                    redirect('dashboard');

                } 
                else if ($query_ver['status_verifikasi'] == 'true') 
                {

                    //set session
                    $session['id_user']         = $query_ver['id_user'];
                    $session['logged_in_front'] = TRUE;
                    $this->session->set_userdata($session);

                    //update last login
                    $data_update['last_login']  = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');

                    $data_update['status']      = '1';

                    $this->db->where('id_user', $query_ver['id_user']);
                    $this->db->update('tb_user', $data_update);

                    redirect('dashboard');

                } else {
                    redirect('');
                }

            } else {
                redirect('');
            }
        } else {
            redirect('');
        }
        
    }


    function cek_email($email)
    {

        $this->db->select('id_user');
        $this->db->where('email', $value, TRUE);
        $query = $this->db->get('tb_user')->result();

        if ($query) {
            $status = false;
        } else {
            $status = true;
        }

        return $status;
    }

    // function cek_phone()
    // {
    //     $value = $this->input->post('value');
    //     $this->db->select('id_user');
    //     $this->db->where('telp', $value, TRUE);
    //     $query = $this->db->get('tb_user')->result();

    //     if ($query) {
    //         $response['status'] = 1;
    //     } else {
    //         $response['status'] = 0;
    //     }

    //     json_response($response);
    // }


    function post_register_profile()
    {   
        $kategori_user = $this->input->post('kategori_user', TRUE);

        $this->load->library('form_validation');
        $row = array(
            "kategori_user"        => $kategori_user,
        ); 

        $this->form_validation->set_data($row);
        $this->form_validation->set_rules('kategori_user', 'kategori_user', 'trim|required|callback_sanitize_input');

        if ( $this->form_validation->run() === false ) {
            $response['status']  = 0;
            $response['message'] = validation_errors();
            json_response($response);
            return;
        }
        
        $data['kategori_user'] = $kategori_user;

        $this->session->set_userdata($data);

        $response['status']  = 1;
        $response['message'] = 'Success';

        json_response($response);
    }

    function post_register_personal()
    {     
        
        $email              = $this->input->post('email', TRUE);
        $tanggal_lahir      = $this->input->post('tanggal_lahir', TRUE);
        $nama               = $this->input->post('nama', TRUE);
        $telp               = $this->input->post('telp', TRUE);
        $pendidikan         = $this->input->post('pendidikan', TRUE);
        $dapat_informasi    = $this->input->post('dapat_informasi', TRUE);
        $jenis_kelamin      = $this->input->post('jenis_kelamin', TRUE);

        $this->load->library('form_validation');
        $row = array(
            "email"             => $email,
            "tanggal_lahir"     => $tanggal_lahir,
            "nama"              => $nama,
            "telp"              => $telp,
            "pendidikan"        => $pendidikan,
            "dapat_informasi"   => $dapat_informasi,
            "jenis_kelamin"     => $jenis_kelamin,
        ); 

        $this->form_validation->set_data($row);
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
        $this->form_validation->set_rules('tanggal_lahir', 'tanggal_lahir', 'trim|required|callback_sanitize_input');
        $this->form_validation->set_rules('nama', 'nama', 'trim|required|callback_sanitize_input');
        $this->form_validation->set_rules('telp', 'telp', 'trim|required|callback_sanitize_input');
        $this->form_validation->set_rules('pendidikan', 'pendidikan', 'trim|required|callback_sanitize_input');
        $this->form_validation->set_rules('dapat_informasi', 'dapat_informasi', 'trim|required|callback_sanitize_input');
        $this->form_validation->set_rules('jenis_kelamin', 'jenis_kelamin', 'trim|required|callback_sanitize_input');

        if ( $this->form_validation->run() === false ) {
            $response['status']  = 0;
            $response['message'] = validation_errors();
            json_response($response);
            return;
        }

		$this->db->select('id_user');
        $this->db->from('tb_user');
        $this->db->where('email', $email);
        $cek_email = $this->db->get()->row_array();

        if ($cek_email) {
            $response['status']  = 0;
            $response['message'] = 'Gagal, Email Pengguna sudah terdaftar';
        }
        else {

            $this->db->select('id_user');
            $this->db->from('tb_user');
            $this->db->where('telp', $telp);
            $cek_telp = $this->db->get()->row_array();

            if ($cek_telp) {
                $response['status']  = 0;
                $response['message'] = 'Gagal, Telephone Pengguna sudah terdaftar';
            }
            else {

                $data['nama']               = $nama;
                $data['telp']               = $telp;
                $data['tanggal_lahir']      = ($tanggal_lahir ? DateTime::createFromFormat('d/m/Y', trim($tanggal_lahir))->format('Y-m-d') : $tanggal_lahir);
                $data['pendidikan']         = $pendidikan;
                $data['email']              = $email;
                $data['password']           = $this->input->post('password', TRUE);
                $data['dapat_informasi']    = $dapat_informasi;
                $data['jenis_kelamin']      = $jenis_kelamin;

                $this->session->set_userdata($data);

                $response['data']  = $this->session->userdata('nama', TRUE);
                $response['status']  = 1;
                $response['message'] = 'Success';
            }
        }

        json_response($response);
    }

    public function validate_file_upload($dummy, $field_name)
    {
        if (isset($_FILES[$field_name]) && $_FILES[$field_name]['error'] === 0) {
            $allowed_types = [
                'application/pdf',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation' // .pptx
            ];

            $file_type = mime_content_type($_FILES[$field_name]['tmp_name']);

            if (in_array($file_type, $allowed_types)) {
                return TRUE;
            } else {
                $this->form_validation->set_message(
                    'validate_file_upload',
                    "File pada {$field_name} harus berformat PDF atau PowerPoint (.ppt/.pptx)."
                );
                return FALSE;
            }
        }

        return TRUE;
    }

    public function validate_image_upload($dummy, $field_name)
    {
        if (isset($_FILES[$field_name]) && $_FILES[$field_name]['error'] === 0) {
            $allowed_types = [
                'image/jpeg',
                'image/png',
                'image/jpg',
                'image/webp',
            ];

            $file_type = mime_content_type($_FILES[$field_name]['tmp_name']);

            if (in_array($file_type, $allowed_types)) {
                return TRUE;
            } else {
                $this->form_validation->set_message(
                    'validate_image_upload',
                    "File pada {$field_name} harus berupa gambar (JPG, JPEG PNG, atau webp)."
                );
                return FALSE;
            }
        }

        return TRUE; // tidak ada file diupload dianggap valid
    }

    function post_register_startup()
    {   
        $file_pitchdeck             = $this->input->post('file_pitchdeck');
        $file_pengajuan_kegiatan    = $this->input->post('file_pengajuan_kegiatan');
        $file_analisa_skorlife      = $this->input->post('file_analisa_skorlife');
        $file_profile_komunitas     = $this->input->post('file_profile_komunitas');

        $this->load->library('form_validation');
        $row = array(
            "file_pitchdeck"            => $file_pitchdeck,
            "file_pengajuan_kegiatan"   => $file_pengajuan_kegiatan,
            "file_analisa_skorlife"     => $file_analisa_skorlife,
            "file_profile_komunitas"    => $file_profile_komunitas,
        ); 

        $this->form_validation->set_data($row);
        $this->form_validation->set_rules('file_pitchdeck', 'file_pitchdeck', 'callback_validate_file_upload[file_pitchdeck]');
        $this->form_validation->set_rules('file_pengajuan_kegiatan', 'file_pengajuan_kegiatan', 'callback_validate_file_upload[file_pengajuan_kegiatan]');
        $this->form_validation->set_rules('file_analisa_skorlife', 'file_analisa_skorlife', 'callback_validate_image_upload[file_analisa_skorlife]');
        $this->form_validation->set_rules('file_profile_komunitas', 'file_profile_komunitas', 'callback_validate_file_upload[file_profile_komunitas]');

        if ( $this->form_validation->run() === false ) {
            $response['status']  = 0;
            $response['message'] = validation_errors();
            json_response($response);
            return;
        }

        $kode_user = strtoupper(substr(uniqid(), 7)).date('my');
        $token     = md5(date('his').date('d').uniqid().date('my'));

        $kategori_user              = sanitize_input($this->session->userdata('kategori_user'));
        $data['kode_user']          = $kode_user;
        $data['token']              = $token;
        $data['status']             = 0;
        $data['date_create']        = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');

        // personal
        $data['kategori_user']      = $kategori_user;
        $data['nama']               = sanitize_input($this->session->userdata('nama'));
        $data['telp']               = sanitize_input($this->session->userdata('telp'));
        $data['tanggal_lahir']      = sanitize_input($this->session->userdata('tanggal_lahir'));
        $data['pendidikan']         = sanitize_input($this->session->userdata('pendidikan'));
        $data['umur']               = $this->main_model->get_umur(sanitize_input($this->session->userdata('tanggal_lahir')));
        $data['email']              = sanitize_input($this->session->userdata('email'));
        $data['password']           = md5($this->session->userdata('password'));
        $data['password_text']      = $this->session->userdata('password');
        $data['dapat_informasi']    = sanitize_input($this->session->userdata('dapat_informasi'));
        $data['jenis_kelamin']      = sanitize_input($this->session->userdata('jenis_kelamin'));
        $data['status']             = 1;
        $data['channel']            = '2025';

        // startup
        $data['provinsi']           = (int) $this->input->post('provinsi');

        if ($kategori_user == 'Ideasi') {
            $data['problem_disekitar']  = sanitize_input($this->input->post('problem_disekitar'));
            $data['solusi_yang_dibuat'] = sanitize_input($this->input->post('solusi_yang_dibuat'));
            $data['jumlah_anggota']     = (int) $this->input->post('jumlah_anggota');
        } 
        else if ($kategori_user == 'MVP') {
            $data['nama_startup']       = sanitize_input($this->input->post('nama_startup'));
            $data['jumlah_anggota']     = (int) $this->input->post('jumlah_anggota');

            if ($_FILES['file_pitchdeck']['name']) {
                $filename = 'Pitchdeck-'.strtolower(url_title(sanitize_input($this->input->post('nama_startup'))));
                $data['file_pitchdeck'] = $this->upload_file_pdf('file_pitchdeck', $filename);
            }
        } 
        else {
            $data['kabupaten']                  = (int) $this->input->post('kabupaten');
            $data['nama_komunitas']             = sanitize_input($this->input->post('nama_komunitas'));
            $data['jumlah_anggota_komunitas']   = (int) $this->input->post('jumlah_anggota_komunitas');
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

        $this->db->select('id_user');
        $this->db->where('email', sanitize_input($this->session->userdata('email')));
        $cek_user = $this->db->get('tb_user')->row_array();

        if (empty($cek_user)) {
            $query      = $this->db->insert('tb_user', $data);
            $id_user    = $this->db->insert_id();

            if ($query) {

                // clear session
                $this->_clear_session();

                // 1FA Handle
                $this->fa_handle($id_user);

                // send API IDE - AOH
                // $this->send_API_IDE_Oauth_PROD($id_user, $data['password_text']);
                // $this->send_API_IDE_Oauth_SANDBOX($id_user, $data['password_text']);

                $response['status']   = 1;
                $response['message']  = 'Sukses';
            }
            else {  
                $response['status']  = 0;
                $response['message'] = 'Registrasi gagal, Silahkan coba lagi.';
            }
        }
        else {  
            $response['status']  = 0;
            $response['message'] = 'Gagal, email sudah terdaftar.';
        }

        json_response($response);

    }

    function fa_handle($id_user) 
    {
        $generate_code = rand(100000, 999999);

        $dataInsert2fa['id_user']      = $id_user;
        $dataInsert2fa['code']         = $generate_code;
        $dataInsert2fa['access_policy']= 'FE';
        $dataInsert2fa['code_encrypt'] = encrypt_url($generate_code);
        $dataInsert2fa['date_create']  = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');
        $dataInsert2fa['date_expired'] = date_create('now', timezone_open('Asia/Jakarta'))->modify('+10 minutes')->format('Y-m-d H:i:s');
        $this->db->insert('tb_user_2fa', $dataInsert2fa);

        $data_email['id_user']         = $id_user;
        $data_email['code']            = $generate_code;

        $this->send_email($data_email);

        $this->session->set_userdata('2fa_id_user', encrypt_url($id_user));
    }

    function send_email($data) 
    {
        require_once(APPPATH.'third_party/phpmailer/PHPMailerAutoload.php');

        $this->db->select('email');
        $this->db->where('id_user', $data['id_user'], TRUE);
        $get_user = $this->db->get('tb_user')->row_array();
       
        $data_email['code']          = $data['code'];
        $data_email['email']         = $get_user['email'];
        $data_email['param']         = 'Mendaftar';

        $message  = $this->load->view('email/2fa_email_login', $data_email, TRUE);
        $get_konf = $this->db->query("SELECT * FROM tb_admin_konf_email WHERE id = 1 ")->row_array();

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host       = $get_konf['host'];
        $mail->SMTPAuth   = $get_konf['smtpauth'];
        $mail->Username   = $get_konf['email'];
        $mail->Password   = $get_konf['password'];
        $mail->SMTPSecure = $get_konf['smtpsecure'];
        $mail->Port       = $get_konf['port'];
        $mail->Subject    = $data['code']." - Kode akses login akun SheHacks";
        $mail->setFrom($get_konf['email'], $get_konf['setfrom']);
        $mail->addAddress($data_email['email']);
        $mail->isHTML(true);
        //$mail->AddEmbeddedImage(FCPATH.'assets/front/img/Image-registrasi-2024.png', 'logo_email');
        $mail->MsgHTML(stripslashes($message));
        $mail->send();
        
    }

    // ---------------- API IDE -------------------

        // ---------------- API BARU ------------------

        function send_API_IDE_Oauth_PROD($id_user, $password_text) {
            $post_token = $this->get_token_oauth_PROD();

            // Jika token didapat
            if ($post_token['sukses'] == '1') {

                $access_token           = $post_token['access_token'];
                // EndPoint Production
                $end_Point              = 'https://ide.ioh.co.id/sso/api/v1/users';           

                $get_data_user = $this->get_data_user($id_user);

                $post = array (
                    'email'             => $get_data_user['email'],
                    'password'          => $password_text,
                    'name'              => $get_data_user['nama'],
                    'given_name'        => $get_data_user['nama'],
                    'family_name'       => $get_data_user['nama'],
                    'nickname'          => $get_data_user['nama'],
                    'picture'           => $get_data_user['foto'],
                    'birth_date'        => $get_data_user['tanggal_lahir'],
                    'phone_number'      => $get_data_user['telp'],
                    'country'           => 'Indonesia',
                    'province'          => $get_data_user['provinsi'],
                    'city'              => $get_data_user['kabupaten'],
                    'district'          => '-',
                    'address'           => '-',
                    'postal_code'       => '-',
                    'business_name'     => $get_data_user['nama_startup'],
                    'business_email'    => $get_data_user['email'],
                    'business_contact'  => $get_data_user['telp'],
                    'business_address'  => '-',
                    'business_category' => '-',
                    'business_revenue'  => '0',
                    'source'            => 'Shehack'
                );  

                $posh_json = json_encode($post);  

                $headers = [
                    'Accept: */*',
                    "Authorization: Bearer $access_token",
                    'Content-Type: application/json'            
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $end_Point);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");   
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);   
                curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);  
                curl_setopt($ch, CURLOPT_TIMEOUT, 100);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $posh_json);

                $response       = curl_exec ($ch);

                $data_update['log_sync_ide'] = $response;

                $this->db->where('id_user', $id_user);
                $this->db->update('tb_user', $data_update);

            // Jika tidak dapat token
            } else {
                $data_update['log_sync_ide'] = $post_token['message'];

                $this->db->where('id_user', $id_user);
                $this->db->update('tb_user', $data_update);
            }
        }

        function get_token_oauth_PROD() {

            // EndPoint Production
            $end_Point          = 'https://ide.ioh.co.id/sso/api/v1/oauth/token'; 
            $client_id          = "DI3Yqh7BcW4wujMdjE7fDhqNzwOPXNhO"; 
            $client_secret      = "Qwbfg5eDVDNadKTkDgXM7tlcfPmbCDQZuJxot4i42XEx-cjpAGAITDB3jGACDqUF";           

            $post = array (
                'client_id'         => $client_id,
                'client_secret'     => $client_secret,
            );  

            $posh_json = json_encode($post);  

            $headers = [
                'Accept: */*',
                'Content-Type: application/json'            
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $end_Point);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");   
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);   
            curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);  
            curl_setopt($ch, CURLOPT_TIMEOUT, 100);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $posh_json);

            $response       = curl_exec ($ch);

            $data           = json_decode($response, true);
            $access_token   = $data['access_token'];

            if ($access_token) {
                $result['sukses']       = 1;
                $result['access_token'] = $access_token;
                $result['message']      = $response;
            } else {
                $result['sukses']       = 0;
                $result['access_token'] = "";
                $result['message']      = $response;
            }

            return $result;
        }

        function send_API_IDE_Oauth_SANDBOX($id_user, $password_text) {
            $post_token = $this->get_token_oauth_SAND();

            // Jika token didapat
            if ($post_token['sukses'] == '1') {

                $access_token       = $post_token['access_token'];
                // EndPoint sandbox
                $end_Point          = 'https://ide.gammasprint.com/sso/api/v1/users'; 
                $get_data_user      = $this->get_data_user($id_user);       

                $post = array (
                    'email'             => $get_data_user['email'],
                    'password'          => $password_text,
                    'name'              => $get_data_user['nama'],
                    'given_name'        => $get_data_user['nama'],
                    'family_name'       => $get_data_user['nama'],
                    'nickname'          => $get_data_user['nama'],
                    'picture'           => $get_data_user['foto'],
                    'birth_date'        => $get_data_user['tanggal_lahir'],
                    'phone_number'      => $get_data_user['telp'],
                    'country'           => 'Indonesia',
                    'province'          => $get_data_user['provinsi'],
                    'city'              => $get_data_user['kabupaten'],
                    'district'          => '-',
                    'address'           => '-',
                    'postal_code'       => '-',
                    'business_name'     => $get_data_user['nama_startup'],
                    'business_email'    => $get_data_user['email'],
                    'business_contact'  => $get_data_user['telp'],
                    'business_address'  => '-',
                    'business_category' => '-',
                    'business_revenue'  => '0',
                    'source'            => 'Shehack'
                );  

                $posh_json = json_encode($post);  

                $headers = [
                    'Accept: */*',
                    "Authorization: Bearer $access_token",
                    'Content-Type: application/json'            
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $end_Point);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");   
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);   
                curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);  
                curl_setopt($ch, CURLOPT_TIMEOUT, 100);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $posh_json);

                $response       = curl_exec ($ch);

                $data_update['log_sync_ide'] = $response;

                $this->db->where('id_user', $id_user);
                $this->db->update('tb_user', $data_update);

            // Jika tidak dapat token
            } else {
                $data_update['log_sync_ide'] = $post_token['message'];

                $this->db->where('id_user', $id_user);
                $this->db->update('tb_user', $data_update);
            }
        }

        public function send_API_IDE_Oauth_SANDBOXTEST() {
            $post_token = $this->get_token_oauth_SAND();

            // Jika token didapat
            if ($post_token['sukses'] == '1') {

                $access_token       = $post_token['access_token'];
                // EndPoint sandbox
                $end_Point          = 'https://ide.gammasprint.com/sso/api/v1/users'; 
                $get_data_user      = $this->get_data_user(2);       

                $post = array (
                    'email'             => 'bantucatat@gmail.com',
                    'password'          => 'Sipekok!233',
                    'name'              => $get_data_user['nama'],
                    'given_name'        => $get_data_user['nama'],
                    'family_name'       => $get_data_user['nama'],
                    'nickname'          => $get_data_user['nama'],
                    'picture'           => $get_data_user['foto'],
                    'birth_date'        => $get_data_user['tanggal_lahir'],
                    'phone_number'      => $get_data_user['telp'],
                    'country'           => 'Indonesia',
                    'province'          => $get_data_user['provinsi'],
                    'city'              => $get_data_user['kabupaten'],
                    'district'          => '-',
                    'address'           => '-',
                    'postal_code'       => '-',
                    'business_name'     => $get_data_user['nama_startup'],
                    'business_email'    => 'bantucatat@gmail.com',
                    'business_contact'  => $get_data_user['telp'],
                    'business_address'  => '-',
                    'business_category' => '-',
                    'business_revenue'  => '0',
                    'source'            => 'Shehack'
                );  

                $posh_json = json_encode($post);  

                $headers = [
                    'Accept: */*',
                    "Authorization: Bearer $access_token",
                    'Content-Type: application/json'            
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $end_Point);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");   
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);   
                curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);  
                curl_setopt($ch, CURLOPT_TIMEOUT, 100);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $posh_json);

                $response       = curl_exec ($ch);

                echo $response;

            // Jika tidak dapat token
            } else {
                echo $post_token['message'];
            }
        }

        function get_token_oauth_SAND() {

            // EndPoint sandbox
            $end_Point          = 'https://ide.gammasprint.com/sso/api/v1/oauth/token'; 
            $client_id          = "mo1JKvwE0N70x26TaWRQm28lhayDrnRN"; 
            $client_secret      = "VQg-lVLI5-tD64nwvjirmFH8--E4IIGVCc3ao3EKtE9F5bvMQQMcLYHC3eQjYbm5";           

            $post = array (
                'client_id'         => $client_id,
                'client_secret'     => $client_secret,
            );  

            $posh_json = json_encode($post);  

            $headers = [
                'Accept: */*',
                'Content-Type: application/json'            
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $end_Point);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");   
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);   
            curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);  
            curl_setopt($ch, CURLOPT_TIMEOUT, 100);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $posh_json);

            $response       = curl_exec ($ch);

            $data           = json_decode($response, true);
            $access_token   = $data['access_token'];

            if ($access_token) {
                $result['sukses']       = 1;
                $result['access_token'] = $access_token;
                $result['message']      = $response;
            } else {
                $result['sukses']       = 0;
                $result['access_token'] = "";
                $result['message']      = $response;
            }

            return $result;
        }

        function get_data_user($id_user) 
        {

            $this->db->select('
                u.*
            ');
            $this->db->from('tb_user u');
            $this->db->where('u.id_user', $id_user);
            $query = $this->db->get()->row_array();

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

            $result['nama']               = $query['nama'];
            $result['email']              = $query['email'];
            $result['telp']               = '62'.$query['telp'];
            $result['tanggal_lahir']      = DateTime::createFromFormat('Y-m-d', trim($query['tanggal_lahir']))->format('d/m/Y');
            $result['nama_startup']       = $query['nama_startup']; 
            $result['provinsi']           = $provinsi;
            $result['kabupaten']          = $kabupaten;
            $result['foto']               = $this->main_model->url_image($query['foto'], 'image-user');

            return $result;
        }

        // ---------------- END API BARU ------------------

        // ---------------- API LAMA ------------------
            function send_API_IDE($id_user, $email, $password, $fullname, $telp) {

                $target_url = $this->config->item('url_api_IDE').'/api/v1/extusrmgmt/setSignUp'; 
                $api_key    = $this->config->item('api_key_IDE');           

                $post = array (
                    'email'     => $email,
                    'password'  => $password,
                    'fullName'  => $fullname,
                    'source'    => 'Shehack',
                    'contactNo' => '62'.$telp,
                    'registrationType'    => 1
                );  

                $posh_json = json_encode($post);  

                $headers = [
                    'Accept: */*',
                    'api-key: '.$api_key,
                    'Content-Type: application/json'            
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $target_url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");   
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);   
                curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);  
                curl_setopt($ch, CURLOPT_TIMEOUT, 100);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $posh_json);

                $result = curl_exec ($ch);

                $data_update['log_sync_ide']      = $result;

                $this->db->where('id_user', $id_user);
                $this->db->update('tb_user', $data_update);

            }

            public function testing_api_ide() {

                $target_url = $this->config->item('url_api_IDE_staging').'/api/v1/extusrmgmt/setSignUp'; 
                $api_key    = $this->config->item('api_key_IDE');           

                $post = array (
                    'email'     => 'menoz.azza@gmail.com',
                    'password'  => 'PASS!!2024',
                    'fullName'  => 'annas',
                    'source'    => 'Shehack',
                    'contactNo' => '628200000000',
                    'registrationType'    => 1
                );  

                $posh_json = json_encode($post);  

                $headers = [
                    'Accept: */*',
                    'api-key: '.$api_key,
                    'Content-Type: application/json'            
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $target_url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");   
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);   
                curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);  
                curl_setopt($ch, CURLOPT_TIMEOUT, 100);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $posh_json);

                $result = curl_exec ($ch);

                echo $result;

            }
        // ---------------- END API LAMA ------------------

    // ---------------- END API IDE -------------------

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

    function send_email_text($id_user) {

        $get_konf = $this->db->query("SELECT * FROM tb_admin_konf_email WHERE id = 1 ")->row_array();

        $get_user = $this->db->query("
            SELECT nama, email, telp, token
            FROM tb_user 
            WHERE id_user = ".$id_user." 
            ")->row_array();

        $data_verif['id_user']              = $id_user;
        $data_verif['token_user']           = md5(date('his').date('d').uniqid().date('my'));
        $data_verif['token_verifikasi']     = $get_user['token'];
        $data_verif['status_verifikasi']    = 'false';

        $query_verf = $this->db->insert('tb_user_verifikasi_email', $data_verif);

        if ($query_verf) {

            $url_verifikasi= base_url().'register/verifikasi?token_user='.$data_verif['token_user'].'&token_verf='.$data_verif['token_verifikasi'];

            // ini_set( 'display_errors', 1 );
            // error_reporting( E_ALL );

            $from    = $get_konf['email'];
            $to      = $get_user['email'];
            $subject = "Verifikasi email Anda untuk SHEHACKS";

            $message = "

            Halo,

            Klik link ini untuk memverifikasi alamat email Anda.

            ".$url_verifikasi."

            Jika Anda tidak meminta verifikasi alamat ini, Anda dapat mengabaikan email ini.

            Terima Kasih,

            Tim SheHacks Anda";

            $headers = "From:" . $from;

            mail($to,$subject,$message, $headers);

            // if(mail($to,$subject,$message, $headers)) {
            // echo "The email message was sent.";
            // } else {
            // echo "The email message was not sent.";
            // }

        }

    }

    function send_email_lama($id_user) 
    {
        require_once(APPPATH.'third_party/phpmailer/PHPMailerAutoload.php');

        $get_user = $this->db->query("
            SELECT nama, email, telp, token, kategori_user
            FROM tb_user 
            WHERE id_user = ".$id_user." 
            ")->row_array();

        $data_verif['id_user']              = $id_user;
        $data_verif['token_user']           = md5(date('his').date('d').uniqid().date('my'));
        $data_verif['token_verifikasi']     = $get_user['token'];
        $data_verif['status_verifikasi']    = 'false';

        $query_verf = $this->db->insert('tb_user_verifikasi_email', $data_verif);


        if ($query_verf) {
            
            if ($get_user['kategori_user'] == 'MVP') {
                $data_email['text_kategori']          = 'Jangan lupa untuk lengkapi profile kamu di <a href="https://shehacks.id/" target="_blank">website SheHacks 2024</a>, dan sertakan deck dari MVP produk kamu.';
            } else {
                $data_email['text_kategori']          = 'Jangan lupa untuk lengkapi profile kamu di <a href="https://shehacks.id/" target="_blank">website SheHacks 2024</a>, dan sertakan masalah dan ide inovasi yang menjadi solusi hadapi masalah tersebut.';
            }

            $data_email['url_verifikasi']= base_url().'register/verifikasi?token_user='.$data_verif['token_user'].'&token_verf='.$data_verif['token_verifikasi'];
            
            $data_email['nama']          = $get_user['nama'];
            $data_email['email']         = $get_user['email'];
            $data_email['telp']          = $get_user['telp'];

            $message  = $this->load->view('email/register_email_terbaru', $data_email, TRUE);
            $get_konf = $this->db->query("SELECT * FROM tb_admin_konf_email WHERE id = 1 ")->row_array();

            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host       = $get_konf['host'];
            $mail->SMTPAuth   = $get_konf['smtpauth'];
            $mail->Username   = $get_konf['email'];
            $mail->Password   = $get_konf['password'];
            $mail->SMTPSecure = $get_konf['smtpsecure'];
            $mail->Port       = $get_konf['port'];
            $mail->Subject    = 'Verifikasi email Anda untuk SHEHACKS';
            $mail->setFrom($get_konf['email'], $get_konf['setfrom']);
            $mail->addAddress($data_email['email']);
            $mail->isHTML(true);
            $mail->AddEmbeddedImage(FCPATH.'assets/front/img/Image-registrasi-2024.png', 'logo_email');
            $mail->MsgHTML(stripslashes($message));
            $mail->send();

        } 
        
    }
    
    function _clear_session()
    {
        $this->session->unset_userdata('nama');
        $this->session->unset_userdata('telp');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('password');
        $this->session->unset_userdata('alamat');
        $this->session->unset_userdata('jenis_kelamin');
        $this->session->unset_userdata('tanggal_lahir');
        $this->session->unset_userdata('pendidikan');
        $this->session->unset_userdata('kategori_user');
        $this->session->unset_userdata('dapat_informasi');
    }

    public function sanitize_input($str)
    {
        if ($str === null) {
            $str = '';
        }
        
        $str = strip_tags($str);
        $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
        return $str;
    }
}