<?php

namespace App\Controllers\Startups;

use App\Controllers\FrontController;
use App\Models\StartupModel;
use App\Models\MasterSectorModel;

class Startups extends FrontController
{
    public function index()
    {
        $sectorModel = new MasterSectorModel();
        
        $data = [
            'sector'      => $sectorModel->getActiveSectors(),
            'title'       => 'Startup Alumni SheHacks',
            'description' => '',
            'keywords'    => '',
            'page'        => 'startups/startups'
        ];

        $this->data = array_merge($this->data, $data);

        return view('index', $this->data);
    }

    public function detail($slug, $enc_id)
    {
        $startupModel = new StartupModel();
        $enc_id_kode = preg_replace('/[^A-Za-z0-9]/', '', $enc_id);
        
        $id = decrypt_url($enc_id);

        $get_data = $startupModel->getStartupById($id);

        if ($get_data) {
            $data = [
                'id_enc'      => $enc_id_kode,
                'title'       => $get_data['startup_name'],
                'description' => $get_data['sort_description'],
                'keywords'    => '',
                'page'        => 'startups/startups_detail'
            ];

            $this->data = array_merge($this->data, $data);

            return view('index', $this->data);
        }

        return redirect()->to('/');
    }
}