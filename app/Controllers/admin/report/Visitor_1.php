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

        $data = $this->main_model->check_access('report');
        
        $date  = date('Y-m-d');
        $hours = date('H');
        $days  = date('d');
        $month = date('m');
        $year  = date('Y');

        $get_page_view = $this->db->query("SELECT page_view FROM tb_analytic_visitors_2025 GROUP BY page_view")->result();

        // ----------------------------------- visitor by today -----------------------------------
            $fortgl[] = date('Y-m-d');
            $fortgl[] = date('Y-m-d', strtotime('-1 days', strtotime($date)));
            $fortgl[] = date('Y-m-d', strtotime('-2 days', strtotime($date)));
            $fortgl[] = date('Y-m-d', strtotime('-3 days', strtotime($date)));
            $fortgl[] = date('Y-m-d', strtotime('-4 days', strtotime($date)));
            $fortgl[] = date('Y-m-d', strtotime('-5 days', strtotime($date)));
            $fortgl[] = date('Y-m-d', strtotime('-6 days', strtotime($date)));

            $data['array_last7'] = $fortgl; 

            $colLast7 = "[";
                foreach ($get_page_view as $lpview) {
                    $colLast7 .= "{";
                        $kerja = array();

                        foreach ($fortgl as $key => $val) {
                            $get = $this->db->query("SELECT SUM(hits) as tot_hits FROM tb_analytic_visitors_2025 WHERE date='".$val."' AND page_view='".$lpview->page_view."'")->row();
                            if($get) { $push1 = (float)$get->tot_hits; } else { $push1 = 0; }

                            array_push($kerja, $push1);
                        }

                        $colLast7 .= "name: '".$lpview->page_view."',";
                        $colLast7 .= "data: ".json_encode($kerja)."";
                    $colLast7 .= "},";
                }
            $colLast7 .= "]";

            $data['series_visit_by_last7'] = $colLast7;

            $data['table_last7'] = $this->db->query("SELECT * FROM tb_analytic_visitors_2025 GROUP BY date ORDER BY id DESC LIMIT 7")->result_array();
        // ----------------------------------- end visitor by today -----------------------------------


        // ----------------------------------- visitor by day -----------------------------------
            $data['array_day'] = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];

            $colD = "[";
                foreach ($get_page_view as $lpview) {
                    $colD .= "{";
                        $visit_by_day = array();
                        foreach ($data['array_day'] as $key => $val_tgl) {
                            $day_hits = $this->db->query("
                                SELECT sum(hits) AS tot_hits_day 
                                FROM tb_analytic_visitors_2025 
                                WHERE DAY(date)='".$val_tgl."' AND MONTH(date)='".$month."' AND YEAR(date)='".$year."' AND page_view='".$lpview->page_view."'
                                GROUP BY MONTH(date) 
                            ")->row();

                            if($day_hits) { $push = (float)$day_hits->tot_hits_day; } else { $push = 0; }
                            
                            array_push($visit_by_day, $push);
                        }

                        $colD .= "name: '".$lpview->page_view."',";
                        $colD .= "data: ".json_encode($visit_by_day)."";
                    $colD .= "},";
                }
            $colD .= "]";

            $data['series_visit_by_day'] = $colD;
        // ----------------------------------- end visitor by day -----------------------------------


        // ----------------------------------- visitor by month -----------------------------------
            $data['category_month'] = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $array_month = ['01','02','03','04','05','06','07','08','09','10','11','12'];

            $colM = "[";
                foreach ($get_page_view as $lpview) {
                    $colM .= "{";
                        $visit_by_month = array();
                        foreach ($array_month as $key => $val_bln) {
                            $month_hits = $this->db->query("
                                SELECT sum(hits) AS tot_hits_month 
                                FROM tb_analytic_visitors_2025 
                                WHERE MONTH(date)='".$val_bln."' AND YEAR(date)='".$year."' AND page_view='".$lpview->page_view."'
                                GROUP BY MONTH(date) 
                            ")->row();

                            if($month_hits) { $push = (float)$month_hits->tot_hits_month; } else { $push = 0; }
                            
                            array_push($visit_by_month, $push);
                        }

                        $colM .= "name: '".$lpview->page_view."',";
                        $colM .= "data: ".json_encode($visit_by_month)."";
                    $colM .= "},";
                }
            $colM .= "]";

            $data['series_visit_by_month'] =  $colM;
        // ----------------------------------- end visitor by month -----------------------------------

        $data['group_page_view'] = $get_page_view;
        
        $data['title']       = 'Data Visitor';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']           = 'admin/report/visitor';
        $this->load->view('admin/index', $data);
    }

    function last7_datatable()
    {
        $month = date('m');
        $year  = date('Y');

        $valid_columns = array(
            1 => 'id',
        );

        $this->main_model->datatable($valid_columns);

        $filter_page = trim($this->input->get('filter_page'));

        $query = $this->db->query("SELECT 
            id,
            date,
            page_view,
            COUNT(ip_address) as tot_Visit_day, 
            SUM(hits) as tot_hits_day  
            FROM tb_analytic_visitors_2025 
            WHERE page_view='".$filter_page."' 
            AND MONTH(date) = '".$month."' 
            AND YEAR(date) = '".$year."'
            GROUP BY DAY(date) 
            ORDER BY id DESC
            LIMIT 7
        ");

        $no = $_POST['start'];
        $data = array();
        foreach($query->result_array() as $row) {
            $no++;

            $data[] = array(
                $no,
                date('d-m-Y', strtotime($row['date'])),
                $row['page_view'],
                $row['tot_Visit_day'],
                $row['tot_hits_day']
            );
        }

        $response = array(
            "draw"            => intval($this->input->post("draw")),
            "recordsTotal"    => $query->num_rows(),
            "recordsFiltered" => $query->num_rows(),
            "data"            => $data
        );

        json_response($response);
    }


    function by_day_datatable()
    {
        $month = date('m');
        $year  = date('Y');

        $valid_columns = array(
            1 => 'id',
        );

        $this->main_model->datatable($valid_columns);

        $filter_page = trim($this->input->get('filter_page'));

        $query = $this->db->query("SELECT 
            id,
            date,
            page_view,
            COUNT(ip_address) as tot_Visit_day, 
            SUM(hits) as tot_hits_day  
            FROM tb_analytic_visitors_2025 
            WHERE page_view='".$filter_page."' 
            AND MONTH(date) = '".$month."' 
            AND YEAR(date) = '".$year."'
            GROUP BY DAY(date) 
            ORDER BY id DESC
        ");

        $no = $_POST['start'];
        $data = array();
        foreach($query->result_array() as $row) {
            $no++;

            $data[] = array(
                $no,
                date('d-m-Y', strtotime($row['date'])),
                $row['page_view'],
                $row['tot_Visit_day'],
                $row['tot_hits_day']
            );
        }

        $response = array(
            "draw"            => intval($this->input->post("draw")),
            "recordsTotal"    => $query->num_rows(),
            "recordsFiltered" => $query->num_rows(),
            "data"            => $data
        );

        json_response($response);
    }


    function by_month_datatable()
    {
        $year  = date('Y');

        $valid_columns = array(
            1 => 'id',
        );

        $this->main_model->datatable($valid_columns);

        $filter_page = trim($this->input->get('filter_page'));

        $query = $this->db->query("SELECT 
            id,
            date,
            page_view,
            COUNT(ip_address) as tot_Visit_month, 
            SUM(hits) as tot_hits_month  
            FROM tb_analytic_visitors_2025 
            WHERE page_view='".$filter_page."' 
            AND YEAR(date) = '".$year."' 
            GROUP BY MONTH(date) 
            ORDER BY id DESC
        ");

        $no = $_POST['start'];
        $data = array();
        foreach($query->result_array() as $row) {
            $no++;

            $data[] = array(
                $no,
                date('F Y', strtotime($row['date'])),
                $row['page_view'],
                $row['tot_Visit_month'],
                $row['tot_hits_month']
            );
        }

        $response = array(
            "draw"            => intval($this->input->post("draw")),
            "recordsTotal"    => $query->num_rows(),
            "recordsFiltered" => $query->num_rows(),
            "data"            => $data
        );

        json_response($response);
    }


    function _sql_visitor() 
    {
        $fil_date = trim($this->input->get('fil_date'));

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
        
        $this->db->select('id, date, page_view, COUNT(ip_address) as tot_visit, SUM(hits) as tot_hits');
        $this->db->from('tb_analytic_visitors_2025');
        $this->db->where('date BETWEEN "'.$tgl_start_query .'" AND "'.$tgl_end_query.'" ');

        $this->db->group_by('date');

        return $this->db->get();
    }

    function datatables_visitor()
    {
        $valid_columns = array(
            0 => 'id',
            1 => 'date',
            2 => 'page_view',
            3 => 'tot_visit',
            4 => 'tot_hits',
        );

        $this->main_model->datatable($valid_columns);

        $query = $this->_sql_visitor();

        $no   = $this->input->post('start');
        $data = array();

        foreach($query->result_array() as $key) {
            $no++;

            $data[] = array(
                $no,
                date('d F Y', strtotime($key['date'])),
                $key['page_view'],
                number_format($key['tot_visit'], 0,',','.'),
                number_format($key['tot_hits'], 0,',','.')
            );
        }

        $response = array(
            'draw'            => intval($this->input->post('draw')),
            'recordsTotal'    => $this->_sql_visitor()->num_rows(),
            'recordsFiltered' => $this->_sql_visitor()->num_rows(),
            'data'            => $data
        );

        json_response($response);
    }

}