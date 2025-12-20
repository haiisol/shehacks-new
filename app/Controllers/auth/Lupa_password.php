<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Lupa_password extends CI_Controller {
	
    public function __construct()
    {
        parent::__construct();
    }

// --------------------------- reset password ---------------------------
	public function index()
	{
        $data['title']       = 'Lupa Password';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']        = 'auth/lupa_password';
        $this->load->view('index', $data);
    }

    function post_reset_password()
    {   
        $email      = $this->input->post('email', TRUE);
        $ip         = $this->input->ip_address();

        $row = array(
            "email"        => $this->input->post('email', TRUE),
        ); 

        $this->form_validation->set_data($row);
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');

        if ( $this->form_validation->run() === false ) {
            $response['status']  = 0;
            $response['message'] = validation_errors();
            json_response($response);
            return;
        }

        $get_user = $this->db->get_where('tb_user', ['email' => $email])->row_array();

        if ($get_user) {

            $attempts = $this->main_model->get_reset_password_attempts($ip, $email, 'submit_form');
            if ($attempts >= 3) {
                $response['status']  = 0;
                $response['message'] = 'Terlalu banyak permintaan. Coba lagi dalam 10 menit.';
            } else {
                
                $kode = $this->generateRandomString();

                // insert table
                $data['id_user']  = $get_user['id_user'];
                $data['email']    = $get_user['email'];
                $data['token']    = $kode;
                $data['time']     = date('Y-m-d H:i:s');
                $data['is_reset'] = 0;

                $query = $this->db->insert('tb_user_reset_password', $data);

                // email
                $data_email['email'] = $email;
                $data_email['kode']  = $kode;
                $this->send_email($data_email);
                //$this->send_email_text($data_email);

                if ($query) {

                    $this->main_model->record_reset_password_attempt($ip, $email, 'submit_form');

                    $response['status']  = 1;
                    $response['message'] = 'Berhasil mengirim email, silahkan cek email Anda pada Kotak Masuk / Spam.';
                } 
                else {

                    $response['status']  = 0;
                    $response['message'] = 'Gagal mengirim email.';

                }
            }
        } 
        else {

            $response['status']  = 0;
            $response['message'] = 'Gagal, Email pengguna tidak terdaftar.';
        }

        json_response($response);
    }

    function generateRandomString() 
    {
        $length           = 4;
        $characters       = '0123456789';
        $charactersLength = strlen($characters);

        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= $characters[rand(0, $charactersLength - 1)];
        }

        return $result;
    }

    function send_email($data) 
    {
        require_once(APPPATH.'third_party/phpmailer/PHPMailerAutoload.php');

        $data_email['email']    = $data['email'];
        $data_email['kode_otp'] = $data['kode'];

        $message  = $this->load->view('email/lupa_password_email', $data_email, TRUE);
        $get_konf = $this->db->query("SELECT * FROM tb_admin_konf_email WHERE id = 1 ")->row_array();

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host       = $get_konf['host'];
        $mail->SMTPAuth   = $get_konf['smtpauth'];
        $mail->Username   = $get_konf['email'];
        $mail->Password   = $get_konf['password'];
        $mail->SMTPSecure = $get_konf['smtpsecure'];
        $mail->Port       = $get_konf['port'];
        $mail->Subject    = $data_email['kode_otp'].' - Reset Password akun SheHacks';
        $mail->setFrom($get_konf['email'], $get_konf['setfrom']);
        $mail->addAddress($data_email['email']);
        $mail->isHTML(true);
        $mail->AddEmbeddedImage(FCPATH.'assets/front/img/logo-shehacks.png', 'logo_email');
        $mail->MsgHTML(stripslashes($message));
        $mail->send();
    }

    function send_email_text($data) 
    {
       
        $data_email['email']    = $data['email'];
        $data_email['kode_otp'] = $data['kode'];

        $get_konf = $this->db->query("SELECT * FROM tb_admin_konf_email WHERE id = 1 ")->row_array();

        $from    = $get_konf['email'];
        $to      = $get_user['email'];

        $subject = $data_email['kode_otp']." - Reset password akun SheHacks";

        $message = "

        Halo,

        Reset password akun SheHacks.

        Berikut kode verifikasi akun Anda, silahkan gunakan kode dibawah untuk reset password pada akun Anda

        Kode Verifikasi: ".$data_email['kode_otp']."

        Terima Kasih,

        Tim SheHacks Anda";

        $headers = "From:" . $from;

        mail($to,$subject,$message, $headers);

    }
// --------------------------- reset password ---------------------------


