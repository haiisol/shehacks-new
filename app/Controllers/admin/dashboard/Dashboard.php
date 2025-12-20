<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->main_model->logged_in_admin();
		$this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
	}

	public function index()
	{   
        $this->main_model->check_access('dashboard');

		$data['title']		 = 'Welcome to Dashboard';
		$data['description'] = '';
		$data['keywords']    = '';
		$data['page']        = 'admin/dashboard/dashboard';
		$this->load->view('admin/index', $data);
	}

    function data_info() 
    {   
        $this->main_model->check_access('dashboard');
        $cact = $this->main_model->check_access_action('dashboard');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 

            $row = array(
                "value" => $this->input->get('value', TRUE),
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('value', 'value', 'trim|alpha_numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $days  = date('d');
            $month = date('m');
            $year  = date('Y');

            $param = $this->input->get('value', TRUE);

            $tanggal_sekarang = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d');
            $tanggal_sebelum  = date('Y-m-d', strtotime('-7 days', strtotime($tanggal_sekarang)));

            if ($param) {
                if ($param == 'today') {
                    $label = $days.' '.conv_month($month).' '.$year;
                    $where = "AND DATE(date_create) = CURDATE() ";
                } 
                else if ($param == 'last7') {
                    $label = '7 Hari Terakhir';

                    $where = "AND DATE(date_create) BETWEEN DATE('".$tanggal_sebelum."') AND DATE('".$tanggal_sekarang."') ";
                    // $where = "AND DATE(date_create) BETWEEN CURDATE()-6 AND CURDATE() ";
                }
                else if ($param == 'month') {
                    $label = conv_month($month).' '.$year;
                    $where = "AND MONTH(date_create) = '".$month."' AND YEAR(date_create) = '".$year."' ";
                } 
                else if ($param == 'year') {
                    $label = $year;
                    $where = "AND YEAR(date_create) = '".$year."' ";
                } 
                else {
                    $label = "";
                    $where = "";
                }
            }


            $get_user           = $this->db->query("SELECT COUNT(id_user) as total FROM tb_user WHERE id_user != 0 ".$where." ")->row_array();
            $get_user_ideasi    = $this->db->query("SELECT COUNT(id_user) as total FROM tb_user WHERE kategori_user = 'Ideasi' ".$where." ")->row_array();
            $get_user_mvp       = $this->db->query("SELECT COUNT(id_user) as total FROM tb_user WHERE kategori_user = 'MVP' ".$where." ")->row_array();

            $response['total_user']	            = number_format($get_user['total'], 0,',','.');
            $response['total_user_label']	    = $label;
            $response['total_user_ideasi']      = number_format($get_user_ideasi['total'], 0,',','.');
            $response['total_user_mvp']         = number_format($get_user_mvp['total'], 0,',','.');
        }

        json_response($response);
	}

    function load_data_kategori()
    {   
        $this->main_model->check_access('dashboard');
        $cact = $this->main_model->check_access_action('dashboard');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 

            $array_labels   = array();
            $array_datasets = array();
            // $array_list     = array();

            $count_data = $this->db->query("
                SELECT COUNT(u.id_user) as total 
                FROM tb_user u  ")->row_array();

            $get_data = $this->db->query("
                SELECT IFNULL(u.kategori_user, '-') as nama, COUNT(u.id_user) as total
                FROM tb_user u  
                GROUP BY u.kategori_user 
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
            $response['legend_title'] = 'Total';
            $response['legend_value'] = $count_data['total'];
            // $response['data_list']    = $array_list;
            $response['status']       = 1;
        }
       
        json_response($response);
    }

    function load_data_channel()
    {   

        $this->main_model->check_access('dashboard');
        $cact = $this->main_model->check_access_action('dashboard');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {

            $array_labels   = array();
            $array_datasets = array();

            $count_data = $this->db->query("
                SELECT COUNT(u.id_user) as total 
                FROM tb_user u  ")->row_array();

            // Cek 2023
            $get_data_2023 = $this->db->query("
                SELECT COUNT(u.id_user) as total
                FROM tb_user u  
                WHERE u.channel LIKE '%2023%'
                ")->row_array();

            if ($get_data_2023) {
                array_push($array_labels, 'Alumni 2023');
                array_push($array_datasets, $get_data_2023['total']);
            } else {
                array_push($array_labels, 'Alumni 2023');
                array_push($array_datasets, 0);
            }

            // Cek 2024
            $get_data_2024 = $this->db->query("
                SELECT COUNT(u.id_user) as total
                FROM tb_user u  
                WHERE u.channel LIKE '%2024%'
                ")->row_array();

            if ($get_data_2024) {
                array_push($array_labels, 'Alumni 2024');
                array_push($array_datasets, $get_data_2024['total']);
            } else {
                array_push($array_labels, 'Alumni 2024');
                array_push($array_datasets, 0);
            }

            // Cek 2023, 2024
            $get_data_2024 = $this->db->query("
                SELECT COUNT(u.id_user) as total
                FROM tb_user u  
                WHERE u.channel LIKE '%2023, 2024%'
                ")->row_array();

            if ($get_data_2024) {
                array_push($array_labels, 'Alumni 2023, 2024');
                array_push($array_datasets, $get_data_2024['total']);
            } else {
                array_push($array_labels, 'Alumni 2023, 2024');
                array_push($array_datasets, 0);
            }
            

            $response['labels']       = $array_labels;
            $response['datasets']     = $array_datasets;
            $response['legend_title'] = 'Total';
            $response['legend_value'] = $count_data['total'];
            $response['status']       = 1;
        }
       
        json_response($response);
    }

    function load_data_tingkat_pendidikan()
    {   
        $this->main_model->check_access('dashboard');
        $cact = $this->main_model->check_access_action('dashboard');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 

            $array_labels   = array();
            $array_datasets = array();
            $array_list     = array();

            $get_data = $this->db->query("
                SELECT IFNULL(p.nama, '-') as nama, COUNT(u.pendidikan) as total
                FROM tb_user u 
                LEFT JOIN tb_master_pendidikan p ON u.pendidikan = p.id_pendidikan
                GROUP BY p.nama 
                ORDER BY total DESC ")->result_array();

            foreach ($get_data as $key) {
                array_push($array_labels, $key['nama']);
                array_push($array_datasets, $key['total']);

                $row['label'] = $key['nama'];
                $row['value'] = $key['total'];
                array_push($array_list, $row);
            }

            $response['labels']       = $array_labels;
            $response['datasets']     = $array_datasets;
            $response['data_list']    = $array_list;
            $response['status']	      = 1;
        }

        json_response($response);
    }


    function load_data_provinsi()
    {   
        $this->main_model->check_access('dashboard');
        $cact = $this->main_model->check_access_action('dashboard');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {

            $array_labels   = array();
            $array_datasets = array();
            $array_list     = array();

            $count_data = $this->db->query("
                SELECT COUNT(u.id_user) as total 
                FROM tb_user u
                LEFT JOIN tb_master_province j ON u.provinsi = j.id  ")->row_array();

            $get_data = $this->db->query("
                SELECT IFNULL(j.name, '-') as nama, COUNT(u.id_user) as total
                FROM tb_user u 
                LEFT JOIN tb_master_province j ON u.provinsi = j.id 
                GROUP BY j.name 
                ORDER BY total DESC ")->result_array();

            foreach ($get_data as $key) {
                array_push($array_labels, $key['nama']);
                array_push($array_datasets, $key['total']);

                $row['label'] = $key['nama'];
                $row['value'] = $key['total'];
                array_push($array_list, $row);
            }

            $response['labels']       = $array_labels;
            $response['datasets']     = $array_datasets;
            $response['legend_title'] = 'Total';
            $response['legend_value'] = $count_data['total'];
            $response['data_list']    = $array_list;
            $response['status']       = 1;
        }
       
        json_response($response);
    }

    function load_data_dapat_informasi()
    {   
        $this->main_model->check_access('dashboard');
        $cact = $this->main_model->check_access_action('dashboard');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 

            $array_labels   = array();
            $array_datasets = array();
            $array_list     = array();


            $get_data = $this->db->query("
                SELECT IFNULL(j.nama, '-') as nama, COUNT(u.id_user) as total
                FROM tb_user u 
                LEFT JOIN tb_master_dapat_informasi j ON u.dapat_informasi = j.id_informasi 
                GROUP BY j.nama 
                ORDER BY total DESC ")->result_array();

            foreach ($get_data as $key) {
                array_push($array_labels, $key['nama']);
                array_push($array_datasets, $key['total']);

                $row['label'] = $key['nama'];
                $row['value'] = $key['total'];
                array_push($array_list, $row);
            }

            $response['labels']       = $array_labels;
            $response['datasets']     = $array_datasets;
            $response['data_list']    = $array_list;
            $response['status']       = 1;
        }
       
        json_response($response);
    }

    
    function get_address()
    {
        $id    = $this->input->post('id');
        $param = $this->input->post('param');

        $response = array();

        if ($param == 'provinsi') {
            $kab = $this->db->query("SELECT * FROM tb_master_regencies WHERE province_id = ".$id." ORDER BY name ASC")->result_array();

            $data = '';
            foreach ($kab as $key_kab) {
                $data .= '<option value="'.$key_kab['id'].'">'.$key_kab['name'].'</option>';
            }
        } 
        else if ($param == 'kabupaten') {
            $kec = $this->db->query("SELECT * FROM tb_master_district WHERE regency_id = ".$id." ORDER BY name ASC")->result_array();

            $data = '';
            foreach ($kec as $key_kac) {
                $data .= '<option value="'.$key_kac['id'].'">'.$key_kac['name'].'</option>';
            }
        }
        
        $response = array(
            'data'  => $data,
            'param' => $param
        );

        json_response($response);
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



