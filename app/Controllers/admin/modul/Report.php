<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    public function Index()
    {
        $data = $this->main_model->check_access('data_user');
        
        $data['title']       = 'Progress User';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']           = 'admin/modul/report';
        $this->load->view('admin/index', $data);
    }

    function _sql() 
    {   
        $fil_date               = trim($this->input->get('fil_date') ?? '');
        $fil_provinsi           = trim($this->input->get('fil_provinsi') ?? '');
        $fil_pendidikan         = trim($this->input->get('fil_pendidikan') ?? '');
        $fil_kategori_user      = trim($this->input->get('fil_kategori_user') ?? '');
        $fil_umur               = trim($this->input->get('fil_umur') ?? '');
            
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
            u.*, COUNT(q.id_modul) as total_modul
        ');
        $this->db->from('tb_user u');
        $this->db->join('edu_modul_user_progress q','u.id_user = q.id_user','LEFT');
        $this->db->where('q.id_modul !=', 'NULL');

        if ($fil_date) {
            $this->db->where('u.date_create BETWEEN "'.$tgl_start_query .'" AND "'.$tgl_end_query.'" ');
        }

        if ($fil_provinsi) {
            $this->db->where('u.provinsi', $fil_provinsi);
        }

        if ($fil_pendidikan) {
            $this->db->where('u.pendidikan', $fil_pendidikan);
        }

        if ($fil_kategori_user) {
            $this->db->where('u.kategori_user', $fil_kategori_user);
        }

        if ($fil_umur) {
            if ($fil_umur == 1) {
                $this->db->where('u.umur BETWEEN "20" AND "30" ');
            } elseif($fil_umur == 2) {
                $this->db->where('u.umur BETWEEN "31" AND "40" ');
            } elseif($fil_umur == 3) {
                $this->db->where('u.umur BETWEEN "41" AND "50" ');
            } elseif($fil_umur == 4) {
                $this->db->where('u.umur >=', '51');
            } else {

            }
        }

        $this->db->group_by('q.id_user'); 

        return $this->db->get();
    }

    function datatables()
    {   
        $this->main_model->check_access('data_user');
        
        $valid_columns = array(
            0 => 'u.id_user',
            2 => 'u.nama',
            3 => 'u.email',
            4 => 'u.telp',
        );

        $this->main_model->datatable($valid_columns);

        $query = $this->_sql();

        $no   = $this->input->get('start');
        $data = array();

        foreach($query->result_array() as $key) {
            $no++;

            $cact = $this->main_model->check_access_action('data_user');

            // Kategori
            if ($key['kategori_user'] == 'Ideasi') {
                $kategori_user = '<span class="badge bg-danger">'.$key['kategori_user'].'</span> ';
            } else {
                $kategori_user = '<span class="badge bg-success">'.$key['kategori_user'].'</span>';
            }

            // terakhir login
            if ($key['date_create'] == "0000-00-00 00:00:00") {
                $date_create = '-';
            } else {
                $date_create = time_ago_from_3($key['date_create']);
            }

            $get_progress = $this->db->query("
                    SELECT COUNT(DISTINCT p.id_modul) as total 
                    FROM edu_modul_user_progress p USE INDEX (progress_user)
                    WHERE p.id_user = '".$key['id_user']."'
                     ")->row_array();

            if ($get_progress) {
                $total_modul = $get_progress['total'].' Modul';
            } else {    
                $total_modul = '0 Modul';
            }
            

            $url_detail = base_url().'admin/user/detail_user/detail_belajar/'.encrypt_url($key['id_user']);

            $data[] = array(
                $no,
                $key['channel'],
                $kategori_user,
                '<a href="'.$url_detail.'" target="_blank">'.character_limiter($key['nama'], 10).'</a>',
                character_limiter($key['email'], 20),
                $total_modul,
                $date_create,
                '<div class="dropdown dropdown-action '.$cact['access_action'].' options ms-auto text-center">
                    <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" id="dropdown-action">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end" aria-labelledby="dropdown-action">
                        <a href="'.$url_detail.'" target="_blank" class="dropdown-item"><ion-icon name="eye-sharp"></ion-icon> Detail</a>
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

    public function export()
    {   
        $this->load->library('table');
        $output = '';

        $output .= 
            '<table class="table" border="1">
                <thead>
                    <th>No</th>
                    <th>Channel Alumni</th>
                    <th>Kategori</th>
                    <th>Nama User</th>
                    <th>Email</th>
                    <th>Telp</th>
                    <th>Tanggal Lahir</th>
                    <th>Umur</th>
                    <th>Pendidikan Terakhir</th>
                    <th>Mengetahui Informasi</th>
                    <th>Nama Startup</th>
                    <th>Provinsi</th>
                    <th>Kabupaten</th>
                    <th>Jumlah Anggota Tim</th>
                    <th>Problem Disekitar</th>
                    <th>Solusi yang dibuat</th>
                    <th>Pitch deck</th>
                    <th>Tanggal Daftar</th>
                    <th>Tanggal Update</th>
                    <th>Total Modul</th>
                    <th>Progress Materi Modul</th>
                    <th>Progress Video Dilihat</th>
                    <th>Skor Quiz sebelum belajar</th>
                    <th>Tanggal Pengerjaan</th>
                    <th>Skor Quiz setelah belajar</th>
                    <th>Tanggal Pengerjaan</th>
                </thead>
                <tbody>';
        
       
        $query = $this->_sql();

        $no = 0;
        foreach ($query->result_array() as $key) {
            $no++;

            $style_foto = '';

            // date create
            if ($key['date_create'] == "0000-00-00 00:00:00") {
                $date_create = '-';
            } else {
                $date_create = date('d-m-Y h.i', strtotime($key['date_create']));
            }

            // date update
            if ($key['date_update'] == "0000-00-00 00:00:00") {
                $date_update = '-';
            } else {
                $date_update = date('d-m-Y h.i', strtotime($key['date_update']));
            }

            // Industri
            $get_dapat_informasi = $this->db->query("SELECT nama FROM tb_master_dapat_informasi WHERE id_informasi = '".$key['dapat_informasi']."' ")->row_array();
            if ($get_dapat_informasi) {
                $dapat_informasi = $get_dapat_informasi['nama'];
            } else {
                $dapat_informasi = '-';
            }

            // Provinsi
            $get_prov = $this->db->query("SELECT name FROM tb_master_province WHERE id = '".$key['provinsi']."' ")->row_array();
            if ($get_prov) {
                $provinsi = $get_prov['name'];
            } else {
                $provinsi = '-';
            }

            // Provinsi
            $get_kab = $this->db->query("SELECT name FROM tb_master_regencies WHERE id = '".$key['kabupaten']."' ")->row_array();
            if ($get_kab) {
                $kabupaten = $get_kab['name'];
            } else {
                $kabupaten = '-';
            }

            // Pendidikan
            $get_pendidikan = $this->db->query("SELECT nama FROM tb_master_pendidikan WHERE id_pendidikan = '".$key['pendidikan']."' ")->row_array();
            if ($get_pendidikan) {
                $pendidikan = $get_pendidikan['nama'];
            } else {
                $pendidikan = '-';
            }

            // pitch deck
            if ($key['file_pitchdeck']) {
                $file_pitchdeck = base_url().'file_media/file-user/'.$key['file_pitchdeck'];
            } else {
                $file_pitchdeck = '-';
            }
            
                        
            $progress_modul   = $this->db->query("SELECT p.id_modul, m.modul
                                        FROM edu_modul_user_progress p
                                        LEFT JOIN edu_modul m ON p.id_modul = m.id_modul
                                        WHERE p.id_user = '".$key['id_user']."'
                                        AND m.status_delete = 0")->result_array();
                
                if($progress_modul){
                    for ($i = 0; $i < count($progress_modul); $i++) {
                        
                        $his_video = $this->db->query("SELECT p.id_user, p.id_modul, v.judul
                                                                FROM edu_modul_progress p
                                                                LEFT JOIN edu_video v ON p.id_video = v.id_video
                                                                WHERE p.id_user = '".$key['id_user']."' 
                                                                AND p.id_modul = '".$progress_modul[$i]['id_modul']."'
                                                                AND p.id_video != 0
                                                                ORDER BY p.id ASC ")->result_array();
                        
                        $data_video = "";
                        foreach ($his_video as $keyv) {  
                            $data_video .= $keyv['judul'].'<br>';
                        }
                        
                        $sekor_pre   = $this->db->query("SELECT skor, tanggal
                                                                FROM quiz_skor_user
                                                                WHERE id_user = '".$key['id_user']."' 
                                                                AND id_modul = '".$progress_modul[$i]['id_modul']."'
                                                                AND jenis_quiz = 'PRE - TEST'
                                                                ORDER BY id_skor DESC LIMIT 1 ")->row_array();

                        $sekor_post   = $this->db->query("SELECT skor, tanggal
                                                                FROM quiz_skor_user
                                                                WHERE id_user = '".$key['id_user']."' 
                                                                AND id_modul = '".$progress_modul[$i]['id_modul']."'
                                                                AND jenis_quiz = 'POST - TEST'
                                                                ORDER BY id_skor DESC LIMIT 1 ")->row_array();
                           
                        if ($sekor_pre) {
                            $hasil_skor_pre = $sekor_pre['skor'];
                            if ($sekor_pre['tanggal'] != '0000-00-00 00:00:00') {
                                $tanggal_skor_pre =  date('d F Y H:i', strtotime($sekor_pre['tanggal']));
                            } else {
                                $tanggal_skor_pre = '';
                            }
                        } else {
                            $hasil_skor_pre = 0;
                            $tanggal_skor_pre = '';
                        }
                        
                        if ($sekor_post) {
                            $hasil_skor_post = $sekor_post['skor'];
                            if ($sekor_post['tanggal'] != '0000-00-00 00:00:00') {
                                $tanggal_skor_post =  date('d F Y H:i', strtotime($sekor_post['tanggal']));
                            } else {
                                $tanggal_skor_post = '';
                            }
                        } else {
                            $hasil_skor_post = 0;
                            $tanggal_skor_post = '';
                        }

                        $get_progress = $this->db->query("
                                SELECT COUNT(DISTINCT p.id_modul) as total 
                                FROM edu_modul_user_progress p USE INDEX (progress_user)
                                WHERE p.id_user = '".$key['id_user']."'
                                 ")->row_array();

                        if ($get_progress) {
                            $total_modul = $get_progress['total'];
                        } else {    
                            $total_modul = '0';
                        }
                        
                        if($i == 0){
                            $output .= '
                                <tr>
                                    <td>'.$no.'</td>
                                    <td>'.$key['channel'].'</td>
                                    <td>'.$key['kategori_user'].'</td>
                                    <td>'.$key['nama'].'</td>
                                    <td>'.$key['email'].'</td>
                                    <td>'."'62".$key['telp'].'</td>
                                    <td>'.$key['tanggal_lahir'].'</td>
                                    <td>'.$key['umur'].'</td>
                                    <td>'.$pendidikan.'</td>
                                    <td>'.$dapat_informasi.'</td>
                                    <td>'.$key['nama_startup'].'</td>
                                    <td>'.$provinsi.'</td>
                                    <td>'.$kabupaten.'</td>
                                    <td>'.$key['jumlah_anggota'].'</td>
                                    <td>'.$key['problem_disekitar'].'</td>
                                    <td>'.$key['solusi_yang_dibuat'].'</td>
                                    <td>'.$file_pitchdeck.'</td>
                                    <td>'.$date_create.'</td>
                                    <td>'.$date_update.'</td>
                                    <td>'.$total_modul.'</td>
                                    <td>'.$progress_modul[$i]['modul'].'</td>
                                    <td>'.$data_video.'</td>
                                    <td>'.$hasil_skor_pre.'</td>
                                    <td>'.$tanggal_skor_pre.'</td>
                                    <td>'.$hasil_skor_post.'</td>
                                    <td>'.$tanggal_skor_post.'</td>
                                </tr>';
                        } else {
                            $output .= '
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>'.$progress_modul[$i]['modul'].'</td>
                                    <td>'.$data_video.'</td>
                                    <td>'.$hasil_skor_pre.'</td>
                                    <td>'.$tanggal_skor_pre.'</td>
                                    <td>'.$hasil_skor_post.'</td>
                                    <td>'.$tanggal_skor_post.'</td>
                                </tr>';
                        }
                        
                    }
                } else {
                    $output .= '
                        <tr>
                            <td>'.$no.'</td>
                            <td>'.$key['channel'].'</td>
                            <td>'.$key['kategori_user'].'</td>
                            <td>'.$key['nama'].'</td>
                            <td>'.$key['email'].'</td>
                            <td>'.'62'.$key['telp'].'</td>
                            <td>'.$key['tanggal_lahir'].'</td>
                            <td>'.$key['umur'].'</td>
                            <td>'.$pendidikan.'</td>
                            <td>'.$dapat_informasi.'</td>
                            <td>'.$key['nama_startup'].'</td>
                            <td>'.$provinsi.'</td>
                            <td>'.$kabupaten.'</td>
                            <td>'.$key['jumlah_anggota'].'</td>
                            <td>'.$key['problem_disekitar'].'</td>
                            <td>'.$key['solusi_yang_dibuat'].'</td>
                            <td>'.$file_pitchdeck.'</td>
                            <td>'.$date_create.'</td>
                            <td>'.$date_update.'</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>';
                }
                
        }

       

        $output .= 
                '</tbody>
            </table>';

        $filename = "Data-Progress-User-".date('Y-m-d-h-i-s');

        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=".$filename.".xls");
        header("Cache-Control: max-age=0");

        echo $output;

    }

}

