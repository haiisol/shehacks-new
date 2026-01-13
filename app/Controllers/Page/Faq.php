<?php

namespace App\Controllers\Page;

use App\Controllers\FrontController;

class Faq extends FrontController
{
    public function index()
    {
        $data = [
            'title'       => 'Frequently Asked Question',
            'page'        => 'page/faq'
        ];

        $this->data = array_merge($this->data, $data);

        return view('index', $this->data);
    }
}
