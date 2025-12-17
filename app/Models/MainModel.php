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
        $this->session = Services::session();
        $this->request = Services::request();
    }

    function logged_in_front()
    {
        if ($this->session->get('logged_in_front') == FALSE) {
            redirect('', 'refresh');
        } else {

            $id_user        = key_auth();
            $key_token      = $this->session->get('key_token');

            $builder = $this->db->table('tb_user_2fa');
            $cek_logout_status = $builder->select('logout_status')
                ->where('access_policy', 'FE')
                ->where('id_user', $id_user)
                ->where('code_encrypt', $key_token)
                ->get()->getRowArray();

            if ($cek_logout_status) {
                if ($cek_logout_status['logout_status'] == 'true') {
                    redirect('', 'refresh');
                }
            } else {
                redirect('', 'refresh');
            }
        }
    }

    function logged_in_admin()
    {
        if ($this->session->get('logged_in_admin') == FALSE) {
            $this->session->setFlashdata('flash-warning-message', 'Silahkan login terlebih dahulu.');
            redirect('panel', 'refresh');
        }
    }

    function unset_log_redirect()
    {

        $this->session->remove("log_redirect");
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
                $response['access_add']    = ($get_data_akses['create_data'] == 0) ? 'd-none' : '';
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
            $res['access_view']   = ($get_data_akses['view_data'] == 0) ? 'd-none' : '';
            $res['access_add']    = ($get_data_akses['create_data'] == 0) ? 'd-none' : '';
            $res['access_edit']   = ($get_data_akses['edit_data'] == 0) ? 'd-none' : '';
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
        $id = ($row && $row->sta) ? sprintf("%03s", (int)$row->sta + 1) : "001";
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

    // TODO: Check the rest (model, controller and view)

    function get_admin_user()
    {
        $id_admin = decrypt_url($this->session->get('key_auth_admin'));
        $query = $this->db->query("SELECT * FROM tb_admin_user WHERE id_admin = '" . $id_admin . "' ")->row_array();

        return $query;
    }



    // --------------------------- post visitors ---------------------------
    function post_visitors_2023($page)
    {
        $this->load->library('user_agent');

        $date      = date('Y-m-d');
        $ipaddress = $_SERVER['REMOTE_ADDR'];

        $visitor = $this->db->query("SELECT * FROM tb_analytic_visitors_2025 WHERE date='" . $date . "' AND page_view='" . $page . "' AND ip_address='" . $ipaddress . "'")->row_array();

        if ($visitor) {
            $hits = $visitor['hits'];
            $this->db->query("UPDATE tb_analytic_visitors_2025 SET hits=$hits+1 WHERE date='" . $date . "' AND page_view='" . $page . "' AND ip_address='" . $ipaddress . "'");
        } else {
            if ($this->agent->is_referral()) {
                $referrer = $this->agent->referrer();
            } else {
                $referrer = base_url();
            }

            $visit['referral']   = $referrer;
            $visit['ip_address'] = $ipaddress;
            $visit['page_view']  = $page;
            $visit['date']       = $date;
            $visit['hits']       = 1;
            $visit['browser']    = $this->agent->browser();
            $visit['platform']   = $this->agent->platform();
            $visit['waktu']      = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');

            $this->db->insert('tb_analytic_visitors_2025', $visit);
        }
    }
    // --------------------------- end post visitors ---------------------------


    // --------------------------- datatables ---------------------------
    function count_all($table, $where)
    {
        $this->db->from($table);
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    function total_records($table)
    {
        $query = $this->db->select("COUNT(*) as total")->get($table)->row();

        if (isset($query)) return $query->total;
        return 0;
    }

    function datatable($valid_columns)
    {
        $start  = intval($this->input->post('start') ?: $this->input->get('start'));
        $length = intval($this->input->post('length') ?: $this->input->get('length'));
        $order  = $this->input->post('order') ?: $this->input->get('order');
        $search = $this->input->post('search') ?: $this->input->get('search');
        $search = $search['value'];
        $col    = 0;
        $dir    = "";

        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column'];
                $dir = $o['dir'];
            }
        }
        if ($dir != "asc" && $dir != "desc") {
            $dir = "desc";
        }

        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];
        }

        if ($order != null) {
            $this->db->order_by($order, $dir);
        }

        if (!empty($search)) {
            $x = 0;
            foreach ($valid_columns as $sterm) {
                if ($x == 0) {
                    $this->db->like($sterm, $search);
                } else {
                    $this->db->or_like($sterm, $search);
                }
                $x++;
            }
        }

        $this->db->limit($length, $start);
    }
    // --------------------------- end datatables ---------------------------


    // --------------------------- datatables custom ---------------------------
    function datatable_custom($valid_columns, $group_by, $order_by)
    {
        $start  = intval($this->input->post('start') ?: $this->input->get('start'));
        $length = intval($this->input->post('length') ?: $this->input->get('length'));
        $search = $this->input->post('search') ?: $this->input->get('search');
        $search = $search['value'];
        $col    = 0;
        $dir    = "";

        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column'];
                $dir = $o['dir'];
            }
        }
        if ($dir != "asc" && $dir != "desc") {
            $dir = "desc";
        }

        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];
        }

        if ($order != null) {
            $this->db->order_by($order, $dir);
        }

        if (!empty($search)) {
            $x = 0;
            foreach ($valid_columns as $sterm) {
                if ($x == 0) {
                    $this->db->like($sterm, $search);
                } else {
                    $this->db->or_like($sterm, $search);
                }
                $x++;
            }
        }

        if ($group_by != null) {
            $this->db->group_by($group_by);
        }

        if ($order_by != null) {
            $this->db->order_by($order_by);
        }

        $this->db->limit($length, $start);
    }
    // --------------------------- end datatables custom---------------------------

    function cek_token_user($iduser, $token)
    {
        $this->db->select('id_user');
        $this->db->where('id_user', $iduser);
        $this->db->where('token', $token);

        $result = $this->db->get('tb_user')->result_array();

        return $result;
    }

    function insert_table($table, $data)
    {
        $result = $this->db->insert($table, $data);

        return $result;
    }


    function remote_file_exists($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode == 200) {
            return true;
        }
    }

    function url_image($nama_file, $folder_image)
    {
        if ($nama_file) {
            $result = $this->config->item('url_api_file') . $folder_image . '/' . $nama_file;
        } else {
            $result = default_image();
        }

        return $result;
    }

    function url_image_thumbnail($nama_file, $folder_image)
    {
        if ($nama_file) {
            if (file_exists(FCPATH . 'file_media/' . $folder_image . '/Thumbnail-S-' . $nama_file)) {
                $result = $this->config->item('url_api_file') . $folder_image . '/Thumbnail-S-' . $nama_file;
            } else {
                $result = '-';
            }
        } else {
            $result = '-';
        }

        return $result;
    }

    function url_image_admin($nama_file)
    {
        if ($nama_file) {
            $result = $this->config->item('url_api_file') . 'image-admin/' . $nama_file;
        } else {
            $result = default_image();
        }

        return $result;
    }

    function url_banner_popup()
    {
        $sql = "SELECT c.button_url, c.image  
                FROM tb_content c 
                WHERE c.status_delete = 0 
                AND status = 1
                AND c.section = 'banner_popup' 
                ORDER BY c.id DESC LIMIT 1 ";

        $query = $this->db->query($sql)->row_array();

        if ($query) {
            $result = $this->main_model->url_image($query['image'], 'image-content');
        } else {
            $result = '';
        }

        return $result;
    }

    function tanggal_model($tanggal)
    {
        $date_now = date('Y-m-d');
        $date_ago = date('Y-m-d', strtotime('-6 days', strtotime($date_now)));

        if (date('Y-m-d', strtotime($tanggal)) > $date_ago) {

            $result = time_ago($tanggal);
        } else {

            $result = date('d M Y H:i', strtotime($tanggal));
        }

        return $result;
    }

    function getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;

        if (floor($kilometers) >= 1) {
            $return = floor($kilometers) . ' Kilometer';
        } else {
            $return = floor($meters) . ' Meter';
        }

        return $return;
    }

    function _create_thumbs($filename, $dir)
    {

        $source     = './file_media/' . $dir . '/' . $filename;
        $source_new = './file_media/' . $dir . '/Thumbnail-S-' . $filename;

        // Image resizing config
        $config = array(
            // 150
            array(
                'image_library' => 'GD2',
                'source_image'  => $source,
                'maintain_ratio' => TRUE,
                //'width'       => 150,
                'height'        => 150,
                'new_image'     => $source_new
            )
        );

        $this->load->library('image_lib', $config[0]);

        foreach ($config as $item) {
            $this->image_lib->initialize($item);
            if (!$this->image_lib->resize()) {
                return false;
            }
            $this->image_lib->clear();
        }
    }

    function get_umur($tanggal)
    {
        $tanggal_lahir = date('Y-m-d', strtotime($tanggal));
        $birthDate = new \DateTime($tanggal_lahir);
        $today = new \DateTime("today");
        if ($birthDate > $today) {
            return 0;
        }

        $y = $today->diff($birthDate)->y;
        $m = $today->diff($birthDate)->m;
        $d = $today->diff($birthDate)->d;

        return $y;
    }

    // Login Attempts
    public function get_login_attempts($ip_address, $email = null, $status)
    {
        date_default_timezone_set('Asia/Jakarta');
        $minutes    = 15;
        $time_limit = date("Y-m-d H:i:s", strtotime("-$minutes minutes"));
        $this->db->where('ip_address', $ip_address);
        if ($email !== null) {
            $this->db->where('email', $email);
        }

        $this->db->where('status', $status);
        $this->db->where('attempt_time >=', $time_limit);
        return $this->db->count_all_results('tb_user_login_attempts');
    }

    public function record_login_attempt($ip_address, $email = null, $status)
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->db->insert('tb_user_login_attempts', [
            'ip_address' => $ip_address,
            'email' => $email,
            'status' => $status,
            'attempt_time' => date('Y-m-d H:i:s')
        ]);
    }

    public function clear_login_attempts($ip_address, $email = null, $status)
    {
        $this->db->where('ip_address', $ip_address);
        if ($email !== null) {
            $this->db->where('email', $email);
        }

        $this->db->where('status', $status);
        $this->db->delete('tb_user_login_attempts');
    }

    // Reset Password Attempts
    public function get_reset_password_attempts($ip_address, $email = null, $status)
    {
        date_default_timezone_set('Asia/Jakarta');

        $minutes    = 10;
        $time_limit = date("Y-m-d H:i:s", strtotime("-$minutes minutes"));
        $this->db->where('ip_address', $ip_address);
        if ($email !== null) {
            $this->db->where('email', $email);
        }

        $this->db->where('status', $status);
        $this->db->where('attempt_time >=', $time_limit);
        return $this->db->count_all_results('tb_user_reset_password_attempts');
    }

    public function record_reset_password_attempt($ip_address, $email = null, $status)
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->db->insert('tb_user_reset_password_attempts', [
            'ip_address' => $ip_address,
            'email' => $email,
            'status' => $status,
            'attempt_time' => date('Y-m-d H:i:s')
        ]);
    }

    public function clear_reset_password_attempts($ip_address, $email = null, $status = null)
    {
        $this->db->where('ip_address', $ip_address);
        if ($email !== null) {
            $this->db->where('email', $email);
        }

        if ($status !== null) {
            $this->db->where('status', $status);
        }

        $this->db->delete('tb_user_reset_password_attempts');
    }
}
