<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Register_voting extends CI_Controller {

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
                $data['page']        = 'auth/register_voting';
                $this->load->view('index', $data);
            }
        } 
        else {
            redirect('');
        }   
    }

    function post_register_voting()
    {
        $kode_user = strtoupper(substr(uniqid(), 7)).date('my');
        $token     = md5(date('his').date('d').uniqid().date('my'));
        $email     = sanitize_input($this->input->post('email'));
        
        $data['kode_user']          = $kode_user;
        $data['channel']            = '2025';
        $data['token']              = $token;
        $data['status']             = 1;
        $data['date_create']        = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');
        $data['kategori_user']      = '';
        $data['nama']               = sanitize_input($this->session->userdata('nama'));
        $data['telp']               = sanitize_input($this->session->userdata('telp'));
        $data['email']              = $email;
        $data['password']           = md5($this->input->post('password'));
        $data['password_text']      = $this->input->post('password');

        $this->db->select('id_user');
        $this->db->where('email', $email);
        $cek_user = $this->db->get('tb_user')->row_array();

        if (empty($cek_user)) {

            $query      = $this->db->insert('tb_user', $data);
            $id_user    = $this->db->insert_id();

            if ($query) {

                // clear session
                $this->_clear_session();

                // 1FA Handle
                $this->fa_handle($id_user);

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

    // function send_API_IDE($id_user, $email, $password, $fullname) {

    //     $target_url = $this->config->item('url_api_IDE').'/api/v1/extusrmgmt/setSignUp'; 
    //     $api_key    = $this->config->item('api_key_IDE');           

    //     $post = array (
    //         'email'     => $email,
    //         'password'  => $password,
    //         'fullName'  => $fullname
    //     );  

    //     $posh_json = json_encode($post);  

    //     $headers = [
    //         'Accept: */*',
    //         'api-key: '.$api_key,
    //         'Content-Type: application/json'            
    //     ];

    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $target_url);
    //     curl_setopt($ch, CURLOPT_POST, 1);
    //     curl_setopt($ch, CURLOPT_HEADER, 0);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    //     curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");   
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);   
    //     curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);  
    //     curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $posh_json);

    //     $result = curl_exec ($ch);

    //     $data_update['log_sync_ide']      = $result;

    //     $this->db->where('id_user', $id_user);
    //     $this->db->update('tb_user', $data_update);

    // }

    // public function testing_api_ide() {

    //     $target_url = $this->config->item('url_api_IDE').'/api/v1/extusrmgmt/setSignUp'; 
    //     $api_key    = $this->config->item('api_key_IDE');           

    //     $post = array (
    //         'email'     => 'sarwito.alim@gmail.com',
    //         'password'  => 'B0l4hruwet!',
    //         'fullName'  => 'oglaahoglooh'
    //     );  

    //     $posh_json = json_encode($post);  

    //     $headers = [
    //         'Accept: */*',
    //         'api-key: '.$api_key,
    //         'Content-Type: application/json'            
    //     ];

    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $target_url);
    //     curl_setopt($ch, CURLOPT_POST, 1);
    //     curl_setopt($ch, CURLOPT_HEADER, 0);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    //     curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");   
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);   
    //     curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);  
    //     curl_setopt($ch, CURLOPT_TIMEOUT, 100);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $posh_json);

    //     $result = curl_exec ($ch);

    //     echo $result;

    // }

    // function send_email_text($id_user) {

    //     $get_konf = $this->db->query("SELECT * FROM tb_admin_konf_email WHERE id = 1 ")->row_array();

    //     $get_user = $this->db->query("
    //         SELECT nama, email, telp, token
    //         FROM tb_user 
    //         WHERE id_user = ".$id_user." 
    //         ")->row_array();

    //     $data_verif['id_user']              = $id_user;
    //     $data_verif['token_user']           = md5(date('his').date('d').uniqid().date('my'));
    //     $data_verif['token_verifikasi']     = $get_user['token'];
    //     $data_verif['status_verifikasi']    = 'false';

    //     $query_verf = $this->db->insert('tb_user_verifikasi_email', $data_verif);

    //     if ($query_verf) {

    //         $url_verifikasi= base_url().'register/verifikasi?token_user='.$data_verif['token_user'].'&token_verf='.$data_verif['token_verifikasi'];

    //         // ini_set( 'display_errors', 1 );
    //         // error_reporting( E_ALL );

    //         $from    = $get_konf['email'];
    //         $to      = $get_user['email'];
    //         $subject = "Verifikasi email Anda untuk SHEHACKS";

    //         $message = "

    //         Halo,

    //         Klik link ini untuk memverifikasi alamat email Anda.

    //         ".$url_verifikasi."

    //         Jika Anda tidak meminta verifikasi alamat ini, Anda dapat mengabaikan email ini.

    //         Terima Kasih,

    //         Tim SheHacks Anda";

    //         $headers = "From:" . $from;

    //         mail($to,$subject,$message, $headers);

    //         // if(mail($to,$subject,$message, $headers)) {
    //         // echo "The email message was sent.";
    //         // } else {
    //         // echo "The email message was not sent.";
    //         // }

    //     }

    // }

    // function send_email($id_user) 
    // {
    //     require_once(APPPATH.'third_party/phpmailer/PHPMailerAutoload.php');

    //     $get_user = $this->db->query("
    //         SELECT nama, email, telp, token, kategori_user
    //         FROM tb_user 
    //         WHERE id_user = ".$id_user." 
    //         ")->row_array();

    //     $data_verif['id_user']              = $id_user;
    //     $data_verif['token_user']           = md5(date('his').date('d').uniqid().date('my'));
    //     $data_verif['token_verifikasi']     = $get_user['token'];
    //     $data_verif['status_verifikasi']    = 'false';

    //     $query_verf = $this->db->insert('tb_user_verifikasi_email', $data_verif);


    //     if ($query_verf) {
            
    //         if ($get_user['kategori_user'] == 'MVP') {
    //             $data_email['text_kategori']          = 'Jangan lupa untuk lengkapi profile kamu di <a href="https://shehacks.id/" target="_blank">website SheHacks 2023</a>, dan sertakan deck dari MVP produk kamu.';
    //         } else {
    //             $data_email['text_kategori']          = 'Jangan lupa untuk lengkapi profile kamu di <a href="https://shehacks.id/" target="_blank">website SheHacks 2023</a>, dan sertakan masalah dan ide inovasi yang menjadi solusi hadapi masalah tersebut.';
    //         }

    //         $data_email['url_verifikasi']= base_url().'register/verifikasi?token_user='.$data_verif['token_user'].'&token_verf='.$data_verif['token_verifikasi'];
            
    //         $data_email['nama']          = $get_user['nama'];
    //         $data_email['email']         = $get_user['email'];
    //         $data_email['telp']          = $get_user['telp'];

    //         $message  = $this->load->view('email/register_email_terbaru', $data_email, TRUE);
    //         $get_konf = $this->db->query("SELECT * FROM tb_admin_konf_email WHERE id = 1 ")->row_array();

    //         $mail = new PHPMailer;
    //         $mail->isSMTP();
    //         $mail->Host       = $get_konf['host'];
    //         $mail->SMTPAuth   = $get_konf['smtpauth'];
    //         $mail->Username   = $get_konf['email'];
    //         $mail->Password   = $get_konf['password'];
    //         $mail->SMTPSecure = $get_konf['smtpsecure'];
    //         $mail->Port       = $get_konf['port'];
    //         $mail->Subject    = 'Verifikasi email Anda untuk SHEHACKS';
    //         $mail->setFrom($get_konf['email'], $get_konf['setfrom']);
    //         $mail->addAddress($data_email['email']);
    //         $mail->isHTML(true);
    //         $mail->AddEmbeddedImage(FCPATH.'assets/front/img/banner-popup.png', 'logo_email');
    //         $mail->MsgHTML(stripslashes($message));
    //         $mail->send();

    //     } 
    // }
    
}