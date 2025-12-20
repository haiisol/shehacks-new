<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Visitor extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    public function Index()
    {   
        $this->main_model->check_access('report');

        $data['title']       = 'Data Visitor';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']        = 'admin/report/visitor';
        $this->load->view('admin/index', $data);
    }

    function data_select()
    {   
        $this->main_model->check_access('report');
        $cact = $this->main_model->check_access_action('report');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 

            $row = array(
                "date" => $this->input->get('date', TRUE),
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('date', 'date', 'trim|required|alpha_numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $fil_periode = $this->input->get('date', TRUE);

            $response = array();

            $data = '';

            $this->db->select('v.page_view');
            $this->db->from('tb_analytic_visitors_2025 v');
            
            if ($fil_periode) {
                if ($fil_periode == 'byhour') {
                    $this->db->where('YEAR(waktu) = YEAR(NOW()) AND MONTH(waktu) = MONTH(NOW()) AND DAY(waktu) = DAY(NOW()) ');
                } 
                else if ($fil_periode == 'bylast7day') {
                    $this->db->where('DATE(waktu) >= DATE_SUB(DATE(NOW()), INTERVAL 7 DAY)');
                } 
                else if ($fil_periode == 'byday') {
                    $this->db->where('YEAR(waktu) = YEAR(NOW()) AND MONTH(waktu) = MONTH(NOW()) ');
                } 
                else if ($fil_periode == 'bymonth') {
                    $this->db->where('YEAR(waktu) = YEAR(NOW())');
                }
            } else {
                $this->db->where('YEAR(waktu) = YEAR(NOW()) AND MONTH(waktu) = MONTH(NOW()) AND DAY(waktu) = DAY(NOW()) ');
            }

            $this->db->group_by('v.page_view');
            $query = $this->db->get()->result_array();

            $data .= '<option value="">Semua Page</option>';
            foreach ($query as $key) {
                $data .= '<option value="'.$key['page_view'].'">'.$key['page_view'].'</option>';
            }

            $response = array(
                'data'  => $data
            );
        }

        json_response($response);
    }

    function _sql_page_view()
    {
        $fil_page       = trim($this->input->get('fil_page'));
        $fil_periode    = trim($this->input->get('fil_periode'));
        
        $this->db->select('v.id, v.page_view, COUNT(DISTINCT v.ip_address) as total_ip, SUM(v.hits) as total_hits');
        $this->db->from('tb_analytic_visitors_2025 v');

        if ($fil_page) {
            if ($fil_page != 'all') {
                $this->db->where('v.page_view', $fil_page);
            }
        }
        
        if ($fil_periode) {
            if ($fil_periode == 'byhour') {
                $this->db->where('YEAR(waktu) = YEAR(NOW()) AND MONTH(waktu) = MONTH(NOW()) AND DAY(waktu) = DAY(NOW()) ');
            } 
            else if ($fil_periode == 'bylast7day') {
                $this->db->where('DATE(waktu) >= DATE_SUB(DATE(NOW()), INTERVAL 7 DAY)');
            } 
            else if ($fil_periode == 'byday') {
                $this->db->where('YEAR(waktu) = YEAR(NOW()) AND MONTH(waktu) = MONTH(NOW()) ');
            } 
            else if ($fil_periode == 'bymonth') {
                $this->db->where('YEAR(waktu) = YEAR(NOW())');
            }
        }

        $this->db->group_by('v.page_view');
        
        return $this->db->get();
    }

// --------------------------- chart visitor ---------------------------
    function fetch_data_chart_visitor()
    {   
        $this->main_model->check_access('report');
        $cact = $this->main_model->check_access_action('report');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 

            $row = array(
                "fil_periode" => $this->input->get('fil_periode', TRUE),
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('fil_periode', 'fil_periode', 'trim|alpha_numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $fil_periode = trim($this->input->get('fil_periode'));

            $data_chart = array();

            if ($fil_periode == 'byhour') {
                $title = date_ind(date('Y-m-d'), 'full');
                $data_chart = $this->fetch_visitor_byhour();
            } 
            else if ($fil_periode == 'bylast7day') {
                $title = '7 Hari Terakhir';
                $data_chart = $this->fetch_visitor_bylast7day();
            } 
            else if ($fil_periode == 'byday') {
                $title = conv_month(date('m')).' '.date('Y');
                $data_chart = $this->fetch_visitor_byday();
            } 
            else if ($fil_periode == 'bymonth') {
                $title = date('Y');
                $data_chart = $this->fetch_visitor_bymonth();
            }
            
            
            $response['card_title']   = $title;
            $response['data_chart']   = $data_chart;
            $response['status']       = 1;
        }
       
        json_response($response);
    }

    function fetch_visitor_byhour()
    {
        // data
        $array_datasets = array();

        $get_page = $this->_sql_page_view('chart');

        foreach ($get_page->result_array() as $key) {

            $row['label']       = $key['page_view'];
            $row['data']        = array();
            $row['borderColor'] = generateRandomColorHex();

            for ($i=0; $i<24; $i++) {
                $hour = str_pad($i, 2, '0', STR_PAD_LEFT);
                $query = $this->db->query("SELECT COUNT(DISTINCT ip_address) as total_ip, SUM(hits) as total_hits FROM tb_analytic_visitors_2025 WHERE page_view = '".$key['page_view']."' AND YEAR(waktu) = YEAR(NOW()) AND MONTH(waktu) = MONTH(NOW()) AND DAY(waktu) = DAY(NOW()) AND HOUR(waktu) = '".$hour."' ")->row_array();

                // if($query['total_ip']) { $total_ip = (float)$query['total_ip']; } else { $total_ip = 0; }
                if($query['total_hits']) { $total_hits = (float)$query['total_hits']; } else { $total_hits = 0; }
                
                array_push($row['data'], $total_hits);
            }

            array_push($array_datasets, $row);
        }
        
        // label
        $array_labels = array();

        for ($i=0; $i<24; $i++) {
            $hour = str_pad($i, 2, '0', STR_PAD_LEFT);
            $labels = $hour;
            array_push($array_labels, $labels);
        }

        // result
        $result['labels']     = $array_labels;
        $result['datasets']   = $array_datasets;

        return $result;
    }

    function fetch_visitor_bylast7day()
    {
        // data
        $array_datasets = array();

        $get_page = $this->_sql_page_view('chart');

        foreach ($get_page->result_array() as $key) {

            $row['label']       = $key['page_view'];
            $row['data']        = array();
            $row['borderColor'] = generateRandomColorHex();

            for ($i = 6; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $query = $this->db->query("SELECT COUNT(DISTINCT ip_address) as total_ip, SUM(hits) as total_hits FROM tb_analytic_visitors_2025 WHERE page_view = '".$key['page_view']."' AND DATE(waktu) = '".$date."' ")->row_array();

                // if($query['total_ip']) { $total_ip = (float)$query['total_ip']; } else { $total_ip = 0; }
                if($query['total_hits']) { $total_hits = (float)$query['total_hits']; } else { $total_hits = 0; }
                
                array_push($row['data'], $total_hits);
            }

            array_push($array_datasets, $row);
        }
        
        // label
        $array_labels = array();

        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $labels = date('d', strtotime($date)).' '.conv_month_medium(date('m'));
            array_push($array_labels, $labels);
        }

        // result
        $result['labels']     = $array_labels;
        $result['datasets']   = $array_datasets;

        return $result;
    }

    function fetch_visitor_byday()
    {
        // data
        $array_datasets = array();

        $get_page = $this->_sql_page_view('chart');

        foreach ($get_page->result_array() as $key) {

            $row['label']       = $key['page_view'];
            $row['data']        = array();
            $row['borderColor'] = generateRandomColorHex();

            for ($i=1; $i<32; $i++) { 
                $query = $this->db->query("SELECT COUNT(DISTINCT ip_address) as total_ip, SUM(hits) as total_hits FROM tb_analytic_visitors_2025 WHERE page_view = '".$key['page_view']."' AND YEAR(waktu) = YEAR(NOW()) AND MONTH(waktu) = MONTH(NOW()) AND DAY(waktu) = '".$i."' ")->row_array();

                // if($query['total_ip']) { $total_ip = (float)$query['total_ip']; } else { $total_ip = 0; }
                if($query['total_hits']) { $total_hits = (float)$query['total_hits']; } else { $total_hits = 0; }
                
                array_push($row['data'], $total_hits);
            }

            array_push($array_datasets, $row);
        }
        
        // label
        $array_labels = array();
        for ($i=1; $i<32; $i++) { 
            $labels = $i;
            array_push($array_labels, $labels);
        }

        // result
        $result['labels']     = $array_labels;
        $result['datasets']   = $array_datasets;

        return $result;
    }

    function fetch_visitor_bymonth()
    {
        // data
        $array_datasets = array();

        $get_page = $this->_sql_page_view('chart');

        foreach ($get_page->result_array() as $key) {

            $row['label']       = $key['page_view'];
            $row['data']        = array();
            $row['borderColor'] = generateRandomColorHex();

            for ($i=1; $i<13; $i++) { 
                $query = $this->db->query("SELECT COUNT(DISTINCT ip_address) as total_ip, SUM(hits) as total_hits FROM tb_analytic_visitors_2025 WHERE page_view = '".$key['page_view']."' AND YEAR(waktu) = YEAR(NOW()) AND MONTH(waktu) = '".$i."' ")->row_array();

                // if($query['total_ip']) { $total_ip = (float)$query['total_ip']; } else { $total_ip = 0; }
                if($query['total_hits']) { $total_hits = (float)$query['total_hits']; } else { $total_hits = 0; }

                array_push($row['data'], $total_hits);
            }

            array_push($array_datasets, $row);
        }

        // label
        $array_labels = array();

        for ($i=1; $i<13; $i++) { 
            $labels = conv_month_medium($i);
            array_push($array_labels, $labels);
        }

        // result
        $result['labels']     = $array_labels;
        $result['datasets']   = $array_datasets;

        return $result;
    }
// --------------------------- end chart visitor ---------------------------


// --------------------------- url pie ---------------------------

    function load_data_url()
    {   
        $this->main_model->check_access('report');
        $cact = $this->main_model->check_access_action('report');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 

        
            $this->load->library('form_validation');
            $row = array(
                "fil_periode"       => $this->input->get('fil_periode', TRUE),
                "param"             => $this->input->post('param', TRUE),
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('fil_periode', 'fil_periode', 'trim|required|alpha_numeric');
            $this->form_validation->set_rules('param', 'param', 'trim|required|alpha_numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $fil_page       = trim($this->input->get('fil_page', TRUE));
            $fil_periode    = trim($this->input->get('fil_periode', TRUE));
            $param          = trim($this->input->post('param', TRUE));

            $array_labels   = array();
            $array_datasets = array();

            if ($param == 'referral') {
                $this->db->select('v.id, v.referral as nama, COUNT(DISTINCT v.id) as total');
            } else {
                $this->db->select('v.id, v.page_view as nama, SUM(v.hits) as total');
            }
            
            $this->db->from('tb_analytic_visitors_2025 v');

            if ($fil_page) {
                if ($fil_page != 'all') {
                    $this->db->where('v.page_view', $fil_page);
                }
            }
            
            if ($fil_periode) {
                if ($fil_periode == 'byhour') {
                    $this->db->where('YEAR(waktu) = YEAR(NOW()) AND MONTH(waktu) = MONTH(NOW()) AND DAY(waktu) = DAY(NOW())');
                } 
                else if ($fil_periode == 'bylast7day') {
                    $this->db->where('DATE(waktu) >= DATE_SUB(DATE(NOW()), INTERVAL 7 DAY)');
                } 
                else if ($fil_periode == 'byday') {
                    $this->db->where('YEAR(waktu) = YEAR(NOW()) AND MONTH(waktu) = MONTH(NOW())');
                } 
                else if ($fil_periode == 'bymonth') {
                    $this->db->where('YEAR(waktu) = YEAR(NOW())');
                }
            }

            if ($param == 'referral') {
                $this->db->group_by('v.referral');
            } else {
                $this->db->group_by('v.page_view');
            }

            $get_data  = $this->db->get()->result_array();

            foreach ($get_data as $key) {
                array_push($array_labels, $key['nama']);
                array_push($array_datasets, $key['total']);
            }

            $response['labels']       = $array_labels;
            $response['datasets']     = $array_datasets;
            $response['status']       = 1;
        }
       
        json_response($response);
    }
// --------------------------- end url pie ---------------------------

// --------------------------- data header ---------------------------
    function load_data_header()
    {   
        $this->main_model->check_access('report');
        $cact = $this->main_model->check_access_action('report');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 

            $response['total_pengunjung'] = $this->_sql_header('pengunjung');
            $response['total_referral']   = $this->_sql_header('referral');
            $response['total_unik_ip']    = $this->_sql_header('unik_ip');
            $response['status']           = 1;
        }
        
        json_response($response);
    }

    function _sql_header($param){

        $fil_page       = trim($this->input->get('fil_page'));
        $fil_periode    = trim($this->input->get('fil_periode'));

        if ($param == 'pengunjung') {
            $this->db->select('SUM(v.hits) as total');
        } else if ($param == 'referral') {
            $this->db->select('COUNT(DISTINCT v.referral) as total');
        } else {
            $this->db->select('COUNT(DISTINCT v.ip_address) as total');
        }
        
        $this->db->from('tb_analytic_visitors_2025 v');

        if ($fil_page) {
            if ($fil_page != 'all') {
                $this->db->where('v.page_view', $fil_page);
            }
        }
        
        if ($fil_periode) {
            if ($fil_periode == 'byhour') {
                $this->db->where('YEAR(waktu) = YEAR(NOW()) AND MONTH(waktu) = MONTH(NOW()) AND DAY(waktu) = DAY(NOW())');
            } 
            else if ($fil_periode == 'bylast7day') {
                $this->db->where('DATE(waktu) >= DATE_SUB(DATE(NOW()), INTERVAL 7 DAY)');
            } 
            else if ($fil_periode == 'byday') {
                $this->db->where('YEAR(waktu) = YEAR(NOW()) AND MONTH(waktu) = MONTH(NOW())');
            } 
            else if ($fil_periode == 'bymonth') {
                $this->db->where('YEAR(waktu) = YEAR(NOW())');
            }
        }

        $get_data  = $this->db->get()->row_array();

        if ($get_data['total']) {
            $total = number_format($get_data['total']);
        } else {
            $total = 0;
        }

        return $total;
        

    }

    // --------------------------- end data header ---------------------------

    public function valid_huruf_angka_spasi($str) {
        if (preg_match('/^[a-zA-Z0-9 ]+$/', $str)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('valid_huruf_angka_spasi', 'Kolom {field} hanya boleh berisi huruf, angka, dan spasi.');
            return FALSE;
        }
    }

}