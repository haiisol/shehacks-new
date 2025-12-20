<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Lms extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    public function Index()
    {   

        $data = $this->main_model->check_access('report');
        
        $data['title']       = 'Data Pengunjung LMS';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']           = 'admin/report/lms';
        $this->load->view('admin/index', $data);
    }

    function _sql($group_by) 
    {
        $fil_date               = trim($this->input->get('fil_date') ?? '');

        $tgl_pertama = date('01/m/Y', strtotime(date('Y-m-d')));
        $tgl_terakhir = date('t/m/Y', strtotime(date('Y-m-d')));

        if (empty($fil_date)) {
            $fil_date_new = $tgl_pertama.' - '.$tgl_terakhir;
        } else {
            $fil_date_new = $fil_date;
        }

        $tgl_buffer = explode('-', $fil_date_new);
        $tgl_start  = DateTime::createFromFormat('d/m/Y', trim($tgl_buffer[0]))->format('Y-m-d');
        $tgl_end    = DateTime::createFromFormat('d/m/Y', trim($tgl_buffer[1]))->format('Y-m-d');
        
        $tgl_start_query = $this->db->escape_str($tgl_start).' 00:00:00';
        $tgl_end_query   = $this->db->escape_str($tgl_end).' 23:59:59';
        
        $this->db->select('
            p.id,
            m.modul,
            COUNT(DISTINCT p.id_video) as total_video,
            COUNT(p.id_user) as total_user
        ');
        $this->db->from('edu_modul_progress p USE INDEX (progress_user)'); 
        $this->db->join('edu_modul m', 'p.id_modul = m.id_modul', 'LEFT');   
        $this->db->where('p.id_video >','0');
        $this->db->where('p.date_create !=','0000-00-00 00:00:00');

        if ($fil_date) {
            $this->db->where('p.date_create BETWEEN "'.$tgl_start_query .'" AND "'.$tgl_end_query.'" ');
        }

        if($group_by !=null) {
            $this->db->group_by($group_by);
        }
    

        return $this->db->get();
    }

    function datatables()
    {   
        $this->main_model->check_access('report');

        $valid_columns = array(
            1 => 'p.id',
            2 => 'm.modul'
        );

        $this->main_model->datatable_custom($valid_columns, ' p.id_video', ' total_user DESC ');

        $query = $this->_sql(null);

        $no   = $this->input->get('start');
        $data = array();

        foreach($query->result_array() as $key) {
            $no++;

            $cact = $this->main_model->check_access_action('report');
        
            $data[] = array(
                $no,
                character_limiter($key['modul'], 55),
                number_format($key['total_video'], 0,",","."),
                number_format($key['total_user'], 0,",","."),
            );
        }

        $total_user  = $this->_sql_total('user');
        $total_modul = $this->_sql_total('modul');
        $total_video = $this->_sql_total('video');

        $response['draw']            = intval($this->input->get("draw"));
        $response['recordsTotal']    = $this->_sql(' p.id_video')->num_rows();
        $response['recordsFiltered'] = $this->_sql(' p.id_video')->num_rows();
        $response['total_user']      = $total_user;
        $response['total_modul']     = $total_modul;
        $response['total_video']     = $total_video;
        $response['data']            = $data;

        json_response($response);
    }

    function _sql_total($param) 
    {
        $fil_date       = trim($this->input->get('fil_date', TRUE) ?? '');

        $tgl_pertama    = date('01/m/Y', strtotime(date('Y-m-d')));
        $tgl_terakhir   = date('t/m/Y', strtotime(date('Y-m-d')));

        if (empty($fil_date)) {
            $fil_date_new = $tgl_pertama.' - '.$tgl_terakhir;
        } else {
            $fil_date_new = $fil_date;
        }

        $tgl_buffer = explode('-', $fil_date_new);
        $tgl_start  = DateTime::createFromFormat('d/m/Y', trim($tgl_buffer[0]))->format('Y-m-d');
        $tgl_end    = DateTime::createFromFormat('d/m/Y', trim($tgl_buffer[1]))->format('Y-m-d');
        
        $tgl_start_query = $this->db->escape_str($tgl_start).' 00:00:00';
        $tgl_end_query   = $this->db->escape_str($tgl_end).' 23:59:59';
        
        if ($param == 'user') {
            $select = ' COUNT(p.id_user) as total ';
        } elseif ($param == 'modul') {
            $select = ' COUNT(DISTINCT p.id_video) as total';
        } else {
            $select = ' COUNT(DISTINCT p.id_modul) as total ';
        }
        
        $this->db->select('
            '.$select.'
        ');

        $this->db->from('edu_modul_progress p USE INDEX (progress_user)'); 
        $this->db->where('p.id_video >','0');
        $this->db->where('p.date_create !=','0000-00-00 00:00:00');

        if ($fil_date) {
            $this->db->where('p.date_create BETWEEN "'.$tgl_start_query .'" AND "'.$tgl_end_query.'" ');
        }

        $query = $this->db->get()->row_array();

        $total = 0;

        if ($query) {
            if ($query['total']) {
                $total = number_format($query['total'], 0,",",".");
            }
        }
        
        return $total;
    }

    function load_data_pengunjung()
    {   
        $this->main_model->check_access('report');
        $cact = $this->main_model->check_access_action('report');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 

            $fil_date       = trim($this->input->get('fil_date'));
            $param          = trim($this->input->get('param'));

            $tgl_pertama    = date('01/m/Y', strtotime(date('Y-m-d')));
            $tgl_terakhir   = date('t/m/Y', strtotime(date('Y-m-d')));

            if (empty($fil_date)) {
                $fil_date_new = $tgl_pertama.' - '.$tgl_terakhir;
            } else {
                $fil_date_new = $fil_date;
            }

            $tgl_buffer = explode('-', $fil_date_new);
            $tgl_start  = DateTime::createFromFormat('d/m/Y', trim($tgl_buffer[0]))->format('Y-m-d');
            $tgl_end    = DateTime::createFromFormat('d/m/Y', trim($tgl_buffer[1]))->format('Y-m-d');
            
            $tgl_start_query = $this->db->escape_str($tgl_start).' 00:00:00';
            $tgl_end_query   = $this->db->escape_str($tgl_end).' 23:59:59';

            $array_labels   = array();
            $array_datasets = array();
            // $array_list     = array();

            $where = '';
            if ($fil_date) {
                
                $where = ' AND p.date_create BETWEEN "'.$tgl_start_query .'" AND "'.$tgl_end_query.'" ';
            }
            

            $get_data = $this->db->query("
                SELECT IFNULL(m.modul, '-') as nama, COUNT(p.id_user) as total
                FROM edu_modul_progress p USE INDEX (progress_user) 
                LEFT JOIN edu_modul m ON p.id_modul = m.id_modul 
                WHERE p.id_video > '0'
                AND p.date_create != '0000-00-00 00:00:00'
                ".$where." 
                GROUP BY p.id_modul
                ORDER BY total DESC ")->result_array();

            foreach ($get_data as $key) {
                array_push($array_labels, $key['nama']);
                array_push($array_datasets, $key['total']);

                // $row['label'] = $key['nama'];
                // $row['value'] = $key['total'];
                // array_push($array_list, $row);
            }

            $response['labels']       = $array_labels;
            $response['datasets']     = $array_datasets;
            // $response['data_list']    = $array_list;
            $response['status']       = 1;
        }

        json_response($response);
    }

    function fetch_data_total_date()
    {   
        $this->main_model->check_access('report');
        $cact = $this->main_model->check_access_action('report');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 

            $this->load->library('form_validation');
            $row = array(
                "value" => $this->input->get('value', TRUE),
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('value', 'value', 'trim|required|callback_valid_huruf_angka_spasi');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $value          = trim($this->input->get('value', TRUE));
            $array_labels   = array();
            $array_datasets = array();
            $date  = date('Y-m-d');
            $hours = date('H');
            $days  = date('d');
            $month = date('m');
            $year  = date('Y');

            if ($value == 'last7') {
                $array_date  = $this->_last7();
                $label_title = 'Total Pengunjung Belajar 7 Hari Terakhir';

                foreach ($array_date as $key) {

                    $get = $this->db->query("
                        SELECT COUNT(p.id_user) as total 
                        FROM edu_modul_progress p USE INDEX (progress_user)
                        WHERE p.id_video > '0'
                        AND p.date_create != '0000-00-00 00:00:00' 
                        AND DATE(p.date_create) = '".$key."' ")->row_array();
                    
                    if($get) { $value = (float)$get['total']; } else { $value = 0; }

                    array_push($array_labels, date('d-m-Y', strtotime($key)));
                    array_push($array_datasets, $value);
                }

            } elseif ($value == 'month') {

                $array_date  = $this->_month();
                $label_title = 'Total Pengunjung Belajar Bulan Ini';

                foreach ($array_date as $key => $val) {

                    $get = $this->db->query("
                        SELECT COUNT(p.id_user) as total 
                        FROM edu_modul_progress p USE INDEX (progress_user)
                        WHERE p.id_video > '0'
                        AND p.date_create != '0000-00-00 00:00:00' 
                        AND DAY(p.date_create)='".$val."' 
                        AND MONTH(p.date_create)='".$month."' 
                        AND YEAR(p.date_create)='".$year."' ")->row_array();
                    
                    if($get) { $value = (float)$get['total']; } else { $value = 0; }

                    array_push($array_labels, $val);
                    array_push($array_datasets, $value);
                }

            } elseif ($value == 'year') {

                $array_date  = $this->_year();
                $label_title = 'Total Pengunjung Belajar Tahun Ini';

                foreach ($array_date as $key => $val) {

                    $get = $this->db->query("
                        SELECT COUNT(p.id_user) as total 
                        FROM edu_modul_progress p USE INDEX (progress_user)
                        WHERE p.id_video > '0'
                        AND p.date_create != '0000-00-00 00:00:00' 
                        AND MONTH(p.date_create)='".$val."' 
                        AND YEAR(p.date_create)='".$year."' ")->row_array();
                    
                    if($get) { $value = (float)$get['total']; } else { $value = 0; }
                 
                    array_push($array_datasets, $value);
                }

                $array_labels = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            } else {
                $array_date  = $this->_today();
                $label_title = 'Total Pengunjung Belajar Hari Ini';

                foreach ($array_date as $key => $val) {

                    $get = $this->db->query("
                        SELECT COUNT(p.id_user) as total 
                        FROM edu_modul_progress p USE INDEX (progress_user)
                        WHERE p.id_video > '0'
                        AND p.date_create != '0000-00-00 00:00:00' 
                        AND (TIME(p.date_create) > '".$val.":00' 
                        AND TIME(p.date_create) < '".$val.":59')
                        AND DAY(p.date_create) = '".$days."'
                        AND MONTH(p.date_create) ='".$month."' 
                        AND YEAR(p.date_create) ='".$year."' ")->row_array();
                    
                    if($get) { $value = (float)$get['total']; } else { $value = 0; }

                    array_push($array_datasets, $value);
                }

                $array_labels = ['01:00','02:00','03:00','04:00','05:00','06:00','07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00','23:00','24:00'];
            }


            $response['labels']       = $array_labels;
            $response['datasets']     = $array_datasets;
            $response['label_title']  = $label_title;
            $response['status']       = 1;
        }
       
        json_response($response);
    }

    function _today(){

        $array_jam = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24'];

        return $array_jam;
    }

    function _last7(){

        $date       = date('Y-m-d');

        $fordate[]  = date('Y-m-d');
        $fordate[]  = date('Y-m-d', strtotime('-1 days', strtotime($date)));
        $fordate[]  = date('Y-m-d', strtotime('-2 days', strtotime($date)));
        $fordate[]  = date('Y-m-d', strtotime('-3 days', strtotime($date)));
        $fordate[]  = date('Y-m-d', strtotime('-4 days', strtotime($date)));
        $fordate[]  = date('Y-m-d', strtotime('-5 days', strtotime($date)));
        $fordate[]  = date('Y-m-d', strtotime('-6 days', strtotime($date)));


        $array_date = array_reverse($fordate);

        return $array_date;
    }

    function _month(){

        $array_date = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];

        return $array_date;
    }

    function _year(){

        $array_month = ['01','02','03','04','05','06','07','08','09','10','11','12'];

        return $array_month;
    }

    public function valid_huruf_angka_spasi($str) {
        if (preg_match('/^[a-zA-Z0-9 ]+$/', $str)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('valid_huruf_angka_spasi', 'Kolom {field} hanya boleh berisi huruf, angka, dan spasi.');
            return FALSE;
        }
    }



}
