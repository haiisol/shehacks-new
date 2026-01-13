<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use Config\Services;

class EditProfile extends AdminController
{
    protected $db;
    function __construct()
    {
        $this->db = db_connect();
    }

    public function index()
    {
        $id_admin = decrypt_url(session()->get('key_auth_admin'));

        $row_user = $this->db->table('tb_admin_user')
            ->where('id_admin', $id_admin)
            ->get()
            ->getRowArray();

        $data = [
            'data' => $row_user,
            'title' => 'Edit profile',
            'page' => 'admin/profile',
        ];

        $this->data = array_merge($this->data, $data);

        return view('admin/index', $this->data);
    }

    function get_data()
    {

        $id = decrypt_url(session()->get('key_auth_admin'));

        $query = $this->db->table('tb_admin_user')
            ->where('id_admin', $id)
            ->get()
            ->getRowArray();

        return json_response([
            'data' => $query,
            'link_photo' => url_image_admin($query['photo'] ?? ''),
        ]);
    }

    function edit_data()
    {
        $id = $this->request->getPost('id_admin');

        $data = [
            'nama_admin' => $this->request->getPost('nama'),
            'phone_admin' => $this->request->getPost('phone'),
        ];

        $file = $this->request->getFile('photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $photo = $this->upload_photo();
            if ($photo !== '') {
                $data['photo'] = $photo;
            }
        }

        $status_logout = 0;
        if ($this->request->getPost('password')) {
            $status_logout = 1;
            $data['password_admin'] = md5($this->request->getPost('password'));
        }

        $query = $this->mainModel->update_data(
            'tb_admin_user',
            $data,
            'id_admin',
            $id
        );

        if ($query) {
            $response['status'] = ($status_logout === 1) ? 2 : 1;
        } else {
            $response['status'] = 0;
        }

        return json_response($response);
    }

    protected function upload_photo()
    {
        $uploadPath = FCPATH . 'file_media/image_admin/';

        $file = $this->request->getFile('photo');

        if (!$file || !$file->isValid()) {
            return '';
        }

        $allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/jpg',
            'image/webp',
        ];

        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return '';
        }

        $nama = strtolower($this->request->getPost('nama'));
        $fileName = url_title($nama) . '_' . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
        $ext = $file->guessExtension();
        $finalName = $fileName . '.' . $ext;

        // Get old photo
        $id = $this->request->getPost('id_admin');

        $query = $this->db->table('tb_admin_user')
            ->select('photo')
            ->where('id_admin', $id)
            ->get()
            ->getRowArray();

        // Delete old photo
        if (!empty($query['photo'])) {
            $oldFile = $uploadPath . $query['photo'];
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        // Move uploaded file
        if ($file->hasMoved()) {
            return '';
        }

        $file->move($uploadPath, $finalName);

        Services::image()
            ->withFile($uploadPath . $finalName)
            ->save($uploadPath . $finalName, 60);

        return $finalName;
    }
}