<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Common extends BaseConfig
{
    public $base_path    = 'https://shehacks.ioh.co.id/';
    public $url_api_file;
    public $url_api_IDE  = 'https://wawasan.ide.ioh.co.id';
    public $url_api_IDE_staging = 'https://wawasan.gammasprint.com';
    public $api_key_IDE  = 'b9a8b7c9-e20f-4062-a18c-bfb125554111';
    
    public $midtrans_production = false;
    public $date_now;

    public function __construct()
    {
        parent::__construct();

        // Handle dynamic concatenations and logic
        $this->url_api_file = $this->base_path . 'file_media/';
        
        // Handle dates
        $this->date_now = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');
    }
}