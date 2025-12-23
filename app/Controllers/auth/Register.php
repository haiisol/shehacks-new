<?php
namespace App\Controllers\Auth;

use App\Models\MainModel;
use Config\Database;
use App\Controllers\FrontController;

class Register extends FrontController
{
    protected $db;
    protected $session;
    protected $validation;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->session = session();
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
            'page' => 'auth/register'
        ];

        $this->data = array_merge($this->data, $data);

        return view('index', $this->data);
    }

    function announcement()
    {
        if ($this->session->get('logged_in_front')) {
            return redirect()->to('dashboard');
        }

        $data = [
            'title' => 'Register 2004',
            'page' => 'auth/register_announcement'
        ];

        $this->data = array_merge($this->data, $data);

        return view('index', $this->data);
    }

    function verifikasi()
    {
        $token_user = $this->request->getGet('token_user');
        $token_verf = $this->request->getGet('token_verf');

        if (!$token_user || !$token_verf) {
            return redirect()->to('/');
        }

        $query_ver = $this->db->table('tb_user_verifikasi_email')
            ->select('id_user, id_verifikasi, status_verifikasi')
            ->where('token_user', $token_user)
            ->where('token_verifikasi', $token_verf)
            ->get()
            ->getRowArray();

        if (!$query_ver) {
            return redirect()->to('/');
        }

        if ($query_ver['status_verifikasi'] === 'false') {
            $this->db->table('tb_user_verifikasi_email')
                ->where('id_verifikasi', $query_ver['id_verifikasi'])
                ->update(['status_verifikasi' => 'true']);
        }

        // set session
        $this->session->set([
            'id_user' => $query_ver['id_user'],
            'logged_in_front' => true,
        ]);

        // update last login
        $this->db->table('tb_user')
            ->where('id_user', $query_ver['id_user'])
            ->update([
                'last_login' => date_create('now', timezone_open('Asia/Jakarta'))
                    ->format('Y-m-d H:i:s'),
                'status' => '1',
            ]);

        return redirect()->to('dashboard');
    }


    function cek_email($email)
    {

        $count = $this->db->table('tb_user')
            ->where('email', $email)
            ->countAllResults();

        return $count === 0;
    }

    function post_register_profile()
    {
        $validation = service('validation');
        $kategori_user = $this->request->getPost('kategori_user');

        $rules = [
            'kategori_user' => 'required',
        ];

        if (!$validation->setRules($rules)->run(['kategori_user' => $kategori_user])) {
            return json_response([
                'status' => 0,
                'message' => $validation->listErrors(),
            ]);
        }

        $this->session->set(['kategori_user' => $kategori_user]);

        return json_response([
            'status' => 1,
            'message' => 'Success',
        ]);
    }

    function post_register_personal()
    {
        $dataPost = [
            'email' => $this->request->getPost('email'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'nama' => $this->request->getPost('nama'),
            'telp' => $this->request->getPost('telp'),
            'pendidikan' => $this->request->getPost('pendidikan'),
            'dapat_informasi' => $this->request->getPost('dapat_informasi'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
        ];

        $rules = [
            'email' => 'required|valid_email',
            'tanggal_lahir' => 'required',
            'nama' => 'required',
            'telp' => 'required',
            'pendidikan' => 'required',
            'dapat_informasi' => 'required',
            'jenis_kelamin' => 'required',
        ];

        if (!$this->validation->setRules($rules)->run($dataPost)) {
            return json_response([
                'status' => 0,
                'message' => $this->validation->listErrors(),
            ]);
        }

        // cek email
        if ($this->db->table('tb_user')->where('email', $dataPost['email'])->countAllResults() > 0) {
            return json_response([
                'status' => 0,
                'message' => 'Gagal, Email Pengguna sudah terdaftar',
            ]);
        }

        // cek telp
        if ($this->db->table('tb_user')->where('telp', $dataPost['telp'])->countAllResults() > 0) {
            return json_response([
                'status' => 0,
                'message' => 'Gagal, Telephone Pengguna sudah terdaftar',
            ]);
        }

        $this->session->set([
            'nama' => $dataPost['nama'],
            'telp' => $dataPost['telp'],
            'tanggal_lahir' => $dataPost['tanggal_lahir']
                ? \DateTime::createFromFormat('d/m/Y', trim($dataPost['tanggal_lahir']))->format('Y-m-d')
                : null,
            'pendidikan' => $dataPost['pendidikan'],
            'email' => $dataPost['email'],
            'password' => $this->request->getPost('password'),
            'dapat_informasi' => $dataPost['dapat_informasi'],
            'jenis_kelamin' => $dataPost['jenis_kelamin'],
        ]);

        return json_response([
            'data' => $this->session->get('nama'),
            'status' => 1,
            'message' => 'Success',
        ]);

    }

    public function validate_file_upload($field)
    {
        $file = $this->request->getFile($field);

        if (!$file || !$file->isValid()) {
            return true;
        }

        $allowedTypes = [
            'application/pdf',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ];

        return in_array($file->getMimeType(), $allowedTypes);
    }

    public function validate_image_upload($field)
    {
        $file = $this->request->getFile($field);

        if (!$file || !$file->isValid()) {
            return true;
        }

        $allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/jpg',
            'image/webp',
        ];

        return in_array($file->getMimeType(), $allowedTypes);
    }

    function post_register_startup()
    {
        if (!$this->validate_file_upload('file_pitchdeck')) {
            $errors[] = 'File pitchdeck harus PDF atau PowerPoint (.ppt/.pptx)';
        }

        if (!$this->validate_file_upload('file_pengajuan_kegiatan')) {
            $errors[] = 'File pengajuan kegiatan harus PDF atau PowerPoint (.ppt/.pptx)';
        }

        if (!$this->validate_image_upload('file_analisa_skorlife')) {
            $errors[] = 'File analisa skorlife harus berupa gambar';
        }

        if (!$this->validate_file_upload('file_profile_komunitas')) {
            $errors[] = 'File profile komunitas harus PDF atau PowerPoint';
        }

        if ($errors) {
            return json_response([
                'status' => 0,
                'message' => implode('<br>', $errors),
            ]);
        }

        $kode_user = strtoupper(substr(uniqid(), 7)) . date('my');
        $token = md5(date('his') . date('d') . uniqid() . date('my'));
        $kategori_user = sanitize_input($this->session->get('kategori_user'));

        $data = [
            'kode_user' => $kode_user,
            'token' => $token,
            'status' => 1,
            'channel' => '2025',
            'date_create' => date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s'),

            // personal
            'kategori_user' => $kategori_user,
            'nama' => sanitize_input($this->session->get('nama')),
            'telp' => sanitize_input($this->session->get('telp')),
            'tanggal_lahir' => sanitize_input($this->session->get('tanggal_lahir')),
            'pendidikan' => sanitize_input($this->session->get('pendidikan')),
            'umur' => get_umur($this->session->get('tanggal_lahir')),
            'email' => sanitize_input($this->session->get('email')),
            'password' => md5($this->session->get('password')),
            'password_text' => $this->session->get('password'),
            'dapat_informasi' => sanitize_input($this->session->get('dapat_informasi')),
            'jenis_kelamin' => sanitize_input($this->session->get('jenis_kelamin')),

            // startup
            'provinsi' => (int) $this->request->getPost('provinsi'),
        ];

        if ($kategori_user === 'Ideasi') {

            $data['problem_disekitar'] = sanitize_input($this->request->getPost('problem_disekitar'));
            $data['solusi_yang_dibuat'] = sanitize_input($this->request->getPost('solusi_yang_dibuat'));
            $data['jumlah_anggota'] = (int) $this->request->getPost('jumlah_anggota');

        } elseif ($kategori_user === 'MVP') {

            $data['nama_startup'] = sanitize_input($this->request->getPost('nama_startup'));
            $data['jumlah_anggota'] = (int) $this->request->getPost('jumlah_anggota');

            if ($file = $this->request->getFile('file_pitchdeck')) {
                $filename = 'Pitchdeck-' . strtolower(url_title($data['nama_startup']));
                $data['file_pitchdeck'] = $this->upload_file_pdf('file_pitchdeck', $filename);
            }

        } else {

            $data['kabupaten'] = (int) $this->request->getPost('kabupaten');
            $data['nama_komunitas'] = sanitize_input($this->request->getPost('nama_komunitas'));
            $data['jumlah_anggota_komunitas'] = (int) $this->request->getPost('jumlah_anggota_komunitas');
            $data['jabatan_komunitas'] = sanitize_input($this->request->getPost('jabatan_komunitas'));
            $data['akun_komunitas'] = sanitize_input($this->request->getPost('akun_komunitas'));

            if ($this->request->getFile('file_pengajuan_kegiatan')) {
                $filename = 'Pengajuan-kegiatan-' . strtolower(url_title($data['nama_komunitas']));
                $data['file_pengajuan_kegiatan'] = $this->upload_file_pdf('file_pengajuan_kegiatan', $filename);
            }

            if ($this->request->getFile('file_analisa_skorlife')) {
                $filename = 'Analisa-skorlife-' . strtolower(url_title($data['nama_komunitas']));
                $data['file_analisa_skorlife'] = $this->upload_file_image('file_analisa_skorlife', $filename);
            }

            if ($this->request->getFile('file_profile_komunitas')) {
                $filename = 'Profile-komunitas-' . strtolower(url_title($data['nama_komunitas']));
                $data['file_profile_komunitas'] = $this->upload_file_pdf('file_profile_komunitas', $filename);
            }
        }

        $cek_user = $this->db->table('tb_user')
            ->where('email', $data['email'])
            ->get()
            ->getRowArray();

        if ($cek_user) {
            return json_response([
                'status' => 0,
                'message' => 'Gagal, email sudah terdaftar.',
            ]);
        }

        $this->db->table('tb_user')->insert($data);
        $id_user = $this->db->insertID();

        if (!$id_user) {
            return json_response([
                'status' => 0,
                'message' => 'Registrasi gagal, silahkan coba lagi.',
            ]);
        }

        _clear_session();

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
        require_once(APPPATH . 'third_party/phpmailer/PHPMailerAutoload.php');

        $this->db->select('email');
        $this->db->where('id_user', $data['id_user'], TRUE);
        $get_user = $this->db->get('tb_user')->row_array();

        $data_email['code'] = $data['code'];
        $data_email['email'] = $get_user['email'];
        $data_email['param'] = 'Mendaftar';

        $message = $this->load->view('email/2fa_email_login', $data_email, TRUE);
        $get_konf = $this->db->query("SELECT * FROM tb_admin_konf_email WHERE id = 1 ")->row_array();

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $get_konf['host'];
        $mail->SMTPAuth = $get_konf['smtpauth'];
        $mail->Username = $get_konf['email'];
        $mail->Password = $get_konf['password'];
        $mail->SMTPSecure = $get_konf['smtpsecure'];
        $mail->Port = $get_konf['port'];
        $mail->Subject = $data['code'] . " - Kode akses login akun SheHacks";
        $mail->setFrom($get_konf['email'], $get_konf['setfrom']);
        $mail->addAddress($data_email['email']);
        $mail->isHTML(true);
        //$mail->AddEmbeddedImage(FCPATH.'assets/front/img/Image-registrasi-2024.png', 'logo_email');
        $mail->MsgHTML(stripslashes($message));
        $mail->send();

    }

    private function upload_file_pdf($name, $filename)
    {
        $kode_user = strtoupper(substr(uniqid(), 7)) . date('my');

        $config['upload_path'] = './file_media/file-user/';
        $config['allowed_types'] = 'pdf|ppt|pptx';
        $config['file_name'] = $filename . '-' . $kode_user;

        $this->upload->initialize($config);

        if ($this->upload->do_upload($name)) {
            return $this->upload->data('file_name');
        } else {
            return '';
        }
    }

    private function upload_file_image($name, $filename)
    {
        $kode_user = strtoupper(substr(uniqid(), 7)) . date('my');

        $config['upload_path'] = './file_media/file-user/';
        $config['allowed_types'] = 'jpg|jpeg|png|webp';
        $config['file_name'] = $filename . '-' . $kode_user;

        $this->upload->initialize($config);

        if ($this->upload->do_upload($name)) {
            return $this->upload->data('file_name');
        } else {
            return '';
        }
    }

    function send_email_text($id_user)
    {

        $get_konf = $this->db->query("SELECT * FROM tb_admin_konf_email WHERE id = 1 ")->row_array();

        $get_user = $this->db->query("
            SELECT nama, email, telp, token
            FROM tb_user 
            WHERE id_user = " . $id_user . " 
            ")->row_array();

        $data_verif['id_user'] = $id_user;
        $data_verif['token_user'] = md5(date('his') . date('d') . uniqid() . date('my'));
        $data_verif['token_verifikasi'] = $get_user['token'];
        $data_verif['status_verifikasi'] = 'false';

        $query_verf = $this->db->insert('tb_user_verifikasi_email', $data_verif);

        if ($query_verf) {

            $url_verifikasi = base_url() . 'register/verifikasi?token_user=' . $data_verif['token_user'] . '&token_verf=' . $data_verif['token_verifikasi'];

            // ini_set( 'display_errors', 1 );
            // error_reporting( E_ALL );

            $from = $get_konf['email'];
            $to = $get_user['email'];
            $subject = "Verifikasi email Anda untuk SHEHACKS";

            $message = "

            Halo,

            Klik link ini untuk memverifikasi alamat email Anda.

            " . $url_verifikasi . "

            Jika Anda tidak meminta verifikasi alamat ini, Anda dapat mengabaikan email ini.

            Terima Kasih,

            Tim SheHacks Anda";

            $headers = "From:" . $from;

            mail($to, $subject, $message, $headers);

        }

    }
}