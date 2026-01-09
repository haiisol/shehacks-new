<?php

namespace App\Controllers;

use App\Models\MainModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class AdminController extends BaseController
{
    protected $mainModel;
    protected $data = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->mainModel = new MainModel();

        $this->data['admin'] = $this->mainModel->get_admin_user();

        if (!$this->request->isAJAX()) {
            $this->prepareGlobalData();
        }

        $this->preparePrivileges();
        $this->prepareMenuAccess();
    }

    private function prepareGlobalData()
    {
        if (! $web = cache()->get('web_settings')) {
            $web = $this->mainModel->get_admin_web();
            cache()->save('web_settings', $web, 3600);
        }

        $admin = $this->data['admin'];

        $this->data['web'] = $web;
        $this->data['site_name'] = $web['name'];

        $this->data['favicon_img']    = url_image($web['favicon'], 'image-logo');
        $this->data['logo']           = url_image($web['logo'], 'image-logo');
        $this->data['logo_sponsor']   = url_image($web['logo_sponsor'], 'image-logo');
        $this->data['favicon_og']     = $this->data['favicon_banner'] ?? $this->data['favicon_img'];
        $this->data['title']          = $web['name'] . ' - ' . $web['meta_description'];
        $this->data['description']    = $web['meta_description'];
        $this->data['keywords']       = $web['meta_keywords'];
        $this->data['event_running']  = filter_var($web['event_running'], FILTER_VALIDATE_BOOLEAN);
        $this->data['register_button'] = filter_var($web['register_button'], FILTER_VALIDATE_BOOLEAN);
        $this->data['voting_running'] = filter_var($web['voting_running'], FILTER_VALIDATE_BOOLEAN);
        $this->data['under_construction'] = filter_var($web['under_construction'], FILTER_VALIDATE_BOOLEAN);
        $this->data['instagram'] = $web['instagram'] ?? '';
        $this->data['facebook'] = $web['facebook'] ?? '';
        $this->data['youtube'] = $web['youtube'] ?? '';
        $this->data['twitter'] = $web['twitter'] ?? '';

        $this->data['nama_admin'] = $admin['nama_admin'];
        $this->data['admin_img'] = url_image($admin['photo'], 'image-admin');
        $this->data['role'] = $admin['role'];
    }

    private function prepareMenuAccess()
    {
        $menus = [
            'data_user',
            'report',
            'blog',
            'modul',
            'webinar',
            'contact',
            'voting',
            'startup',
            'data_master',
            'content_page',
            'content_home',
            'content_program',
            'website',
            'akun_email',
            'user_role',
            'operator',
        ];

        foreach ($menus as $menu) {
            $this->data['menu'][$menu] =
                (! empty($this->data['privileges'][$menu]['view']))
                ? ''
                : 'd-none';
        }
    }

    private function preparePrivileges()
    {
        $admin = $this->data['admin'];
        $cacheKey = 'admin_privileges_' . $admin['id_role'];

        if (! $privileges = cache()->get($cacheKey)) {
            $rows = $this->mainModel->getRolePrivileges($admin['id_role']);
            $privileges = $this->mainModel->mapPrivileges($rows);

            cache()->save($cacheKey, $privileges, 3600);
        }

        $this->data['privileges'] = $privileges;
    }

    protected function requireAccess(string $menu)
    {
        if (
            empty($this->data['privileges'][$menu]) ||
            $this->data['privileges'][$menu]['view'] !== true
        ) {
            response()->redirect(base_url('404'))->send();
            exit;
        }

        $this->data['access_add'] =
            $this->data['privileges'][$menu]['create'] ? '' : 'd-none';

        $this->data['access_edit'] =
            $this->data['privileges'][$menu]['edit'] ? '' : 'd-none';

        $this->data['access_delete'] =
            $this->data['privileges'][$menu]['delete'] ? '' : 'd-none';
    }
}
