<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Email_akun extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
    }
    
    public function index()
    {
        $data = $this->main_model->check_access('akun_email');
        $cact = $this->main_model->check_access_action('akun_email');
        
        $data['access_edit'] = $cact['access_edit'];

        $data['title']       = 'Setting Email Akun';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']        = 'admin/setting/email_akun';
        $this->load->view('admin/index', $data);
    }

    function get_data()
    {   
        $this->main_model->check_access('akun_email');
        $query = $this->db->query("SELECT * FROM tb_admin_konf_email WHERE id = 1")->row_array();

        json_response($query);
    }

    function edit_data()
    {
        $this->main_model->check_access('akun_email');
        $cact = $this->main_model->check_access_action('akun_email');

        if ($cact['access_edit'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses edit.';
        } else {
    
            $id             = $this->input->post('id', TRUE);

            $this->load->library('form_validation');
            $row = array(
                "id"        => $id,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $data['host']               = $this->input->post('host', TRUE);
            $data['smtpauth']           = $this->input->post('smtpauth', TRUE);
            $data['email']              = $this->input->post('email', TRUE);
            $data['password']           = $this->input->post('password', TRUE);
            $data['smtpsecure']         = $this->input->post('smtpsecure', TRUE);
            $data['port']               = $this->input->post('port', TRUE);
            $data['setfrom']            = $this->input->post('setfrom', TRUE);
            $data['email_subject']      = $this->input->post('email_subject', TRUE);

            $query = $this->main_model->update_data('tb_admin_konf_email', $data, 'id', $id);

            if ($query) {
                $response['status']  = 1;
                $response['message'] = 'Data berhasil disimpan.';
            } else {
                $response['status']  = 0;
                $response['message'] = 'Gagal menyimpan data.';
            }

        }

        json_response($response);
    }

    function testing_kirim() 
    {
        $get_email     = $this->db->query("SELECT * FROM tb_admin_konf_email WHERE id = 1 ")->row_array();

        $data_email    = $this->input->post('email_test');
        $message       = '----- Email Testing -----';

        require_once(APPPATH."third_party/phpmailer/PHPMailerAutoload.php");

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host       = $get_email['host'];
        $mail->SMTPAuth   = $get_email['smtpauth'];
        // $mail->SMTPDebug  = 1;
        $mail->Username   = $get_email['email'];
        $mail->Password   = $get_email['password'];
        $mail->SMTPSecure = $get_email['smtpsecure'];
        $mail->Port       = $get_email['port'];
        $mail->Subject    = 'Email Account Test - '.$get_email['email'];
        $mail->setFrom($data_email, $get_email['setfrom']);
        $mail->addAddress($data_email);
        $mail->isHTML(true);
        $mail->MsgHTML(stripslashes($message));

        if(!$mail->send()) {
            $response['status']  = 0;
            $response['message'] = 'Gagal mengirim email';
            $response['info']    = $mail->ErrorInfo;
        } else {
            $response['status']  = 1;
            $response['message'] = 'Berhasil mengirim email';
            $response['info']    = 'Email berhasil terkirim, silahkan cek email';
        }

        json_response($response);
    }

}
