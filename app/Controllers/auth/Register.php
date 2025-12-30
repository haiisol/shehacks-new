<?php
namespace App\Controllers\Auth;

use Config\Services;
use Config\Database;
use App\Controllers\FrontController;

class Register extends FrontController
{
    protected $db;
    protected $session;

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

        $get_pend = $this->mainModel->get_data_order("tb_master_pendidikan", "nama DESC");
        $get_mdi = $this->mainModel->get_data_where_order("tb_master_dapat_informasi", "0", "status_delete", "urutan ASC");
        $get_prov = $this->mainModel->get_data_order("tb_master_province", "name ASC");

        $data = [
            'title' => 'Register',
            'get_pend' => $get_pend,
            'get_mdi' => $get_mdi,
            'get_prov' => $get_prov,
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
        $validation = Services::validation();

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

        if (!$validation->setRules($rules)->run($dataPost)) {
            return json_response([
                'status' => 0,
                'message' => $validation->listErrors(),
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
        $errors = [];

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

        if (!empty($errors)) {
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
            $email = $data['email'];

            $dataEmail = [
                'code' => $fa_data['code'],
                'email' => $email,
                'param' => 'Mendaftar'
            ];

            $message = view('email/2fa_email_login', $dataEmail);
            $subject = $fa_data['code'] . " - Kode akses login akun SheHacks";

            $emailData = [
                'subject' => $subject,
                'message' => $message,
                'email' => $email
            ];

            send_email($emailData);
        }

        return json_response([
            'status' => 1,
            'message' => 'Sukses',
        ]);
    }

    private function upload_file_pdf(string $fieldName, string $filename): string
    {
        $file = $this->request->getFile($fieldName);

        if (!$file || !$file->isValid()) {
            return '';
        }

        $allowed = ['pdf', 'ppt', 'pptx'];
        $ext = $file->guessExtension();

        if (!in_array($ext, $allowed, true)) {
            return '';
        }

        $kodeUser = strtoupper(substr(uniqid(), 7)) . date('my');
        $newName = $filename . '-' . $kodeUser . '.' . $ext;

        $path = FCPATH . 'file_media/file-user/';

        if (!$file->hasMoved()) {
            $file->move($path, $newName);
            return $newName;
        }

        return '';
    }

    private function upload_file_image(string $fieldName, string $filename): string
    {
        $file = $this->request->getFile($fieldName);

        if (!$file || !$file->isValid()) {
            return '';
        }

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = $file->guessExtension();

        if (!in_array($ext, $allowed, true)) {
            return '';
        }

        $kodeUser = strtoupper(substr(uniqid(), 7)) . date('my');
        $newName = $filename . '-' . $kodeUser . '.' . $ext;

        $path = FCPATH . 'file_media/file-user/';

        if (!$file->hasMoved()) {
            $file->move($path, $newName);
            return $newName;
        }

        return '';
    }
}