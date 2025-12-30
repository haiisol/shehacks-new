<?php

namespace App\Controllers\Page;

use App\Controllers\FrontController;
use App\Models\ContentModel;

class TermsOfService extends FrontController
{

    public function index()
    {
        $data = [
            'title' => 'page/program',
            'page' => 'page/terms_of_service'
        ];

        $this->data = array_merge($this->data, $data);

        return view('index', $this->data);
    }

    

    public function fetch_data()
    {
        try {
            $model = new ContentModel();
            $result = $model->getBySection('terms_condition');

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