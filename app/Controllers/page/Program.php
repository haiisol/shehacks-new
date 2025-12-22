<?php

namespace App\Controllers\Page;

use App\Controllers\FrontController;

class Program extends FrontController
{

    public function index()
    {
        $data = [
            'page' => 'page/program'
        ];

        $this->data = array_merge($this->data, $data);

        return view('index', $this->data);
    }


}