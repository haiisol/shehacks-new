<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;
use Config\Database;

class FrontAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = Services::session();

        if ($session->get('logged_in_front') !== true) {
            return redirect()->to('/');
        }

        $id_user = key_auth();
        $key_token = $session->get('key_token');

        $db = Database::connect();

        $cek = $db->table('tb_user_2fa')
            ->select('logout_status')
            ->where([
                'access_policy' => 'FE',
                'id_user' => $id_user,
                'code_encrypt' => $key_token
            ])
            ->get()
            ->getRowArray();

        if (!$cek || $cek['logout_status'] === 'true') {
            return redirect()->to('/');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
