<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MainModel;

class AccessFilter implements FilterInterface
{
    public function before(RequestInterface $request, $args = null)
    {
        $menu = $args[0] ?? null;

        $mainModel = new MainModel();
        $user  = $mainModel->get_admin();

        $access = $mainModel->get_query_menu($menu, $user['id_role']);

        if (!$access || $access['view_data'] != 1) {
            return redirect()->to(base_url('404'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
