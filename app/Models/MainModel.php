<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;
use Config\Services;

class MainModel extends Model
{
    protected $db;
    protected $session;
    protected $request;

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
        $this->session = session();
    }

    function logged_in_admin()
    {
        if ($this->session->get('logged_in_admin') == FALSE) {
            $this->session->setFlashdata('flash-warning-message', 'Silahkan login terlebih dahulu.');
            redirect('panel', 'refresh');
        }
    }

    function get_admin()
    {
        $id_admin = decrypt_url($this->session->get('key_auth_admin'));

        return $this->db->table('tb_admin_user')
            ->where('id_admin', $id_admin)
            ->get()->getRowArray();
    }

    function check_access($param)
    {
        $get_user = $this->get_admin();

        // if ($get_user['role'] == 'Admin') {

        $get_data_akses = $this->get_query_menu($param, $get_user['id_role']);

        if ($get_data_akses) {
            if ($get_data_akses['view_data'] == 1) {
                $response['access_add'] = ($get_data_akses['create_data'] == 0) ? 'd-none' : '';
                $response['access_delete'] = ($get_data_akses['delete_data'] == 0) ? 'd-none' : '';
                return $response;
            }
        }

        // } 
        // else {
        //     $access_add = '';
        //     $access_delete = '';
        // }

        redirect('404');
    }

    function check_access_action($param)
    {
        $get_user = $this->get_admin();
        $get_data_akses = $this->get_query_menu($param, $get_user['id_role']);

        if ($get_data_akses) {
            $res['access_view'] = ($get_data_akses['view_data'] == 0) ? 'd-none' : '';
            $res['access_add'] = ($get_data_akses['create_data'] == 0) ? 'd-none' : '';
            $res['access_edit'] = ($get_data_akses['edit_data'] == 0) ? 'd-none' : '';
            $res['access_delete'] = ($get_data_akses['delete_data'] == 0) ? 'd-none' : '';

            $res['access_action'] = ($res['access_view'] == 'd-none' && $res['access_add'] == 'd-none' && $res['access_edit'] == 'd-none' && $res['access_delete'] == 'd-none') ? 'd-none' : '';
        } else {
            $res = ['access_action' => 'd-none', 'access_view' => 'd-none', 'access_add' => 'd-none', 'access_edit' => 'd-none', 'access_delete' => 'd-none'];
        }
        return $res;
    }

    function check_access_menu($param)
    {
        $get_user = $this->get_admin();
        if ($get_user['role'] == 'Admin') {
            $get_data_akses = $this->get_query_menu($param, $get_user['id_role']);
            return ($get_data_akses && $get_data_akses['view_data'] == 1) ? '' : 'd-none';
        }
        return '';
    }

    function get_user($id_user)
    {
        return $this->db->table('tb_user')
            ->select('id_user, nama, email, token, id_business, id_area')
            ->where('status_delete', 0)
            ->where('id_user', $id_user)
            ->get()->getRowArray();
    }

    function get_query_menu($kode_menu, $id_role)
    {
        return $this->db->table('tb_admin_user_privilage p')
            ->select('p.view_data, p.create_data, p.edit_data, p.delete_data')
            ->join('tb_admin_user_menu m', 'p.id_menu = m.id_menu', 'left')
            ->where('p.id_role', $id_role)
            ->where('m.kode_menu', $kode_menu)
            ->get()->getRowArray();
    }

    function get_data_row_array($table, $data)
    {
        return $this->db->table($table)->getWhere($data)->getRowArray();
    }

    function get_where($table, $parameter, $coloumn)
    {
        return $this->db->table($table)->where($coloumn, $parameter)->get()->getResultArray();
    }

    function get_all($table)
    {
        return $this->db->table($table)->get()->getResult();
    }

    function get_all_result_array($table)
    {
        return $this->db->table($table)->get()->getResultArray();
    }

    function get_data($table, $data)
    {
        return $this->db->table($table)->getWhere($data)->getResultArray();
    }

    function get_data_where_order($table, $parameter, $coloum, $order_by)
    {
        return $this->db->table($table)->where($coloum, $parameter)->orderBy($order_by)->get()->getResultArray();
    }

    function get_data_order($table, $order_by)
    {
        return $this->db->table($table)->orderBy($order_by)->get()->getResultArray();
    }

    function update_data($table, $data, $column, $key)
    {
        return $this->db->table($table)->update($data, [$column => $key]);
    }

    function delete_data($table, $data)
    {
        return $this->db->table($table)->delete($data);
    }

    function Count_where($table, $data)
    {
        return $this->db->table($table)->where($data)->countAllResults();
    }

    function Count_total($table)
    {
        return $this->db->table($table)->countAllResults();
    }

    function Pagination_user($number, $offset, $table)
    {
        return $this->db->table($table)->orderBy('id', 'DESC')->get($number, $offset)->getResult();
    }

    function Auto_id($column, $part, $table)
    {
        $row = $this->db->query("SELECT MAX(RIGHT($column,3)) as sta FROM $table")->getRow();
        $id = ($row && $row->sta) ? sprintf("%03s", (int) $row->sta + 1) : "001";
        return $part . $id;
    }

    function initial_value($value)
    {
        $exp_val = explode(' ', $value);

        $initial = '';

        foreach ($exp_val as $key) {
            $initial .= $key[0];
        }

        return $initial;
    }

    function get_admin_web()
    {
        return $this->db->table('tb_admin_web')->where('id', 1)->get()->getRowArray();
    }

    function get_admin_user()
    {
        $id_admin = decrypt_url($this->session->get('key_auth_admin'));

        return $this->db->table('tb_admin_user')
            ->where('id_admin', $id_admin)
            ->get()->getRowArray();
    }

    // --------------------------- datatables ---------------------------
    function count_all($table, $where)
    {
        return $this->db->table($table)
            ->where($where)
            ->countAllResults();
    }

    function total_records($table)
    {
        return $this->db->table($table)->countAll();
    }

    function datatable($valid_columns)
    {
        $start = $this->request->getVar('start') ?? 0;
        $length = $this->request->getVar('length') ?? 10;
        $order = $this->request->getVar('order');
        $search = $this->request->getVar('search')['value'] ?? '';

        $builder = $this->db->table($this->table);
        if ($order) {
            $col = $order[0]['column'];
            $dir = $order[0]['dir'] === 'asc' ? 'asc' : 'desc';
            if (isset($valid_columns[$col]))
                $builder->orderBy($valid_columns[$col], $dir);
        }

        if (!empty($search)) {
            $builder->groupStart();
            foreach ($valid_columns as $i => $column) {
                if ($i === 0)
                    $builder->like($column, $search);
                else
                    $builder->orLike($column, $search);
            }
            $builder->groupEnd();
        }

        return $builder->limit($length, $start);
    }
    // --------------------------- end datatables ---------------------------


    // --------------------------- datatables custom ---------------------------
    function datatable_custom($valid_columns, $group_by, $order_by)
    {
        $start = intval($this->request->getVar('start'));
        $length = intval($this->request->getVar('length'));
        $search = $this->request->getVar('search');
        $search = $search['value'] ?? '';
        $order = $this->request->getVar('order');

        $col = 0;
        $dir = "desc";

        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column'];
                $dir = $o['dir'];
            }
        }

        if ($dir != "asc" && $dir != "desc") {
            $dir = "desc";
        }

        // Apply Ordering
        if (isset($valid_columns[$col])) {
            $this->db->table($this->table)->orderBy($valid_columns[$col], $dir);
        }

        // Apply Search
        if (!empty($search)) {
            $builder = $this->db->table($this->table);
            $builder->groupStart();
            foreach ($valid_columns as $key => $sterm) {
                if ($key === 0) {
                    $builder->like($sterm, $search);
                } else {
                    $builder->orLike($sterm, $search);
                }
            }
            $builder->groupEnd();
        }

        if ($group_by != null) {
            $this->db->table($this->table)->groupBy($group_by);
        }

        if ($order_by != null) {
            $this->db->table($this->table)->orderBy($order_by);
        }

        $this->db->table($this->table)->limit($length, $start);
    }
    // --------------------------- end datatables custom---------------------------

    function cek_token_user($iduser, $token)
    {
        return $this->db->table('tb_user')
            ->select('id_user')
            ->where('id_user', $iduser)
            ->where('token', $token)
            ->get()->getResultArray();
    }

    function insert_table($table, $data)
    {
        return $this->db->table($table)->insert($data);
    }


    function remote_file_exists($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($httpCode == 200);
    }

    function url_banner_popup()
    {
        $builder = $this->db->table('tb_content');
        $query = $builder->select('button_url, image')
            ->where('status_delete', 0)
            ->where('status', 1)
            ->where('section', 'banner_popup')
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()->getRowArray();

        if ($query) {
            return url_image($query['image'], 'image-content');
        }

        return '';
    }

    function tanggal_model($tanggal)
    {
        $date_now = date('Y-m-d');
        $date_ago = date('Y-m-d', strtotime('-6 days', strtotime($date_now)));

        if (date('Y-m-d', strtotime($tanggal)) > $date_ago) {
            return time_ago($tanggal);
        }

        return date('d M Y H:i', strtotime($tanggal));
    }

    function getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = acos((sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta))));
        $kilometers = rad2deg($miles) * 60 * 1.1515 * 1.609344;
        return ($kilometers >= 1) ? floor($kilometers) . ' Kilometer' : floor($kilometers * 1000) . ' Meter';
    }

    function create_thumbs(string $filename, string $dir)
    {
        $source = FCPATH . 'file_media/' . $dir . '/' . $filename;
        $target = FCPATH . 'file_media/' . $dir . '/Thumbnail-S-' . $filename;

        if (!is_file($source)) {
            return false;
        }

        try {
            Services::image('gd')
                ->withFile($source)
                ->resize(
                    0,
                    150,
                    true
                )
                ->save($target);

            return true;
        } catch (\Throwable $e) {
            log_message('error', $e->getMessage());
            return false;
        }
    }

    // Login Attempts
    public function get_login_attempts($ip_address, $email = null, $status = '')
    {
        date_default_timezone_set('Asia/Jakarta');
        $builder = $this->db->table('tb_user_login_attempts')->where(['ip_address' => $ip_address, 'status' => $status]);
        if ($email)
            $builder->where('email', $email);
        $builder->where('attempt_time >=', date("Y-m-d H:i:s", strtotime("-15 minutes")));
        return $builder->countAllResults();
    }

    public function record_login_attempt($ip_address, $email = null, $status = '')
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->db->table('tb_user_login_attempts')->insert([
            'ip_address' => $ip_address,
            'email' => $email,
            'status' => $status,
            'attempt_time' => date('Y-m-d H:i:s')
        ]);
    }

    public function clear_login_attempts($ip_address, $email = null, $status = '')
    {
        $builder = $this->db->table('tb_user_login_attempts');
        $builder->where('ip_address', $ip_address);

        if ($email !== null) {
            $builder->where('email', $email);
        }

        $builder->where('status', $status);
        return $builder->delete();
    }

    // Reset Password Attempts
    public function get_reset_password_attempts($ip_address, $email = null, $status = '')
    {
        date_default_timezone_set('Asia/Jakarta');

        $minutes = 10;
        $time_limit = date("Y-m-d H:i:s", strtotime("-$minutes minutes"));

        $builder = $this->db->table('tb_user_reset_password_attempts');
        $builder->where('ip_address', $ip_address);

        if ($email !== null) {
            $builder->where('email', $email);
        }

        $builder->where('status', $status);
        $builder->where('attempt_time >=', $time_limit);

        return $builder->countAllResults();
    }

    public function record_reset_password_attempt($ip_address, $email = null, $status = '')
    {
        date_default_timezone_set('Asia/Jakarta');
        return $this->db->table('tb_user_reset_password_attempts')->insert([
            'ip_address' => $ip_address,
            'email' => $email,
            'status' => $status,
            'attempt_time' => date('Y-m-d H:i:s')
        ]);
    }

    public function clear_reset_password_attempts($ip_address, $email = null, $status = null)
    {
        $builder = $this->db->table('tb_user_reset_password_attempts');
        $builder->where('ip_address', $ip_address);

        if ($email !== null) {
            $builder->where('email', $email);
        }

        if ($status !== null) {
            $builder->where('status', $status);
        }

        return $builder->delete();
    }
}
