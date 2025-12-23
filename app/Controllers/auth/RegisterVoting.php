<?php
namespace App\Controllers\Auth;

use Config\Database;
use Config\Services;
use App\Models\MainModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Controllers\FrontController;

class RegisterVoting extends FrontController
{
    protected $db;
    protected $session;
    protected $mainModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->session = Services::session();
        $this->mainModel = new MainModel();
    }

    public function index()
    {
        if (!$this->data['register_button']) {
            return redirect()->to('/');
        }

        if ($this->session->get('logged_in_front')) {
            return redirect()->to('dashboard');
        }

        $data = [
            'title' => 'Register',
            'page' => 'auth/register_voting'
        ];

        $this->data = array_merge($this->data, $data);

        return view('index', $this->data);
    }

    function post_register_voting()
    {
        $kode_user = strtoupper(substr(uniqid(), 7)) . date('my');
        $token = md5(date('his') . date('d') . uniqid() . date('my'));
        $email = sanitize_input($this->request->getPost('email'));

        $data = [
            'kode_user' => $kode_user,
            'channel' => '2025',
            'token' => $token,
            'status' => 1,
            'date_create' => date_create('now', timezone_open('Asia/Jakarta'))
                ->format('Y-m-d H:i:s'),
            'kategori_user' => '',
            'nama' => sanitize_input($this->session->get('nama')),
            'telp' => sanitize_input($this->session->get('telp')),
            'email' => $email,
            'password' => md5($this->request->getPost('password')), // legacy
            'password_text' => $this->request->getPost('password'),
        ];

        $cek_user = $this->db->table('tb_user')
            ->select('id_user')
            ->where('email', $email)
            ->get()
            ->getRowArray();

        if ($cek_user) {
            return json_response([
                'status' => 0,
                'message' => 'Gagal, email sudah terdaftar.',
            ]);
        }

        $query = $this->db->table('tb_user')->insert($data);
        $id_user = $this->db->insertID();

        if (!$query) {
            return json_response([
                'status' => 0,
                'message' => 'Registrasi gagal, Silahkan coba lagi.',
            ]);
        }

        // clear session
        _clear_session();

        // 2FA handle
        $fa_data = fa_handle($id_user);

        if ($fa_data !== false) {
            $this->send_email($fa_data);
        }

        return json_response([
            'status' => 1,
            'message' => 'Sukses',
        ]);
    }

    function send_email($data)
    {

        $get_user = $this->db->table('tb_user')
            ->select('email')
            ->where('id_user', $data['id_user'])
            ->get()
            ->getRowArray();

        if (!$get_user) {
            return;
        }

        $data_email = [
            'code' => $data['code'],
            'email' => $get_user['email'],
            'param' => 'Mendaftar',
        ];

        $message = view('email/2fa_email_login', $data_email);

        $get_konf = $this->db->table('tb_admin_konf_email')
            ->where('id', 1)
            ->get()
            ->getRowArray();

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $get_konf['host'];
            $mail->SMTPAuth = $get_konf['smtpauth'];
            $mail->Username = $get_konf['email'];
            $mail->Password = $get_konf['password'];
            $mail->SMTPSecure = $get_konf['smtpsecure'];
            $mail->Port = $get_konf['port'];
            $mail->Subject = $data['code'] . ' - Kode akses login akun SheHacks';
            $mail->setFrom($get_konf['email'], $get_konf['setfrom']);
            $mail->addAddress($data_email['email']);
            $mail->isHTML(true);
            $mail->MsgHTML(stripslashes($message));
            $mail->send();
        } catch (Exception $e) {
            log_message('error', 'Email failed: ' . $mail->ErrorInfo);
        }

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