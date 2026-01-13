<?php

namespace App\Controllers\Page;

use App\Controllers\FrontController;

class ImpactReport extends FrontController
{

    public function index()
    {
        $data = [
            'title' => 'Impact Report',
            'page' => 'page/impact_report'
        ];

        $this->data = array_merge($this->data, $data);

        return view('index', $this->data);
    }

}