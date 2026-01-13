<?php

namespace App\Controllers\Admin\Setting;

use App\Controllers\AdminController;
use Config\Services;

class Website extends AdminController
{
    protected $db;

    function __construct()
    {
        $this->db = db_connect();
    }

    public function index()
    {
        $this->requireAccess('website');
        $cact = $this->mainModel->check_access_action('website');

        $data = [
            'title' => 'Setting Website',
            'page' => 'admin/setting/website',
            'access_edit' => $cact['access_edit'],
        ];

        $this->data = array_merge($this->data, $data);

        return view('admin/index', $this->data);
    }

    function get_data()
    {
        $this->requireAccess('website');

        $query = $this->db->table('tb_admin_web')
            ->where('id', 1)
            ->get()
            ->getRowArray();

        $response = [
            'query' => $query,
            'link_logo' => url_image($query['logo'], 'image-logo'),
            'link_logo_sponsor' => url_image($query['logo_sponsor'], 'image-logo'),
            'link_favicon' => url_image($query['favicon'], 'image-logo'),
        ];

        return json_response($response);
    }

    function edit_data()
    {
        $this->requireAccess('website');
        $cact = $this->mainModel->check_access_action('website');

        if ($cact['access_edit'] === 'd-none') {
            return json_response([
                'status' => 0,
                'message' => 'Gagal, tidak memiliki akses edit.'
            ]);
        }

        $id = $this->request->getPost('id');

        if (!is_numeric($id)) {
            return json_response([
                'status' => 0,
                'message' => 'ID tidak valid.'
            ]);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'contact_name' => $this->request->getPost('contact_name'),
            'contact_person' => $this->request->getPost('contact_person'),
            'address' => $this->request->getPost('address'),
            'maps_address' => $this->request->getPost('maps_address'),
            'whatsapp' => $this->request->getPost('whatsapp'),
            'facebook' => $this->request->getPost('facebook'),
            'twitter' => $this->request->getPost('twitter'),
            'instagram' => $this->request->getPost('instagram'),
            'youtube' => $this->request->getPost('youtube'),
            'short_description' => $this->request->getPost('short_description'),
            'meta_description' => $this->request->getPost('meta_description'),
            'meta_keywords' => $this->request->getPost('meta_keywords'),
            'register_button' => $this->request->getPost('register_button'),
            'voting_running' => $this->request->getPost('voting_running'),
        ];

        if ($this->request->getFile('logo')->isValid()) {
            $data['logo'] = $this->upload_image('logo', $id);
        }

        if ($this->request->getFile('logo_sponsor')->isValid()) {
            $data['logo_sponsor'] = $this->upload_image('logo_sponsor', $id);
        }

        if ($this->request->getFile('favicon')->isValid()) {
            $data['favicon'] = $this->upload_image('favicon', $id);
        }

        $update = $this->mainModel->update_data(
            'tb_admin_web',
            $data,
            'id',
            $id
        );

        return json_response([
            'status' => $update ? 1 : 0,
            'message' => $update
                ? 'Data berhasil disimpan.'
                : 'Gagal menyimpan data.'
        ]);
    }

    protected function upload_image(string $field, int $id): string
    {
        $uploadPath = FCPATH . 'file_media/image-logo/';
        $file = $this->request->getFile($field);

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

        $fileName = $field . '_' . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
        $finalName = $fileName . '.' . $file->guessExtension();

        $old = $this->db->table('tb_admin_web')
            ->select($field)
            ->where('id', $id)
            ->get()
            ->getRowArray();

        if (!empty($old[$field])) {
            $oldPath = $uploadPath . $old[$field];
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $file->move($uploadPath, $finalName);

        // IMAGE QUALITY (60%)
        Services::image()
            ->withFile($uploadPath . $finalName)
            ->save($uploadPath . $finalName, 60);

        return $finalName;
    }
}
