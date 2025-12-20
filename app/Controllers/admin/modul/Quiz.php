<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Quiz extends CI_Controller {

    private $numbering_row = 0;

    public function __construct() {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    public function Index()
    {

        $data = $this->main_model->check_access('modul');

        $get_modul = $this->db->query("
            SELECT m.*, m.kategori as kategori 
            FROM edu_modul m 
            WHERE m.status_quiz = 1 
            AND m.status_delete = 0 
            ORDER BY m.modul ASC ")->result_array();
            
        // AND m.user_post = '".$this->id_admin."' 
        
        $data['modul'] = $get_modul;

        $data['title']       = 'Data Quiz';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']           = 'admin/modul/quiz';
        $this->load->view('admin/index', $data);
    }

    function _sql() 
    {
        $fil_kategori = trim($this->input->get('fil_kategori') ?? '');
        $fil_modul    = trim($this->input->get('fil_modul') ?? '');

        $this->db->select('
            q.id_quiz,
            q.id_modul,
            q.pertanyaan,
            q.answer,
            m.modul, 
            m.kategori as kategori,
        ');
        $this->db->from('quiz q');
        $this->db->join('edu_modul m', 'm.id_modul = q.id_modul', 'left');
        $this->db->where('q.status_delete', 0);

        if ($fil_kategori) {
            $this->db->where('m.kategori', $fil_kategori);
        }

        if ($fil_modul) {
            $this->db->where('q.id_modul', $fil_modul);
        }
        
        $this->db->order_by('q.id_quiz DESC');

        return $this->db->get();
    }

    function datatables()
    {   
        $this->main_model->check_access('modul');
        
        $valid_columns = array(
            1 => 'q.id_quiz',
            3 => 'm.modul',
            4 => 'q.pertanyaan',
            5 => 'q.answer',
        );

        $this->main_model->datatable($valid_columns);

        $query = $this->_sql();

        $no   = $this->input->get('start');
        $data = array();

        foreach($query->result_array() as $key) {
            $no++;

            $cact = $this->main_model->check_access_action('modul');

            if ($key['modul']) {
                $modul = character_limiter($key['modul'], 20);
            } else {
                $modul = '-';
            }

            if ($key['pertanyaan']) {
                $pertanyaan = character_limiter($key['pertanyaan'], 20);
            } else {
                $pertanyaan = '-';
            }

            if ($key['kategori'] == 'Ideasi') {
                $kategori = '<span class="badge bg-danger">'.$key['kategori'].'</span> ';
            } else {
                $kategori = '<span class="badge bg-success">'.$key['kategori'].'</span>';
            }

            $get_jawaban = $this->db->query("SELECT jawaban FROM quiz_jawaban WHERE id_jawaban = ".$key['answer']." ")->row_array();

            $data[] = array(
                '<label class="checkbox-custome"><input type="checkbox" name="check-record" value="'.$key['id_quiz'].'" class="check-record"></label>',
                $no,
                $kategori,
                $modul,
                $pertanyaan,
                character_limiter($get_jawaban['jawaban'], 15),
                '<div class="dropdown dropdown-action '.$cact['access_action'].' options ms-auto text-center">
                     <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" id="dropdown-action">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                        <a href="#" class="dropdown-item" id="edit-data" data="'.$key['id_quiz'].'">
                            <ion-icon name="create-sharp"></ion-icon>Edit</a>
                        <a href="#" class="dropdown-item '.$cact['access_delete'].'" id="delete-data" data="'.$key['id_quiz'].'">
                            <ion-icon name="trash-sharp"></ion-icon>Delete</a>
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


    function add_data()
    {   

        $this->main_model->check_access('modul');
        $cact = $this->main_model->check_access_action('modul');

        if ($cact['access_add'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 

            $optionjawaban      = sanitize_input($this->input->post('optionjawaban'));

            $data['id_modul']   = sanitize_input($this->input->post('modul'));
            $data['pertanyaan'] = sanitize_input($this->input->post('pertanyaan'));
            $data['status']     = 1;
            $data['user_post']  = $this->id_admin;

            $this->db->insert('quiz', $data);
            $id_quiz = $this->db->insert_id();

            // option a
            $datapilihan_a['id_quiz'] = $id_quiz;
            $datapilihan_a['jawaban'] = sanitize_input($this->input->post('pilihan_a'));
            $get_id_a  = $this->db->insert('quiz_jawaban', $datapilihan_a);
            $last_id_a = $this->db->insert_id($get_id_a);

            // option b
            $datapilihan_b['id_quiz'] = $id_quiz;
            $datapilihan_b['jawaban'] = sanitize_input($this->input->post('pilihan_b'));
            $get_id_b  = $this->db->insert('quiz_jawaban', $datapilihan_b);
            $last_id_b = $this->db->insert_id($get_id_b);

            // option c
            $datapilihan_c['id_quiz'] = $id_quiz;
            $datapilihan_c['jawaban'] = sanitize_input($this->input->post('pilihan_c'));
            $get_id_c  = $this->db->insert('quiz_jawaban', $datapilihan_c);
            $last_id_c = $this->db->insert_id($get_id_c);

            // option d
            $datapilihan_d['id_quiz'] = $id_quiz;
            $datapilihan_d['jawaban'] = sanitize_input($this->input->post('pilihan_d'));
            $get_id_d  = $this->db->insert('quiz_jawaban', $datapilihan_d);
            $last_id_d = $this->db->insert_id($get_id_d);


            // update quiz jawaban benar
            if ($optionjawaban == 'a') {
                $data_jb['answer'] = $last_id_a;
            } elseif ($optionjawaban == 'b') {
                $data_jb['answer'] = $last_id_b;
            } elseif ($optionjawaban == 'c') {
                $data_jb['answer'] = $last_id_c;
            } else {
                $data_jb['answer'] = $last_id_d;
            }

            $data_jb['pilihan_ganda'] = $optionjawaban;
            $query = $this->main_model->update_data('quiz', $data_jb, 'id_quiz', $id_quiz);

            if($query) {
                $response['status']  = 1;
                $response['message'] = 'Data berhasil ditambahkan.';
            } else {
                $response['status']  = 2;
                $response['message'] = 'Gagal menambah data.';
            }
        }
        json_response($response);
    }


    function get_data()
    {   

        $this->main_model->check_access('modul');
        $cact = $this->main_model->check_access_action('modul');

        if ($cact['access_view'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else { 

            $id     = $this->input->post('id', TRUE);

            $this->load->library('form_validation');
            $row = array(
                "id"     => $id,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }
            $query = $this->db->select('*')
                    ->from('quiz')
                    ->where('id_quiz', $id)
                    ->get()
                    ->row();

            $a = $this->db->select('*')
                    ->from('quiz_jawaban')
                    ->where('id_quiz', $id)
                    ->limit(1)
                    ->get()
                    ->row();
            
            $b = $this->db->select('*')
                ->from('quiz_jawaban')
                ->where('id_quiz', $id)
                ->limit(1, 1)
                ->get()
                ->row();
            
            $c = $this->db->select('*')
                ->from('quiz_jawaban')
                ->where('id_quiz', $id)
                ->limit(1, 2)
                ->get()
                ->row();

            $d = $this->db->select('*')
                ->from('quiz_jawaban')
                ->where('id_quiz', $id)
                ->limit(1, 3)
                ->get()
                ->row();
            
            $data['id']            = $query->id_quiz;
            $data['pertanyaan']    = $query->pertanyaan;
            $data['id_modul']      = $query->id_modul;
            $data['pilihan_ganda'] = $query->pilihan_ganda;
            
            $data['id_a']      = $a->id_jawaban;
            $data['jawaban_a'] = $a->jawaban;
        
            $data['id_b']      = $b->id_jawaban;
            $data['jawaban_b'] = $b->jawaban;
        
            $data['id_c']      = $c->id_jawaban;
            $data['jawaban_c'] = $c->jawaban;
            
            $data['id_d']      = $d->id_jawaban;
            $data['jawaban_d'] = $d->jawaban;
        
        }

        json_response($data);
    }

    function edit_data()
    {   
        $this->main_model->check_access('modul');
        $cact = $this->main_model->check_access_action('modul');

        if ($cact['access_edit'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
        } else {  

            $id_quiz       = $this->input->post('id', TRUE);

            $this->load->library('form_validation');
            $row = array(
                "id"   => $id_quiz,
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('id_quiz', 'id_quiz', 'trim|required|numeric');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            $optionjawaban = $this->input->post('optionjawaban', TRUE);
            $id_modul      = $this->input->post('modul', TRUE);
            $id_a          = $this->input->post('id_a', TRUE);
            $id_b          = $this->input->post('id_b', TRUE);
            $id_c          = $this->input->post('id_c', TRUE);
            $id_d          = $this->input->post('id_d', TRUE);

            $data['id_modul']   = $id_modul;
            $data['pertanyaan'] = $this->input->post('pertanyaan', TRUE);
            $this->main_model->update_data('quiz', $data, 'id_quiz', $id_quiz);

            // option a
            $datapilihan_a['jawaban'] = $this->input->post('pilihan_a', TRUE);
            $this->main_model->update_data('quiz_jawaban', $datapilihan_a, 'id_jawaban', $id_a);

            // option b
            $datapilihan_b['jawaban'] = $this->input->post('pilihan_b', TRUE);
            $this->main_model->update_data('quiz_jawaban', $datapilihan_b, 'id_jawaban', $id_b);

            // option c
            $datapilihan_c['jawaban'] = $this->input->post('pilihan_c', TRUE);
            $this->main_model->update_data('quiz_jawaban', $datapilihan_c, 'id_jawaban', $id_c);

            // option d
            $datapilihan_d['jawaban'] = $this->input->post('pilihan_d', TRUE);
            $this->main_model->update_data('quiz_jawaban', $datapilihan_d, 'id_jawaban', $id_d);


            // update quiz jawaban benar
            if ($optionjawaban == 'a') {
                $data_jb['answer'] = $id_a;
            } elseif ($optionjawaban == 'b') {
                $data_jb['answer'] = $id_b;
            } elseif ($optionjawaban == 'c') {
                $data_jb['answer'] = $id_c;
            } else {
                $data_jb['answer'] = $id_d;
            }

            $data_jb['pilihan_ganda'] = $optionjawaban;
            $data_jb['id_modul']      = $id_modul;
            $query = $this->main_model->update_data('quiz', $data_jb, 'id_quiz', $id_quiz);

            if($query) {
                $response['status']  = 3;
                $response['message'] = 'Data berhasil Disimpan.';
            } else {
                $response['status']  = 4;
                $response['message'] = 'Gagal menyimpan data.';
            }
        }

        json_response($response);
    }


    function delete_data()
    {   
        $this->main_model->check_access('modul');
        $cact = $this->main_model->check_access_action('modul');

        if ($cact['access_delete'] == 'd-none') {
            $response['status']  = 0;
            $response['message'] = 'Gagal, tidak memiliki akses.';
            json_response($response);
        } else {  

            $method = $this->input->post('method', TRUE);

            $this->load->library('form_validation');
            $row = array(
                "method"        => $method,
                "id"            => $this->input->post('id', TRUE),
            ); 

            $this->form_validation->set_data($row);
            $this->form_validation->set_rules('method', 'method', 'trim|required|alpha_numeric');
            $this->form_validation->set_rules('id', 'id', 'trim|required|callback_valid_angka_koma');

            if ( $this->form_validation->run() === false ) {
                $response['status']  = 0;
                $response['message'] = validation_errors();
                json_response($response);
                return;
            }

            if($method == 'single')
            {
                $id = $this->input->post('id');

                $data_delete['status_delete']  = 1 ;
                $this->main_model->update_data('quiz_jawaban', $data_delete, 'id_quiz', $id);
                $query = $this->main_model->update_data('quiz', $data_delete, 'id_quiz', $id);


                if($query) {
                    $response = 1;
                }
                json_response($response);
            }
            else
            {
                $json = $this->input->post('id');
                $id = array();

                if (strlen($json) > 0) {
                    $id = json_decode($json);
                }

                if (count($id) > 0) {
                    $id_str = "";
                    $id_str = implode(',', $id);

                    $this->db->query("UPDATE quiz_jawaban SET status_delete = 1 WHERE id_quiz in (".$id_str.")");
                    $query = $this->db->query("UPDATE quiz SET status_delete = 1 WHERE id_quiz in (".$id_str.")");

                    if($query) {
                        $response = 2;
                    }

                    
                    json_response($response);
                }
            }
        }
    }



    public function export()
    {
        $this->load->library('table');

        $output = '';

        $output .= 
            '<table class="table" border="1">
                <thead>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Modul</th>
                    <th>Pertanyaan</th>
                    <th>Jawaban Benar</th>
                </thead>
            <tbody>';

        $query = $this->_sql();

        $no = 1;
        foreach ($query->result_array() as $row) {
            $no++;

            $jaw = $this->db->query("SELECT jawaban FROM quiz_jawaban where id_jawaban = '".$row['answer']."'")->row();
            $jawaban_benar = $jaw->jawaban;

            $output .= '
                                <tr>
                                    <td>'.$no.'</td>
                                    <td>'.$row['kategori'].'</td>
                                    <td>'.$row['modul'].'</td>
                                    <td>'.$row['pertanyaan'].'</td>
                                    <td>'.$jawaban_benar.'</td>
                                </tr>';
        }

        $output .= 
                '</tbody>
            </table>';

        $filename = "Data-pertanyaan-".date('Y-m-d-h-i-s');

        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=".$filename.".xls");
        header("Cache-Control: max-age=0");

        echo $output;
    }

    public function valid_huruf_angka_spasi($str) {
        if (preg_match('/^[a-zA-Z0-9 ]+$/', $str)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('valid_huruf_angka_spasi', 'Kolom {field} hanya boleh berisi huruf, angka, dan spasi.');
            return FALSE;
        }
    }

    public function valid_angka_koma($str) {

        if (is_numeric($str)) {
            return TRUE;
        }
    
        if (preg_match('/^\["[0-9]+"(,"[0-9]+")*\]$/', $str)) {
            return TRUE;
        }
    
        $this->form_validation->set_message('valid_angka_koma', 'Kolom {field} hanya boleh berisi angka atau array angka dengan koma.');
        return FALSE;
    }

}
