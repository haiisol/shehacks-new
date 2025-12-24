<?php

namespace App\Controllers\Modul;

use App\Controllers\FrontController;
use App\Models\MainModel;
use Config\Database;

class Modul extends FrontController
{
    protected $db;
    protected $mainModel;
    public function __construct()
    {
        $this->db = Database::connect();
        $this->mainModel = new MainModel();
        unset_log_redirect();
    }

    public function detail_modul($slug, $id_modul_enc)
    {
        $id_modul = decrypt_url($id_modul_enc);

        $get_modul = $this->db->table('edu_modul')
            ->select('id_modul, modul')
            ->where('id_modul', $id_modul)
            ->get()
            ->getRowArray();

        if (!$get_modul) {
            return redirect()->to('/');
        }

        $data = [
            'id_modul_enc' => $id_modul_enc,
            'title' => $get_modul['modul'],
            'page' => 'modul/modul_detail'
        ];

        $this->data = array_merge($this->data, $data);

        return view('index', $this->data);
    }


    function fetch_data_modul_detail()
    {
        $id_modul = decrypt_url($this->request->getPost('id_modul_enc'));

        $get_modul = $this->db->table('edu_modul m')
            ->select('m.*, u.photo, u.nama_admin, m.kategori')
            ->join('tb_admin_user u', 'u.id_admin = m.user_post', 'left')
            ->where('m.id_modul', $id_modul)
            ->get()
            ->getRowArray();

        if (!$get_modul) {
            return json_response(['status' => 0]);
        }

        $get_quiz = $this->db->table('quiz')
            ->selectCount('id_quiz', 'total')
            ->where([
                'id_modul' => $id_modul,
                'status_delete' => 0
            ])
            ->get()
            ->getRowArray();

        $total_video = $this->db->table('edu_video')
            ->selectCount('id_video', 'total')
            ->where([
                'id_modul' => $id_modul,
                'status_delete' => 0
            ])
            ->get()
            ->getRowArray();

        $get_video = $this->db->table('edu_video')
            ->select('id_video, judul, durasi')
            ->where([
                'id_modul' => $id_modul,
                'status_delete' => 0
            ])
            ->get()
            ->getResultArray();

        $data_video = [];
        foreach ($get_video as $v) {
            $data_video[] = [
                'id_video' => $v['id_video'],
                'judul_video' => $v['judul'],
                'durasi_video' => $v['durasi']
            ];
        }

        return json_response([
            'id_modul' => $get_modul['id_modul'],
            'url_detail' => url_modul_detail($get_modul['modul'], $get_modul['id_modul']),
            'url_edukasi' => url_modul_edukasi($get_modul['modul'], $get_modul['id_modul']),
            'modul' => $get_modul['modul'],
            'deskripsi_modul' => $get_modul['deskripsi_modul'],
            'date_create' => $get_modul['date_create'],
            'cover' => url_image($get_modul['cover'], 'file-modul'),
            'total_video' => $total_video['total'],
            'nama_admin' => $get_modul['nama_admin'],
            'photo_admin' => url_image_admin($get_modul['photo']),
            'package_name' => $get_modul['kategori'],
            'pretest' => $get_quiz['total'],
            'posttest' => $get_quiz['total'],
            'sertifikat' => 1,
            'data_video' => $data_video
        ]);
    }


    function get_data_modul($id_modul)
    {
        return $this->db->table('edu_modul')
            ->where('id_modul', $id_modul)
            ->get()
            ->getRow();
    }


    function fetch_data_modul($slug, $id_modul)
    {
        $dc_id_modul = base64_decode($id_modul);

        // update views
        $this->db->table('edu_modul')
            ->set('views', 'views+1', false)
            ->where('id_modul', $dc_id_modul)
            ->update();

        $data['id_user'] = key_auth();

        $data['modul'] = $this->db->table('edu_modul m')
            ->select('m.*, k.kategori')
            ->join('edu_kategori k', 'm.id_kategori = k.id_kategori', 'left')
            ->where('m.id_modul', $dc_id_modul)
            ->get()
            ->getRowArray();

        $data['ulasan'] = $this->db->table('ratting_modul r')
            ->select('r.*, u.nama_depan')
            ->join('user_apps u', 'r.id_user = u.id_user', 'left')
            ->where([
                'r.id_modul' => $dc_id_modul,
                'r.tampilkan_ulasan' => 1
            ])
            ->get()
            ->getResultArray();

        $data['modul_bestseller'] = $this->db->table('request_order r')
            ->select('m.*, a.nama_admin')
            ->join('edu_modul m', 'r.id_modul = m.id_modul', 'left')
            ->join('admin_user a', 'a.id_admin = m.user_post', 'left')
            ->whereIn('r.id_privilage', [2, 3])
            ->where('m.status_delete', 0)
            ->where('r.status', 'Approved')
            ->where('m.id_modul IS NOT NULL', null, false)
            ->groupBy('r.id_modul')
            ->orderBy('COUNT(r.id_modul)', 'DESC', false)
            ->get()
            ->getResultArray();

        $data['data_flashsale'] = $this->mainModel->get_data_flashsale_modul($dc_id_modul);

        $get_modul = $this->get_data_modul($dc_id_modul);

        $data['title'] = $get_modul->modul;
        $data['page'] = 'front/modul/modul_detail';

        $this->data = array_merge($this->data, $data);

        return view('index', $this->data);
    }

}