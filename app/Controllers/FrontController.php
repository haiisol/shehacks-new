<?php

namespace App\Controllers;

use App\Models\MainModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class FrontController extends BaseController
{
    protected $mainModel;
    protected $userModel;
    protected $data = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->mainModel = new MainModel();
        $this->userModel = new UserModel();

        $this->data['logged_in_front'] = session()->get('logged_in_front') ?? false;
        $this->data['user_name'] = '';

        if ($this->data['logged_in_front']) {
            $id_user = key_auth();
            $user = $this->userModel->getUserName($id_user);
            $this->data['user_name'] = $user->nama ?? '';
        }

        if (!$this->request->isAJAX()) {
            $this->prepareGlobalData();
        }
    }

    private function prepareGlobalData()
    {
        if (! $web = cache()->get('web_settings')) {
            $web = $this->mainModel->get_admin_web();
            cache()->save('web_settings', $web, 3600);
        }

        if (! $banner = cache()->get('banner')) {
            $banner = $this->mainModel->url_banner_popup();
            cache()->save('banner', $banner, 3600);
        }

        $this->data['web'] = $web;
        $this->data['site_name'] = $web['name'];

        $this->data['favicon_img']    = url_image($web['favicon'], 'image-logo');
        $this->data['favicon_banner'] = $banner;
        $this->data['logo']           = url_image($web['logo'], 'image-logo');
        $this->data['logo_sponsor']   = url_image($web['logo_sponsor'], 'image-logo');
        $this->data['favicon_og']     = $this->data['favicon_banner'] ? $this->data['favicon_banner'] : $this->data['favicon_img'];
        $this->data['title']          = $web['name'] . ' - ' . $web['meta_description'];
        $this->data['description']    = $web['meta_description'];
        $this->data['keywords']       = $web['meta_keywords'];
        $this->data['event_running']  = filter_var($web['event_running'], FILTER_VALIDATE_BOOLEAN);
        $this->data['register_button'] = filter_var($web['register_button'], FILTER_VALIDATE_BOOLEAN);
        $this->data['voting_running'] = filter_var($web['voting_running'], FILTER_VALIDATE_BOOLEAN);
        $this->data['under_construction'] = filter_var($web['under_construction'], FILTER_VALIDATE_BOOLEAN);
        $this->data['instagram'] = $web['instagram'] || '';
        $this->data['facebook'] = $web['facebook'] || '';
        $this->data['youtube'] = $web['youtube'] || '';
        $this->data['twitter'] = $web['twitter'] || '';
    }
}