// --------------------------- verifikasi ---------------------------
    function verifikasi()
    {
        if ($this->session->userdata('logged_in_front') == FALSE) {

            $data['title']       = 'Verifikasi Email';
            $data['description'] = '';
            $data['keywords']    = '';
            $data['page']        = 'auth/lupa_password_verifikasi';
            $this->load->view('index', $data);
        } 
        else {
            redirect('');
        }
    }

    function post_kode_verifikasi()
    {   
        $email      = $this->input->post('email', TRUE);
        $kode       = $this->input->post('kode', TRUE);
        $ip         = $this->input->ip_address();

        $attempts = $this->main_model->get_reset_password_attempts($ip, $email, 'submit_kode');
        if ($attempts >= 5) {
            $response['status']  = 0;
            $response['message'] = 'Terlalu banyak permintaan. Coba lagi dalam 10 menit.';
        } else {

            $get_user = $this->db->get_where('tb_user', ['email' => $email])->row_array();

            if ($get_user) {

                $cek_otp = $this->db->where('id_user', $get_user['id_user'])
                   ->order_by('id', 'DESC')
                   ->limit(1)
                   ->get('tb_user_reset_password')
                   ->row_array();

                if ($cek_otp) {

                    if ($cek_otp['token'] == $kode) {

                        if ($cek_otp['is_reset'] == 0) {

                            // update status reset
                            $update['is_reset'] = 1;
                            $this->main_model->update_data('tb_user_reset_password', $update, 'id', $cek_otp['id']);

                            // set session
                            $session['sess_email_reset'] = $get_user['email'];
                            $session['sess_token_reset'] = $get_user['token'];
                            $this->session->set_userdata($session);

                            $this->main_model->clear_reset_password_attempts($ip, $email);

                            $response['status']  = 1;
                            $response['message'] = 'Success';
                        } 
                        else {

                            $response['status']  = 0;
                            $response['message'] = 'Gagal, kode verifikasi telah kadaluarsa.';

                            $this->main_model->record_reset_password_attempt($ip, $email, 'submit_kode');
                        }
                    } 
                    else {

                        $response['status']  = 0;
                        $response['message'] = 'Gagal, kode verikasi tidak sesuai.';

                        $this->main_model->record_reset_password_attempt($ip, $email, 'submit_kode');
                    }
                } 
                else {

                    $response['status']  = 0;
                    $response['message'] = 'Gagal, Silahkan coba lagi.';

                    $this->main_model->record_reset_password_attempt($ip, $email, 'submit_kode');
                }
            } 
            else {

                $response['status']  = 0;
                $response['message'] = 'Gagal, email tidak terdaftar.';

                $this->main_model->record_reset_password_attempt($ip, $email, 'submit_kode');
            }
        }

        json_response($response);
    }
// --------------------------- end verifikasi ---------------------------


// --------------------------- form password ---------------------------
    function form_lupa_password()
    {
        if ($this->session->userdata('logged_in_front') == FALSE) {

            if ($this->session->userdata('sess_token_reset'))
            {
                $data['title']       = 'Form Reset Password';
                $data['description'] = '';
                $data['keywords']    = '';
                $data['page']        = 'auth/lupa_password_form';
                $this->load->view('index', $data);
            }
            else {
                redirect('');
            }
        } 
        else {
            redirect('');
        }
    }

    function post_ganti_password()
    {
        $email      = $this->session->userdata('sess_email_reset', TRUE);
        $token      = $this->session->userdata('sess_token_reset', TRUE);
        $ip         = $this->input->ip_address();

        $attempts = $this->main_model->get_reset_password_attempts($ip, $email, 'submit_password');
        if ($attempts >= 5) {
            $response['status']  = 0;
            $response['message'] = 'Terlalu banyak permintaan. Coba lagi dalam 10 menit.';
        } else {

            if ($token)
            {   
                $password = $this->input->post('password', TRUE);

                $get_user = $this->db->select('id_user, token')
                     ->from('tb_user')
                     ->where('email', $email)
                     ->get()
                     ->row_array();

                if ($get_user) {

                    if ($get_user['token'] == $token ) {

                        $update['password']         = md5($password);
                        $update['password_text']    = $password;

                        $query = $this->main_model->update_data('tb_user', $update, 'id_user', $get_user['id_user']);

                        if ($query) {

                            $this->session->unset_userdata('sess_email_reset');
                            $this->session->unset_userdata('sess_token_reset');

                            $this->main_model->clear_reset_password_attempts($ip, $email);

                            $response['status']  = 1;
                            $response['message'] = 'Sukses, reset password';
                        } 
                        else {

                            $this->main_model->record_reset_password_attempt($ip, $email, 'submit_password');

                            $response['status']  = 0;
                            $response['message'] = 'Gagal, silahkan coba lagi';
                        }
                    } 
                    else {

                        $this->main_model->record_reset_password_attempt($ip, $email, 'submit_password');

                        $response['status']  = 0;
                        $response['message'] = 'Gagal, akses di block';
                    }
                } 
                else {

                    $this->main_model->record_reset_password_attempt($ip, $email, 'submit_password');

                    $response['status']  = 0;
                    $response['message'] = 'Gagal, email tidak terdaftar';
                }
            }
            else {

                $response['status']  = 2;
                $response['message'] = 'Gagal, sesi telah kadaluarsa';

                $this->main_model->record_reset_password_attempt($ip, $email, 'submit_password');
            }
        }
        
        json_response($response);
    }
// --------------------------- end form password ---------------------------

}