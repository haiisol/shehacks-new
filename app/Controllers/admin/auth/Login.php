<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }


    public function index()
    {
        if($this->session->userdata('logged_in_admin') == TRUE) {
            redirect('admin/dashboard/dashboard');
        } 
        else {
            $data['title']       = 'Login';
            $data['description'] = '';
            $data['keywords']    = '';
            $data['page']        = 'admin/auth/login';
            $this->load->view('admin/index', $data);
        }
    }

    function login_verify() 
    {   
        if($this->session->userdata('2fa_id_admin') == FALSE) {
            redirect('panel');
        } 
        else {
            $data['title']       = '2FA Verify';
            $data['description'] = '';
            $data['keywords']    = '';
            $data['page']        = 'admin/auth/login_verify';
            $this->load->view('admin/index', $data);
        }
    }

    function post_login()
    {   
        $row = array(
            "email_admin"        => $this->input->post('email_admin', TRUE),
            "password_admin"     => $this->input->post('password_admin', TRUE),
        ); 

        $this->form_validation->set_data($row);
        $this->form_validation->set_rules('email_admin', 'email_admin', 'trim|required|valid_email');
        $this->form_validation->set_rules('password_admin', 'password_admin', 'trim|required');

        if ( $this->form_validation->run() === false ) {
            $response['status']  = 0;
            $response['message'] = validation_errors();
            json_response($response);
            return;
        }

        $email    = $this->input->post('email_admin', TRUE);
        $password = md5($this->input->post('password_admin', TRUE));

        $this->db->select('*');
        $this->db->where('email_admin', $email, TRUE);
        $query   = $this->db->get('tb_admin_user')->row_array();

        if($query)
        {
           if($password == $query['password_admin']) {

                $this->fa_handle($query);
                
                $response['status']   = 1;
                $response['message']  = 'Success, Login Akun';
                $response['redirect'] = base_url().'panel/auth-verify';
            }
            else
            {
                $response['status']  = 0;
                $response['message'] = 'Email Pengguna atau Kata Sandi salah.';
            } 
        }
        else
        {
            $response['status']  = 0;
            $response['message'] = 'Email Pengguna atau Kata Sandi salah.';
        }

        json_response($response);
    }

    function fa_handle($data) 
    {
        $generate_code = rand(100000, 999999);

        $dataInsert2fa['id_user']      = $data['id_admin'];
        $dataInsert2fa['code']         = $generate_code;
        $dataInsert2fa['access_policy']= 'CMS';
        $dataInsert2fa['code_encrypt'] = encrypt_url($generate_code);
        $dataInsert2fa['date_create']  = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');
        $dataInsert2fa['date_expired'] = date_create('now', timezone_open('Asia/Jakarta'))->modify('+10 minutes')->format('Y-m-d H:i:s');
        $this->db->insert('tb_user_2fa', $dataInsert2fa);

        $data_email['id_user']         = $data['id_admin'];
        $data_email['code']            = $generate_code;

        $this->send_email($data_email);

        $this->session->set_userdata('2fa_id_admin', encrypt_url($data['id_admin']));
    }

    function post_login_verify()
    {   
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
        $kode           = $this->input->post('kode', TRUE);
        $sess_2fa_id    = decrypt_url($this->session->userdata('2fa_id_admin'));

        if ($sess_2fa_id) {

            $this->db->select('id, id_user, code_encrypt, date_expired');
            $this->db->where('id_user', $sess_2fa_id, TRUE);
            $this->db->where('code', $kode, TRUE);
            $this->db->where('access_policy', 'CMS');
            $get_user2fa = $this->db->get('tb_user_2fa')->row_array();

            if ($get_user2fa) {

                if (strtotime($get_user2fa['date_expired']) > strtotime($date_now)) {

                    $session['key_auth_admin']        = encrypt_url($get_user2fa['id_user']);
                    $session['key_token_admin']       = $get_user2fa['code_encrypt'];
                    $session['logged_in_admin']       = TRUE;

                    $this->session->set_userdata($session);

                    // update last login
                    $data_update['terakhir_login_admin'] = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');
                    $this->main_model->update_data('tb_admin_user', $data_update, 'id_admin', $get_user2fa['id_user']);

                    // update 2FA
                    $data_update_2fa['date_verification'] = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');
                    $this->main_model->update_data('tb_user_2fa', $data_update_2fa, 'id', $get_user2fa['id']);

                    $this->session->unset_userdata('2fa_id_admin');

                    $redirect               = base_url().'panel-dashboard';
                    $response['status']     = 1;
                    $response['message']    = 'Success';
                    $response['redirect']   = $redirect;
                }
                else {
                    $response['status']     = 0;
                    $response['message']    = 'Failed, session is expired.';        
                    $response['test']       = $get_user2fa['date_expired'].' | '.$date_now;
                }
            }
            else {
                $response['status']   = 0;
                $response['message']  = 'Failed, code not match.';   
            }
        }
        else {
            $response['status']   = 0;
            $response['message']  = 'Failed, session not found.';
        }

        json_response($response);
    }

    function send_email($data) 
    {
        require_once(APPPATH.'third_party/phpmailer/PHPMailerAutoload.php');

        $get_user = $this->db->query("
            SELECT email_admin
            FROM tb_admin_user 
            WHERE id_admin = '".$data['id_user']."' 
            ")->row_array();

       
        $data_email['code']          = $data['code'];
        $data_email['email']         = $get_user['email_admin'];
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

	function logout()
    {   
        $this->session->unset_userdata('id_admin');
        $this->session->unset_userdata('key_auth_admin');
        $this->session->unset_userdata('key_token_admin');
        $this->session->unset_userdata('logged_in_admin');
        $this->session->sess_destroy();

        redirect('panel');
    }

}

