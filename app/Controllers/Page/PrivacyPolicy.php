<?php

namespace App\Controllers\Page;

use App\Controllers\FrontController;
use App\Models\ContentModel;

class PrivacyPolicy extends FrontController
{
    protected $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function index()
    {
        $data = [
            'title' => 'Privacy & Policy',
            'page' => 'page/privacy_policy'
        ];

        $this->data = array_merge($this->data, $data);

        return view('index', $this->data);
    }

    public function fetch_data()
    {
        try {
            $model = new ContentModel();
            $result = $model->getBySection('privacy_policy');

            return json_response([
                'data' => $result ?? [],
                'status' => 1,
                'message' => 'Success'
            ]);

        } catch (\Throwable $e) {
            return json_response([
                'data' => [],
                'status' => 0,
                'message' => 'Database Error',
                'error' => $e->getMessage()
            ]);
        }
    }

}
