<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Hasil extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    public function Index()
    {
        $data = $this->main_model->check_access('voting');
        
        $data['title']       = 'Data Hasil Voting';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']           = 'admin/voting/hasil';
        $this->load->view('admin/index', $data);
    }


    function _sql() 
    {
        $fil_kategori = trim($this->input->get('fil_kategori', TRUE) ?? '');

        $this->db->select('m.id_voting, m.nama_founders, m.nama_usaha, m.bidang_usaha, m.kategori, m.logo , m.total_voting, m.description ');
        $this->db->from('tb_voting m');
        $this->db->where('m.status_delete', 0);

        if ($fil_kategori) {
            $this->db->where('m.kategori', $fil_kategori);
        }

        $this->db->order_by('m.total_voting DESC');

        return $this->db->get();
    }

    function datatables()
    {     
        $this->main_model->check_access('voting');

        $this->load->library('form_validation');
        $row = array(
            "fil_kategori"     => $this->input->get('fil_kategori', TRUE),
        ); 

        $this->form_validation->set_data($row);
        $this->form_validation->set_rules('fil_kategori', 'fil_kategori', 'trim|alpha_numeric');

        if ( $this->form_validation->run() === false ) {
            $response['status']  = 0;
            $response['message'] = validation_errors();
            json_response($response);
            return;
        }

        $valid_columns = array(
            1 => 'm.id_voting',
            4 => 'm.nama_founders',
            5 => 'm.nama_usaha',
            6 => 'm.total_voting',
        );

        $this->main_model->datatable($valid_columns);

        $query = $this->_sql();

        $no   = $this->input->get('start');
        $data = array();

        foreach($query->result_array() as $key) {
            $no++;

            $cact = $this->main_model->check_access_action('voting');

            $url_image = $this->main_model->url_image($key['logo'], 'image-logo');

            if ($key['nama_founders']) {
                $nama_founders = character_limiter($key['nama_founders'], 30);
            } else {
                $nama_founders = '-';
            }

            if ($key['nama_usaha']) {
                $nama_usaha = character_limiter($key['nama_usaha'], 30);
            } else {
                $nama_usaha = '-';
            }

            if ($key['kategori'] == 'Ideasi') {
                $kategori = '<span class="badge bg-danger">'.$key['kategori'].'</span> ';
            } else {
                $kategori = '<span class="badge bg-success">'.$key['kategori'].'</span>';
            }

            $url_detail = base_url().'admin/voting/hasil/detail/'.encrypt_url($key['id_voting']);

            $data[] = array(
                '<label class="checkbox-custome"><input type="checkbox" name="check-record" value="'.$key['id_voting'].'" class="check-record"></label>',
                $no,
                '<img src="'.$url_image.'" class="img-fluid">',
                $kategori,
                $nama_founders,
                $nama_usaha,
                number_format($key['total_voting']),
                '<div class="dropdown dropdown-action '.$cact['access_action'].' options ms-auto text-center">
                     <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" id="dropdown-action">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                        <a href="'.$url_detail.'" class="dropdown-item">
                            <ion-icon name="create-sharp"></ion-icon>List Voting User
                        </a>
                    </div>
                </div>'
            );
        }

        $response['draw']            = intval($this->input->get("draw"));
        $response['recordsTotal']    = $this->_sql()->num_rows();
        $response['recordsFiltered'] = $this->_sql()->num_rows();
        $response['data']            = $data;

        json_response($response);
    }

    function export()
    {   
        $this->main_model->check_access('voting');
        $cact = $this->main_model->check_access_action('voting');

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
                        <th>Kategori</th>
                        <th>Nama Founders</th>
                        <th>Nama Usaha</th>
                        <th>Bidang Usaha</th>
                        <th>Deskripsi Usaha</th>
                        <th>Total Voting</th>
                    </thead>
                    <tbody>';
    
                    $no = 0;
                    foreach($query->result_array() as $key) {
                        $no++;

                        $output .= '
                            <tr>
                                <td>'.$no.'</td>
                                <td>'.$key['kategori'].'</td>
                                <td>'.$key['nama_founders'].'</td>
                                <td>'.$key['nama_usaha'].'</td>
                                <td>'.$key['bidang_usaha'].'</td>
                                <td>'.$key['description'].'</td>
                                <td>'.$key['total_voting'].'</td>
                            </tr>';
                    }


            $output .= 
                    '</tbody>
                </table>';

            $filename = "Export-Data-Voting-".date('Y-m-d-h-i-s');

            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=".$filename.".xls");
            header("Cache-Control: max-age=0");

            echo $output;
        }
    }


    public function Detail($id_enc)
    {
        $data = $this->main_model->check_access('voting');
        
        $data['id_voting_enc']      = $id_enc;
        $data['title']              = 'Data Voting';
        $data['description']        = '';
        $data['keywords']           = '';
        $data['page']               = 'admin/voting/detail';
        $this->load->view('admin/index', $data);
    }

    function _sql_detail($id_voting_enc) 
    {

        $id_voting = decrypt_url($id_voting_enc);

        $this->db->select('u.id_user, u.nama, u.telp, u.email');
        $this->db->from('tb_voting_user m');
        $this->db->join('tb_user u', 'm.id_user = u.id_user', 'left');
        $this->db->where('m.id_voting', $id_voting);

        return $this->db->get();
    }

    function datatables_detail($id_voting_enc)
    {   
        $this->main_model->check_access('voting');
        $cact = $this->main_model->check_access_action('voting');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';

        } else { 

            $valid_columns = array(
                0 => 'id_user',
                1 => 'nama',
                2 => 'telp',
                3 => 'email',
            );

            $this->main_model->datatable($valid_columns);

            $query = $this->_sql_detail($id_voting_enc);

            $no   = $this->input->get('start');
            $data = array();

            foreach($query->result_array() as $key) {
                $no++;

                $cact = $this->main_model->check_access_action('modul');

                $data[] = array(
                    $no,
                    $key['nama'],
                    $key['telp'],
                    $key['email']
                );
            }

            $response['draw']            = intval($this->input->get("draw"));
            $response['recordsTotal']    = $this->_sql_detail($id_voting_enc)->num_rows();
            $response['recordsFiltered'] = $this->_sql_detail($id_voting_enc)->num_rows();
            $response['data']            = $data;
        }

        json_response($response);
    }

    function export_detail($id_enc)
    {
        $this->load->library('table');
        
        $query = $this->_sql_detail($id_enc);

        $output = '';

        $output .= 
            '<table class="table" border="1">
                <thead>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Telp</th>
                    <th>Email</th>
                </thead>
                <tbody>';
 
                $no = 0;
                foreach($query->result_array() as $key) {
                    $no++;

                    $output .= '
                        <tr>
                            <td>'.$no.'</td>
                            <td>'.$key['nama'].'</td>
                            <td>'.$key['telp'].'</td>
                            <td>'.$key['email'].'</td>
                        </tr>';
                }


        $output .= 
                '</tbody>
            </table>';

        $filename = "Export-Data-Voting-User-".date('Y-m-d-h-i-s');

        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=".$filename.".xls");
        header("Cache-Control: max-age=0");

        echo $output;
    }


}

