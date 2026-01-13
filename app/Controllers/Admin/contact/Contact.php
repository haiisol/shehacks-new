<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    public function Index()
    {
        $data = $this->main_model->check_access('contact');
        
        $data['title']       = 'Data Contact';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']           = 'admin/contact/contact';
        $this->load->view('admin/index', $data);
    }

    function _sql() 
    {   
        $fil_date = trim($this->input->get('fil_date') ?? '');

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

        $this->db->select('c.*');
        $this->db->from('tb_message c');
        $this->db->where('c.status', 0);

        if ($fil_date) {
            $this->db->where('c.date BETWEEN "'.$tgl_start_query .'" AND "'.$tgl_end_query.'" ');
        }
        
        return $this->db->get();
    }

    function datatables()
    {   
        $this->main_model->check_access('contact');

        $valid_columns = array(
            1 => 'c.id',
            2 => 'c.name',
            3 => 'c.email',
            4 => 'c.subject',
            5 => 'c.date',
        );

        $this->main_model->datatable($valid_columns);

        $query = $this->_sql();

        $no   = $this->input->get('start');
        $data = array();

        foreach($query->result_array() as $key) {
            $no++;

            $cact = $this->main_model->check_access_action('contact');


            // date update
            if ($key['date'] == '0000-00-00 00:00:00') {
                $tanggal = '-';
            } else {
                $tanggal = time_ago_from_3($key['date']);
            }

            $data[] = array(
                '<label class="checkbox-custome"><input type="checkbox" name="check-record" value="'.$key['id'].'" class="check-record"></label>',
                $no,
                '<a href="javascript:void(0)" class="detail-data" data="'.$key['id'].'">'.character_limiter($key['name'], 25).'</a>',
                character_limiter(strip_tags($key['email']), 25),
                character_limiter(strip_tags($key['subject']), 40),
                $tanggal,
                '<div class="dropdown dropdown-action '.$cact['access_action'].' options ms-auto text-center">
                    <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" id="dropdown-action">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end" aria-labelledby="dropdown-action">
                     
                        <a href="javascript:void(0)" class="dropdown-item detail-data" id="delete-data" data="'.$key['id'].'""><ion-icon name="eye-outline"></ion-icon> Detail</a>
                    </div>
                </div>'
            );
        }

        $response['draw']            = intval($this->input->get('draw'));
        $response['recordsTotal']    = $this->_sql()->num_rows();
        $response['recordsFiltered'] = $this->_sql()->num_rows();
        $response['data']            = $data;

        json_response($response);
    }


    function detail_data()
    {   
        $this->main_model->check_access('contact');
        $cact = $this->main_model->check_access_action('contact');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 

            $this->load->library('form_validation');
            $row = array(
                "id"     => $this->input->post('id', TRUE),
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $id = $this->input->post('id');

            $query = $this->db->select('*')
                    ->from('tb_message')
                    ->where('id', $id)
                    ->get()
                    ->row_array();

            $response['id']         = $query['id'];
            $response['name']       = $query['name'];
            $response['email']      = $query['email'];
            $response['phone']      = $query['phone'];
            $response['subject']    = $query['subject'];
            $response['message']    = $query['message'];
            $response['date']       = date('d-m-Y h:i', strtotime($query['date']));
        }

        json_response($response);
    }

    function export()
    {   
        $this->main_model->check_access('contact');
        $cact = $this->main_model->check_access_action('contact');

        if ($cact['access_view'] == 'd-none') {
            redirect('404');
        } else { 

            $this->load->library('table');
            
            $query = $this->_sql();

            $output = '';

            $output .= 
                '<table class="table" border="1">
                    <thead>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Tanggal</th>
                    </thead>
                    <tbody>';
    
                    $no = 1;
                    foreach($query->result_array() as $key) {
                        $no++;
                        
                        $date = date('d-m-Y h:i', strtotime($key['date']));
                        $output .= '
                            <tr>
                                <td>'.$no.'</td>
                                <td>'.$key['name'].'</td>
                                <td>'.$key['email'].'</td>
                                <td>'.$key['phone'].'</td>
                                <td>'.$key['subject'].'</td>
                                <td>'.$key['message'].'</td>
                                <td>'.$date.'</td>
                            </tr>';
                    }

            $output .= 
                    '</tbody>
                </table>';

            $filename = "Expoer-Data-Contact-".date('Y-m-d-h-i-s');

            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=".$filename.".xls");
            header("Cache-Control: max-age=0");
            
            echo $output;

        }

        
    }

}

