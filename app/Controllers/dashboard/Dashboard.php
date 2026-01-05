<?php
namespace App\Controllers\Dashboard;

use App\Controllers\FrontController;
use Config\Database;
use Config\Services;
use DateTime;

class Dashboard extends FrontController
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
        $id_user = key_auth();

        $dataUser = $this->db->table('tb_user')
            ->select('kategori_user, nama, pp_nama')
            ->where('id_user', $id_user)
            ->get()
            ->getRowArray();

        $data = [
            'data_user' => $dataUser,
            'page' => 'dashboard/dashboard_index'
        ];

        $this->data = array_merge($this->data, $data);

        return view('index', $this->data);
    }

    function get_query_user()
    {
        $id_user = key_auth();

        return $this->db->table('tb_user')
            ->where('id_user', $id_user)
            ->get()
            ->getRowArray();
    }

    // ---------------------------  popup-event ---------------------------
    function get_modal_event()
    {
        $channel_check = $this->session->get('channel_check');
        $id_user = key_auth();

        $query = $this->db->table('tb_user')
            ->select('channel')
            ->like('channel', '2024')
            ->where('id_user', $id_user)
            ->get()
            ->getRowArray();

        if ($query || $channel_check) {
            return json_response(['status' => 0]);
        }

        return json_response(['status' => 1]);
    }

    function close_modal_event()
    {
        $this->session->set('channel_check', true);
        return json_response(['status' => 1]);
    }

    function generate_channel()
    {
        $id_user = key_auth();

        $data = $this->db->table('tb_user')
            ->select('channel')
            ->where('id_user', $id_user)
            ->get()
            ->getRowArray();

        if (!$data) {
            return json_response(['status' => 0]);
        }

        $channel = $data['channel'] . ', 2025';

        $update = $this->db->table('tb_user')
            ->where('id_user', $id_user)
            ->update(['channel' => $channel]);

        if ($update) {
            return json_response([
                'status' => 1,
                'redirect' => base_url('dashboard?profile')
            ]);
        }

        return json_response(['status' => 0]);
    }

    // ---------------------------  end popup-event ---------------------------

    function get_page()
    {
        $param_pg = $this->request->getGet('param_pg');
        $id_user = key_auth();

        $response['param_pg'] = $param_pg;

        if ($param_pg == 'dashboard') {
            $query = $this->db->table('tb_user')
                ->select('kategori_user')
                ->where('id_user', $id_user)
                ->get()
                ->getRowArray();

            $data['kategori_user'] = $query['kategori_user'] ?? '';

            $response['url'] = 'dashboard';
            $response['title'] = 'Dashboard';
            $response['page'] = view('dashboard/pg_dashboard', $data);
        } elseif ($param_pg == 'profile') {
            $get_pend = $this->mainModel->get_data_order("tb_master_pendidikan", "nama DESC");
            $get_mdi = $this->mainModel->get_data_order("tb_master_dapat_informasi", "urutan ASC");
            $get_prov = $this->mainModel->get_data_order("tb_master_province", "name ASC");

            $data = [
                'get_pend' => $get_pend,
                'get_mdi' => $get_mdi,
                'get_prov' => $get_prov
            ];

            $response['url'] = 'dashboard?profile';
            $response['title'] = 'Profile';
            $response['page'] = view('dashboard/pg_profile', $data);
        } elseif ($param_pg == 'pilot_project') {
            $response['url'] = 'dashboard?pilot_project';
            $response['title'] = 'Pilot Project';
            $response['page'] = view('dashboard/pg_pilot_project');
        } elseif ($param_pg == 'password') {
            $response['url'] = 'dashboard?password';
            $response['title'] = 'Change Password';
            $response['page'] = view('dashboard/pg_password');
        } else {
            return json_response(['status' => 0, 'message' => 'Invalid page']);
        }

        $response['description'] = '';
        $response['keywords'] = '';

        return json_response($response);
    }

    function fetch_data_dashboard()
    {
        $id_user = key_auth();

        $query = $this->db->table('tb_user')
            ->select('nama,email')
            ->where('id_user', $id_user)
            ->get()
            ->getRowArray();

        return json_response([
            'nama' => $query['nama'] ?? '',
            'email' => $query['email'] ?? ''
        ]);
    }


    // --------------------------- profile ---------------------------
    function fetch_data_profile()
    {
        $id_user = key_auth();

        $query = $this->db->table('tb_user')
            ->where('id_user', $id_user)
            ->get()
            ->getRowArray();

        if (!$query) {
            return json_response([
                'status' => 0,
                'message' => 'User not found'
            ]);
        }

        $tanggal = null;
        if (!empty($query['tanggal_lahir'])) {
            $tanggal = DateTime::createFromFormat('Y-m-d', trim($query['tanggal_lahir']))
                ->format('d/m/Y');
        }

        $response = [
            'token' => $query['token'] ?? '',
            'kategori_user' => $query['kategori_user'] ?? '',
            'nama' => $query['nama'] ?? '',
            'telp' => $query['telp'] ?? '',
            'email' => $query['email'] ?? '',
            'tanggal_lahir' => $tanggal,
            'pendidikan' => $query['pendidikan'] ?? '',
            'jenis_kelamin' => $query['jenis_kelamin'] ?? '',
            'dapat_informasi' => $query['dapat_informasi'] ?? '',
            'provinsi' => $query['provinsi'] ?? '',
            'kabupaten' => $query['kabupaten'] ?? '',
            'problem_disekitar' => $query['problem_disekitar'] ?? '',
            'solusi_yang_dibuat' => $query['solusi_yang_dibuat'] ?? '',
            'nama_komunitas' => $query['nama_komunitas'] ?? '',
            'jumlah_anggota_komunitas' => $query['jumlah_anggota_komunitas'] ?? '',
            'jabatan_komunitas' => $query['jabatan_komunitas'] ?? '',
            'akun_komunitas' => $query['akun_komunitas'] ?? '',
            'nama_startup' => $query['nama_startup'] ?? '',
            'jumlah_anggota' => $query['jumlah_anggota'] ?? '',
            'pp_background_masalah' => $query['pp_background_masalah'] ?? '',
            'pp_nama' => $query['pp_nama'] ?? '',
            'pp_deskripsi' => $query['pp_deskripsi'] ?? '',
            'pp_timeline' => $query['pp_timeline'] ?? '',
            'pp_target' => $query['pp_target'] ?? '',
            'pp_potential_partner' => $query['pp_potential_partner'] ?? '',
            'pp_kebutuhan_ahli' => $query['pp_kebutuhan_ahli'] ?? '',
            'pp_pembeda' => $query['pp_pembeda'] ?? '',
            'file_pitchdeck' => $query['file_pitchdeck'] ?? '',
            'file_profile_komunitas' => $query['file_profile_komunitas'] ?? '',
            'file_analisa_skorlife' => $query['file_analisa_skorlife'] ?? '',
            'file_pengajuan_kegiatan' => $query['file_pengajuan_kegiatan'] ?? '',
            'url_file_pitchdeck' => base_url('file_media/file-user/' . $query['file_pitchdeck']),
            'url_file_profile_komunitas' => base_url('file_media/file-user/' . $query['file_profile_komunitas']),
            'url_file_analisa_skorlife' => base_url('file_media/file-user/' . $query['file_analisa_skorlife']),
            'url_file_pengajuan_kegiatan' => base_url('file_media/file-user/' . $query['file_pengajuan_kegiatan']),
        ];

        return json_response($response);
    }

    function cek_phone()
    {
        $validation = Services::validation();

        $data = [
            'value' => $this->request->getPost('value')
        ];

        $validation->setRules([
            'value' => 'required|numeric'
        ]);

        if (!$validation->run($data)) {
            return json_response([
                'status' => 0,
                'message' => $validation->getErrors()
            ]);
        }

        $id_user = key_auth();
        $value = htmlspecialchars($data['value'], ENT_QUOTES, 'UTF-8');

        $user = $this->db->table('tb_user')
            ->select('telp')
            ->where('id_user', $id_user)
            ->get()
            ->getRowArray();

        if ($user && $user['telp'] == $value) {
            return json_response([
                'status' => 1,
                'message' => ''
            ]);
        }

        $exists = $this->db->table('tb_user')
            ->select('id_user')
            ->where('telp', $value)
            ->get()
            ->getResultArray();

        return json_response([
            'status' => $exists ? 0 : 1,
            'message' => ''
        ]);
    }

    function cek_file_pitchdeck()
    {
        $id_user = key_auth();

        $query = $this->db->table('tb_user')
            ->select('file_pitchdeck')
            ->where('id_user', $id_user)
            ->get()
            ->getRowArray();

        $status = (!empty($query['file_pitchdeck'])) ? 0 : 1;

        return json_response([
            'status' => $status,
            'message' => ''
        ]);
    }

    function cek_file_pengajuan_kegiatan()
    {
        $id_user = key_auth();

        $query = $this->db->table('tb_user')
            ->select('file_pengajuan_kegiatan')
            ->where('id_user', $id_user)
            ->get()
            ->getRowArray();

        $status = (!empty($query['file_pengajuan_kegiatan'])) ? 0 : 1;

        return json_response([
            'status' => $status,
            'message' => ''
        ]);
    }

    function post_update_profile()
    {
        $id_user = key_auth();

        $queryCek = $this->db->table('tb_user')
            ->select('id_user')
            ->where('id_user', $id_user)
            ->where('token', $this->request->getPost('token_user'))
            ->get()
            ->getRowArray();

        if (!$queryCek) {
            return redirect()->to('logout');
        }

        $tanggal_lahir = sanitize_input($this->request->getPost('tanggal_lahir'));
        $nama = sanitize_input($this->request->getPost('nama'));
        $telp = sanitize_input($this->request->getPost('telp'));
        $pendidikan = sanitize_input($this->request->getPost('pendidikan'));
        $dapat_informasi = sanitize_input($this->request->getPost('dapat_informasi'));
        $jenis_kelamin = sanitize_input($this->request->getPost('jenis_kelamin'));
        $provinsi = sanitize_input($this->request->getPost('provinsi'));

        $row = [
            "tanggal_lahir" => $tanggal_lahir,
            "nama" => $nama,
            "telp" => $telp,
            "pendidikan" => $pendidikan,
            "dapat_informasi" => $dapat_informasi,
            "jenis_kelamin" => $jenis_kelamin,
            "provinsi" => $provinsi,
        ];

        $validation = Services::validation();
        $validation->setRules([
            'tanggal_lahir' => 'required',
            'nama' => 'required',
            'telp' => 'required',
            'pendidikan' => 'required',
            'dapat_informasi' => 'required',
            'jenis_kelamin' => 'required',
            'provinsi' => 'required|numeric',
        ]);

        if (!$validation->run($row)) {
            return json_response([
                'status' => 0,
                'message' => $validation->getErrors()
            ]);
        }

        // Convert date
        $tgl_lahir = DateTime::createFromFormat('d/m/Y', trim($tanggal_lahir))
            ->format('Y-m-d');

        $data = [
            'nama' => $nama,
            'telp' => $telp,
            'tanggal_lahir' => $tgl_lahir,
            'umur' => get_umur($tgl_lahir),
            'pendidikan' => $pendidikan,
            'jenis_kelamin' => $jenis_kelamin,
            'dapat_informasi' => $dapat_informasi,
            'provinsi' => $provinsi
        ];

        // kategori user
        if ($this->request->getPost('kategori_user_pilihan')) {
            $data['kategori_user'] = $this->request->getPost('kategori_user_pilihan', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $kategori_user = $data['kategori_user'];
        } else {
            $cekKategori = $this->db->table('tb_user')
                ->select('kategori_user')
                ->where('id_user', $id_user)
                ->get()
                ->getRowArray();

            $kategori_user = $cekKategori['kategori_user'];
        }

        // ===== IDEASI =====
        if ($kategori_user == 'Ideasi') {
            $data['problem_disekitar'] = sanitize_input($this->request->getPost('problem_disekitar'));
            $data['solusi_yang_dibuat'] = sanitize_input($this->request->getPost('solusi_yang_dibuat'));
            $data['jumlah_anggota'] = (int) $this->request->getPost('jumlah_anggota');

            // ===== MVP =====
        } elseif ($kategori_user == 'MVP') {

            $data['nama_startup'] = sanitize_input($this->request->getPost('nama_startup'));
            $data['jumlah_anggota'] = (int) $this->request->getPost('jumlah_anggota');

            if ($file = $this->request->getFile('file_pitchdeck')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $filename = 'Pitchdeck-' . strtolower(url_title($data['nama_startup']));
                    $data['file_pitchdeck'] = $this->upload_file_pdf('file_pitchdeck', $filename);
                }
            }

            // ===== COMMUNITY =====
        } else {
            $data['kabupaten'] = (int) $this->request->getPost('kabupaten');
            $data['nama_komunitas'] = sanitize_input($this->request->getPost('nama_komunitas'));
            $data['jumlah_anggota_komunitas'] = sanitize_input($this->request->getPost('jumlah_anggota_komunitas'));
            $data['jabatan_komunitas'] = sanitize_input($this->request->getPost('jabatan_komunitas'));
            $data['akun_komunitas'] = sanitize_input($this->request->getPost('akun_komunitas'));

            if ($file = $this->request->getFile('file_pengajuan_kegiatan')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $filename = 'Pengajuan-kegiatan-' . strtolower(url_title($data['nama_komunitas']));
                    $data['file_pengajuan_kegiatan'] = $this->upload_file_pdf('file_pengajuan_kegiatan', $filename);
                }
            }

            if ($file = $this->request->getFile('file_analisa_skorlife')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $filename = 'Analisa-skorlife-' . strtolower(url_title($data['nama_komunitas']));
                    $data['file_analisa_skorlife'] = $this->upload_file_image('file_analisa_skorlife', $filename);
                }
            }

            if ($file = $this->request->getFile('file_profile_komunitas')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $filename = 'Profile-komunitas-' . strtolower(url_title($data['nama_komunitas']));
                    $data['file_profile_komunitas'] = $this->upload_file_pdf('file_profile_komunitas', $filename);
                }
            }
        }

        $update = $this->mainModel->update_data('tb_user', $data, 'id_user', $id_user);

        if ($update) {
            $response = [
                'status' => 1,
                'message' => 'Perubahan berhasil disimpan.',
                'kategori' => $kategori_user
            ];
        } else {
            $response = [
                'status' => 0,
                'message' => 'Gagal menyimpan perubahan.',
                'kategori' => $kategori_user
            ];
        }

        return json_response($response);
    }

    function post_update_pilot_project()
    {
        $id_user = (int) key_auth();

        $token_user = $this->request->getPost('token_user', true);

        $query = $this->db->table('tb_user')
            ->select('id_user')
            ->where('id_user', $id_user)
            ->where('token', $token_user)
            ->get()
            ->getRowArray();

        if (!$query) {
            return redirect()->to('logout');
        }

        $data = [
            'pp_background_masalah' => $this->request->getPost('pp_background_masalah', true),
            'pp_nama' => $this->request->getPost('pp_nama', true),
            'pp_deskripsi' => $this->request->getPost('pp_deskripsi', true),
            'pp_target' => $this->request->getPost('pp_target', true),
            'pp_timeline' => $this->request->getPost('pp_timeline', true),
            'pp_potential_partner' => $this->request->getPost('pp_potential_partner', true),
            'pp_kebutuhan_ahli' => $this->request->getPost('pp_kebutuhan_ahli', true),
            'pp_pembeda' => $this->request->getPost('pp_potential_partner', true),
        ];

        $update = $this->db->table('tb_user')
            ->where('id_user', $id_user)
            ->update($data);

        if ($update) {
            $response = [
                'status' => 1,
                'message' => 'Perubahan berhasil disimpan.'
            ];
        } else {
            $response = [
                'status' => 0,
                'message' => 'Gagal menyimpan perubahan.'
            ];
        }

        return json_response($response);
    }

    private function upload_file_pdf($name, $filename)
    {
        $validationRule = [
            $name => [
                'label' => 'Pitchdeck File',
                'rules' => '
                uploaded[' . $name . ']
                |max_size[' . $name . ',10240]
                |ext_in[' . $name . ',pdf,ppt,pptx]
                |mime_in[' . $name . ',
                    application/pdf,
                    application/vnd.ms-powerpoint,
                    application/vnd.openxmlformats-officedocument.presentationml.presentation
                ]
            '
            ]
        ];

        if (!$this->validate($validationRule)) {
            return '';
        }

        $file = $this->request->getFile($name);

        if ($file->isValid() && !$file->hasMoved()) {

            $kode_user = strtoupper(substr(uniqid(), 7)) . date('my');
            $newName = $filename . '-' . $kode_user . '.' . $file->guessExtension();

            $file->move(FCPATH . 'file_media/file-user/', $newName);

            return $newName;
        }

        return '';
    }

    private function upload_file_image($name, $filename)
    {
        $validationRule = [
            $name => [
                'label' => 'Image File',
                'rules' => '
                uploaded[' . $name . ']
                |max_size[' . $name . ',5120]
                |is_image[' . $name . ']
                |ext_in[' . $name . ',jpg,jpeg,png,webp]
            '
            ]
        ];

        if (!$this->validate($validationRule)) {
            return '';
        }

        $file = $this->request->getFile($name);

        if ($file->isValid() && !$file->hasMoved()) {

            $kode_user = strtoupper(substr(uniqid(), 7)) . date('my');
            $newName = $filename . '-' . $kode_user . '.' . $file->guessExtension();

            $file->move(FCPATH . 'file_media/file-user/', $newName);

            return $newName;
        }

        return '';
    }
    // --------------------------- end profile ---------------------------


    // --------------------------- change password --------------------------- 
    function post_change_password()
    {
        $id_user = key_auth();
        if (!$id_user) {
            return redirect()->to('/');
        }

        $password_konfirmasi = $this->request->getPost('password_konfirmasi');

        $validation = Services::validation();

        $validation->setRules([
            'password_konfirmasi' => [
                'label' => 'Password',
                'rules' => 'required|password_conf'
            ]
        ]);

        if (!$validation->run(['password_konfirmasi' => $password_konfirmasi])) {
            return json_response([
                'status' => 0,
                'password_konfirmasi' => $password_konfirmasi,
                'message' => $validation->getErrors()
            ]);
        }

        $data = [
            'password' => md5($password_konfirmasi),
            'password_text' => $password_konfirmasi
        ];

        $update = $this->db->table('tb_user')
            ->where('id_user', $id_user)
            ->update($data);

        if ($update) {
            $response = [
                'status' => 1,
                'message' => 'Password berhasil diperbarui. Silahkan login kembali menggunakan password baru Anda'
            ];
        } else {
            $response = [
                'status' => 0,
                'message' => 'Gagal mengganti password.'
            ];
        }

        return json_response($response);
    }
    // --------------------------- end change password --------------------------- 

    // --------------------------- get modul ---------------------------
    function _sql_data_modul($limit, $start)
    {
        $search = trim($this->request->getPost('search') ?? '');

        $id_user = key_auth();

        $get_user = $this->db->table('tb_user')
            ->select('kategori_user as kategori')
            ->where('id_user', $id_user)
            ->get()
            ->getRowArray();

        $builder = $this->db->table('edu_modul');
        $builder->select('id_modul, modul, cover, deskripsi_modul, date_create');
        $builder->where('kategori', $get_user['kategori']);
        $builder->where('status_delete', '0');

        if ($search !== '') {
            $builder->like('modul', $search);
        }

        $builder->orderBy('id_modul', 'ASC');
        $builder->limit($limit, $start);

        return $builder->get();
    }

    function fetch_data_modul()
    {
        $id_user = key_auth();

        $limit = (int) $this->request->getPost('limit');
        $start = (int) $this->request->getPost('start');

        $data = [];

        $query = $this->_sql_data_modul($limit, $start)->getResultArray();

        foreach ($query as $key) {

            // Check sertifikat
            $cek_sertif = $this->db->table('quiz_skor_user')
                ->select('skor')
                ->where('id_modul', $key['id_modul'])
                ->where('jenis_quiz', 'POST - TEST')
                ->where('id_user', $id_user)
                ->get()
                ->getResultArray();

            // count video
            $get_video = $this->db->table('edu_video')
                ->selectCount('id_video', 'total')
                ->where('id_modul', $key['id_modul'])
                ->get()
                ->getRowArray();

            $row['id_modul'] = $key['id_modul'];
            $row['url_detail'] = url_modul_detail($key['modul'], $key['id_modul']);
            $row['url_edukasi'] = url_modul_edukasi($key['modul'], $key['id_modul']);
            $row['modul'] = $key['modul'];
            $row['deskripsi_modul'] = strip_tags($key['deskripsi_modul']);
            $row['date_create'] = $key['date_create'];
            $row['cover'] = url_image($key['cover'], 'file-modul');
            $row['total_video'] = $get_video['total'];

            if ($cek_sertif) {
                $row['url_sertifikat'] = base_url("dashboard/show_sertifikat?user=" . encrypt_url($id_user) . '&modul=' . encrypt_url($key['id_modul']));
            } else {
                $row['url_sertifikat'] = "";
            }

            $data[] = $row;
        }

        // load more handler
        $start_next = $start + $limit;
        $query_next = $this->_sql_data_modul($limit, $start_next);

        $load_more = ($query_next->getNumRows() >= (int) $limit) ? 1 : 0;

        return json_response([
            'data' => $data,
            'load_more' => $load_more,
            'status' => 1,
            'message' => 'Success'
        ]);
    }
    // --------------------------- get modul ---------------------------

    function get_data_user()
    {
        $id_user = key_auth();
        $query = $this->db->query("SELECT nama FROM tb_user WHERE id_user = '" . $id_user . "'")->row();

        return $query;
    }

    public function show_sertifikat()
    {
        $user_encryp = $this->request->getGet('user');
        $modul_encryp = $this->request->getGet('modul');

        $id_user = decrypt_url($user_encryp);
        $id_modul = decrypt_url($modul_encryp);

        if (!empty($user_encryp) && !empty($modul_encryp)) {
            $cek_2 = $this->db->table('edu_modul_user_progress')
                ->where('id_user', $id_user)
                ->where('id_modul', $id_modul)
                ->get()
                ->getRow();

            if ($cek_2) {
                $modul = $this->db->table('edu_modul')
                    ->where('id_modul', $id_modul)
                    ->get()
                    ->getRowArray();

                $get_id_user = key_auth();
                $data_user = $this->userModel->getUserName($get_id_user);
                $nama = strtoupper($data_user->nama);
                $nama_modul = word_wrap(strtoupper($modul['modul']), 65);

                $text_modul = 'Telah menyelesaikan modul "' . $modul['modul'] . '"';
                $text_modul_2 = 'dari SheHacks 2025';
                $date = date('d F Y', strtotime($cek_2->date_sertifikat));

                $get_web = $this->db->table('tb_admin_web')
                    ->select('image_sertifikat')
                    ->where('id', 1)
                    ->get()
                    ->getRowArray();

                $base64 = $get_web['image_sertifikat'];
                $response_verify = base64_decode($base64);

                $image = imageCreateFromString($response_verify);
                $color_black = imageColorAllocate($image, 0, 0, 0);

                $font_bold = "assets/front/font/sertifikat/IndosatBold-Bold.ttf";
                $font_medium = "assets/front/font/sertifikat/IndosatMedium-Medium.ttf";
                $font_tanggal = "assets/front/font/sertifikat/Poppins-Regular.ttf";

                $font_size_lg = 48;
                $font_size_md = 25;
                $font_size_sm = 10.5;

                $image_width = imagesx($image);

                // NAME
                $text_box = imagettfbbox($font_size_lg, 0, $font_bold, $nama);
                $text_width = $text_box[2] - $text_box[0];
                $x = ($image_width / 2) - ($text_width / 2);

                // MODULE TITLE
                $text_box2 = imagettfbbox($font_size_sm, 0, $font_bold, $nama_modul);
                $text_width2 = $text_box2[2] - $text_box2[0];
                $x2 = ($image_width / 2) - ($text_width2 / 2);

                // DATE
                $text_box3 = imagettfbbox($font_size_sm, 0, $font_tanggal, $date);
                $text_width3 = $text_box3[2] - $text_box3[0];
                $x3 = ($image_width / 2) - ($text_width3 / 2);

                // DRAW TEXT
                imagettftext($image, $font_size_lg, 0, 715, 720, $color_black, $font_bold, $nama);
                imagettftext($image, $font_size_md, 0, 715, 805, $color_black, $font_medium, $text_modul);
                imagettftext($image, $font_size_md, 0, 715, 855, $color_black, $font_medium, $text_modul_2);

                header("Content-type: image/jpeg");
                imagejpeg($image);
                imagedestroy($image);

            } else {
                return redirect()->to('/');
            }

        } else {
            return redirect()->to('/');
        }
    }

}
