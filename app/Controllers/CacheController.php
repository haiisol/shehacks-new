<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class CacheController extends Controller
{
    public function clear()
    {
        if ($this->request->getGet('token') !== env('CACHE_CLEAR_TOKEN')) {
            return $this->response->setStatusCode(403)->setBody('Forbidden');
        }

        $cache = service('cache');
        $test = $cache->clean();
        
        return 'Cache cleared successfully';
    }
}
