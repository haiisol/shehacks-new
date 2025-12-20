<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct()
    {
        parent::__construct();
    }
    
	public function index()
	{   
        $referer = isset($_SERVER['HTTP_REFERER']) ? sanitize_header($_SERVER['HTTP_REFERER']) : '';

        if($this->session->userdata('logged_in_front') == TRUE) {
            redirect('dashboard');
        } 
        else {
            $data['title']       = '';
            $data['description'] = '';
            $data['keywords']    = '';
            $data['page']        = 'auth/login';
            $this->load->view('index', $data);
        }
	}

    function login_verify() 
    {   
        if($this->session->userdata('2fa_id_user') == FALSE) {
            redirect('login');
        } 
        else {
            $data['title']       = '2FA Verify';
            $data['description'] = '';
            $data['keywords']    = '';
            $data['page']        = 'auth/login_verify';
            $this->load->view('index', $data);
        }
    }

    function post_login()
    {   
        try {

            // ---- SANITASI INPUT ---- //
            $email      = secure_input($this->input->post('email', TRUE));
            $password   = $this->input->post('password', TRUE);
            $uri_string = secure_input($this->input->post('uri_string', TRUE));

            // Sanitasi referer untuk mencegah XSS via header
            $safe_referer = sanitize_header($this->input->server('HTTP_REFERER'));

            // Hardening — jika ada referer yang aneh, kosongkan
            if (strpos($safe_referer, base_url()) === FALSE) {
                $safe_referer = '';
            }

            $ip = $this->input->ip_address();

            // --- RATE LIMIT (tidak saya ubah) --- //
            $attempts_failed = $this->main_model->get_login_attempts($ip, $email, 'failed');
            if ($attempts_failed >= 5) {
                return json_response([
                    'status'  => 0,
                    'message' => 'Terlalu banyak percobaan login. Coba lagi dalam 15 menit.'
                ]);
            }

            $attempts_success = $this->main_model->get_login_attempts($ip, $email, 'success');
            if ($attempts_success >= 3) {
                return json_response([
                    'status'  => 0,
                    'message' => 'Terlalu banyak permintaan OTP. Coba lagi dalam 15 menit.'
                ]);
            }

            // ---- PROSES LOGIN ORIGINAL ---- //
            $this->db->select('id_user, email, password, status');
            $this->db->where('email', $email);
            $user = $this->db->get('tb_user')->row_array();

            if ($user && md5($password) === $user['password']) {

                if ($user['status'] == 0) {
                    $this->main_model->record_login_attempt($ip, $email, 'failed');
                    sleep(1);
                    $response = [
                        'status'  => 0,
                        'message' => 'Gagal, Akun email belum diverifikasi.'
                    ];
                } 
                else {
                    // gunakan referer yang aman
                    $this->fa_handle($user, $uri_string);
                    $this->main_model->record_login_attempt($ip, $email, 'success');
                    $this->main_model->clear_login_attempts($ip, $email, 'failed');

                    $response = [
                        'status'  => 1,
                        'message' => 'Success'
                    ];
                }
            }
            else {
                $this->main_model->record_login_attempt($ip, $email, 'failed');
                sleep(1);

                $response = [
                    'status'  => 0,
                    'message' => 'Email Pengguna atau Kata Sandi salah.'
                ];
            }
        
        } catch (Throwable $e) {

            log_message('error', 'Login error: ' . $e->getMessage());

            $response = [
                'status'  => 0,
                'message' => 'Email Pengguna atau Kata Sandi salah.'
            ];
        }

        json_response($response);
    }

    function fa_handle($data, $uri_string) 
    {
        $generate_code = rand(100000, 999999);

        $dataInsert2fa['id_user']      = $data['id_user'];
        $dataInsert2fa['code']         = $generate_code;
        $dataInsert2fa['access_policy']= 'FE';
        $dataInsert2fa['code_encrypt'] = encrypt_url($generate_code);
        $dataInsert2fa['date_create']  = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');
        $dataInsert2fa['date_expired'] = date_create('now', timezone_open('Asia/Jakarta'))->modify('+10 minutes')->format('Y-m-d H:i:s');
        $this->db->insert('tb_user_2fa', $dataInsert2fa);

        $data_email['id_user']         = $data['id_user'];
        $data_email['code']            = $generate_code;
        $this->send_email($data_email);

        $this->session->set_userdata('2fa_id_user', encrypt_url($data['id_user']));
        $this->session->set_userdata('uri_string', $uri_string);
    }

    function post_login_verify()
    {   
        $ip         = $this->input->ip_address();

        $row = array(
            "kode"        => $this->input->post('kode', TRUE),
        ); 

        $this->form_validation->set_data($row);
        $this->form_validation->set_rules('kode', 'kode', 'trim|required|numeric');

        if ( $this->form_validation->run() === false ) {
            $response['status']  = 0;
            $response['message'] = validation_errors();
            json_response($response);
            return;
        }
        
        $date_now       = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');
        $kode           = $this->input->post('kode');
        $sess_2fa_id    = decrypt_url($this->session->userdata('2fa_id_user'));
        $uri_string     = $this->session->userdata('uri_string');

        if ($sess_2fa_id) {

            $this->db->select('id, id_user, code_encrypt, date_expired');
            $this->db->where('id_user', $sess_2fa_id, TRUE);
            $this->db->where('code', $kode, TRUE);
            $this->db->where('access_policy', 'FE');
            $get_user2fa = $this->db->get('tb_user_2fa')->row_array();

            if ($get_user2fa) {

                if (strtotime($get_user2fa['date_expired']) > strtotime($date_now)) {

                    $session['key_auth']        = encrypt_url($get_user2fa['id_user']);
                    $session['key_token']       = $get_user2fa['code_encrypt'];
                    $session['logged_in_front'] = TRUE;

                    $this->session->set_userdata($session);
                    
                    if ($uri_string) {
                        $redirect = $uri_string;
                    } else {
                        $redirect = base_url().'dashboard';
                    }

                    // update last login
                    $data_update['last_login'] = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');
                    $this->main_model->update_data('tb_user', $data_update, 'id_user', $get_user2fa['id_user']);

                    // update 2FA
                    $data_update_2fa['date_verification'] = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');
                    $this->main_model->update_data('tb_user_2fa', $data_update_2fa, 'id', $get_user2fa['id']);

                    $this->session->unset_userdata('2fa_id_user');
                    $this->session->unset_userdata('uri_string');

                    $this->db->select('email');
                    $this->db->where('id_user', $sess_2fa_id, TRUE);
                    $get_user_clear = $this->db->get('tb_user')->row_array();
                    $this->main_model->clear_login_attempts($ip, $get_user_clear['email'], 'success');

                    $response['status']   = 1;
                    $response['message']  = 'Success';
                    $response['redirect'] = $redirect;
                }
                else {
                    $response['status']   = 0;
                    $response['message']  = 'Gagal, kode OTP sudah kadaluarsa.';
                    $response['redirect'] = base_url().'login';        
                }
            }
            else {
                $response['status']   = 0;
                $response['message']  = 'Failed, code not match.';
                $response['redirect'] = base_url().'login';    
            }
        }
        else {
            $response['status']   = 0;
            $response['message']  = 'Failed, session not found.';
            $response['redirect'] = base_url().'login';
        }

        json_response($response);
    }

    function send_email($data) 
    {
        require_once(APPPATH.'third_party/phpmailer/PHPMailerAutoload.php');

        $this->db->select('email');
        $this->db->where('id_user', $data['id_user'], TRUE);
        $get_user = $this->db->get('tb_user')->row_array();
       
        $data_email['code']          = $data['code'];
        $data_email['email']         = $get_user['email'];
        $data_email['param']         = 'Login';

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

    function send_email_text($data) 
    {
        $email = $data['user']['email'];
        $kode  = $data['code'];

        $this->db->select('email');
        $this->db->where('id', 1);
        $get_konf = $this->db->get('tb_admin_konf_email')->row_array();

        $from    = $get_konf['email'];
        $to      = $email;

        $subject = $kode." - Kode akses login akun SheHacks";

        $message = "
            Halo,

            Anda dapat menyalin dan menggunakan kode dibawah ini untuk akses login akun Shehacks :

            ".$kode."

            Kode ini akan kedaluwarsa setelah 10 menit atau jika Anda login ulang.

            Terima Kasih,

            Tim SheHacks";

        $headers = "From:" . $from;

        mail($to, $subject, $message, $headers);
    }

	function logout()
    {   
        $id_user        = key_auth();
        $key_token      = $this->session->userdata('key_token');

        $this->db->query("
            UPDATE tb_user_2fa SET logout_status = 'true'
            WHERE  access_policy = 'FE'
            AND id_user = '".$id_user."' 
            AND code_encrypt = '".$key_token."' ");

        $this->session->unset_userdata('key_auth');
        $this->session->unset_userdata('key_token');
        $this->session->unset_userdata('uri_string');
        $this->session->unset_userdata('logged_in_front');
        $this->session->sess_destroy();

        redirect('');
    }


// --------------------------- change password --------------------------- 
    function cek_password_lama()
    {
        $id_user = key_auth();
        $query = $this->db->query("SELECT password FROM tb_user WHERE id_user=".$id_user." ")->row_array();

        if ($query['password'] == md5($this->input->post('value'))) {
            $response['status'] = 1;
        } else {
            $response['status'] = 0;
        }

        json_response($response);
    }
// --------------------------- end change password --------------------------- 

}
