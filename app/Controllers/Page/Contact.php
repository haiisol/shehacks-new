<?php

namespace App\Controllers\Page;

use App\Controllers\FrontController;

class Contact extends FrontController
{
    public function index()
    {
        $data = [
            'title'       => 'About',
            'page'        => 'page/contact'
        ];

        $this->data = array_merge($this->data, $data);

        return view('index', $this->data);
    }
}
