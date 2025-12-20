<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;

class Analytic extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function post_visitors()
    {
        // 1. Get Input
        $page = $this->request->getPost('page');
        $url  = $this->request->getPost('url');

        // 2. Validation
        $rules = [
            'page' => 'required',
            'url'  => 'required|valid_url'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 0,
                'message' => $this->validator->getErrors(),
                'csrf_hash' => csrf_hash()
            ]);
        }

        // 3. Exception Check
        if (in_array($page, ['artikel/artikel_detail', 'modul/master_modul'])) {
            return $this->response->setJSON(['status' => 0]);
        }

        // 4. Gather Agent Data
        $agent = $this->request->getUserAgent();
        $referrer = $agent->isReferral() ? $agent->getReferrer() : base_url();
        $ipaddress = $this->request->getIPAddress();
        $date = date('Y-m-d');

        // 5. Check Existing Visitor for Today
        $builder = $this->db->table('tb_analytic_visitors_2025');
        $visitor = $builder->where('page_view', $url)
            ->where('ip_address', $ipaddress)
            ->where('DATE(waktu)', $date)
            ->where('referral', $referrer)
            ->get()
            ->getRowArray();

        if ($visitor) {
            // Update Hits
            $builder->where('id', $visitor['id'])
                ->increment('hits', 1);
        } else {
            // Insert New Visit
            $data = [
                'ip_address' => $ipaddress,
                'page_view'  => $url,
                'date'       => $date,
                'hits'       => 1,
                'browser'    => $agent->getBrowser(),
                'platform'   => $agent->getPlatform(),
                'waktu'      => date('Y-m-d H:i:s'),
                'referral'   => $referrer
            ];
            $builder->insert($data);
        }

        return $this->response->setJSON([
            'status'    => 1,
            'message'   => 'Success.',
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function post_cta_btn()
    {
        $data_cta = $this->request->getPost('data');
        $url      = $this->request->getPost('url');

        $rules = [
            'data' => 'required',
            'url'  => 'required|valid_url'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 0,
                'message' => $this->validator->getErrors(),
                'csrf_hash' => csrf_hash()
            ]);
        }

        $date      = date('Y-m-d');
        $ipaddress = $this->request->getIPAddress();

        $builder = $this->db->table('tb_analytic_cta');
        $visitor = $builder->where('DATE(date_create)', $date)
            ->where('page_view', $url)
            ->where('ip_address', $ipaddress)
            ->where('cta', $data_cta)
            ->get()
            ->getRowArray();

        if ($visitor) {
            $builder->where('id_analytic_cta', $visitor['id_analytic_cta'])
                ->increment('hits', 1);
        } else {
            $visit = [
                'ip_address'  => $ipaddress,
                'page_view'   => $url,
                'cta'         => $data_cta,
                'hits'        => 1,
                'date_create' => date('Y-m-d H:i:s')
            ];
            $builder->insert($visit);
        }

        return $this->response->setJSON([
            'status'    => 1,
            'message'   => 'Success',
            'csrf_hash' => csrf_hash()
        ]);
    }

    public function post_blog_viewer()
    {
        // 1. Decrypt and Validate
        $id_blog = decrypt_url($this->request->getPost('id_enc'));

        if (empty($id_blog)) {
            return $this->response->setJSON(['status' => 0, 'message' => 'Invalid ID', 'csrf_hash' => csrf_hash()]);
        }

        // 2. Prepare Data
        $agent = $this->request->getUserAgent();
        $referrer = $agent->isReferral() ? $agent->getReferrer() : base_url();
        $date = date('Y-m-d');

        // 3. Query Builder (Replaces Raw SQL)
        $builder = $this->db->table('tb_analytic_blog');
        $viewer  = $builder->where('id_blog', $id_blog)
            ->where('DATE(date_create)', $date)
            ->where('referral', $referrer)
            ->get()
            ->getRowArray();

        if ($viewer) {
            // CI4 optimized way to increment a column
            $builder->where('id_analytic_blog', $viewer['id_analytic_blog'])
                ->increment('hits', 1);
        } else {
            $builder->insert([
                'id_blog'     => $id_blog,
                'referral'    => $referrer,
                'hits'        => 1,
                'date_create' => date('Y-m-d H:i:s')
            ]);
        }

        return $this->response->setJSON(['status' => 1, 'message' => 'Success', 'csrf_hash' => csrf_hash()]);
    }

    public function post_startups_viewer()
    {
        $id_startups = decrypt_url($this->request->getPost('id_enc'));

        if (empty($id_startups)) {
            return $this->response->setJSON(['status' => 0, 'message' => 'Invalid ID', 'csrf_hash' => csrf_hash()]);
        }

        $agent    = $this->request->getUserAgent();
        $referrer = $agent->isReferral() ? $agent->getReferrer() : base_url();
        $date     = date('Y-m-d');

        $builder = $this->db->table('tb_analytic_startups');
        $viewer  = $builder->where('id_startups', $id_startups)
            ->where('DATE(date_create)', $date)
            ->where('referral', $referrer)
            ->get()
            ->getRowArray();

        if ($viewer) {
            $builder->where('id_analytic_startups', $viewer['id_analytic_startups'])
                ->increment('hits', 1);
        } else {
            $builder->insert([
                'id_startups' => $id_startups,
                'referral'    => $referrer,
                'hits'        => 1,
                'date_create' => date('Y-m-d H:i:s')
            ]);
        }

        return $this->response->setJSON(['status' => 1, 'message' => 'Success', 'csrf_hash' => csrf_hash()]);
    }

    public function post_webinar_viewer()
    {
        $id_webinar = $this->request->getPost('id');
        $date       = date('Y-m-d');
        $ipaddress  = $this->request->getIPAddress();

        $builder = $this->db->table('tb_analytic_webinar');
        $viewer  = $builder->where('id_webinar', $id_webinar)
            ->where('ip_address', $ipaddress)
            ->where('DATE(date_create)', $date)
            ->get()
            ->getRowArray();

        if ($viewer) {
            $builder->where('id_analytic_webinar', $viewer['id_analytic_webinar'])
                ->increment('hits', 1);
        } else {
            $builder->insert([
                'ip_address'  => $ipaddress,
                'id_webinar'  => $id_webinar,
                'hits'        => 1,
                'date_create' => date('Y-m-d H:i:s')
            ]);
        }

        return $this->response->setJSON(['status' => 1, 'message' => 'Success', 'csrf_hash' => csrf_hash()]);
    }
}
