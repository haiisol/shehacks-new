<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Data_user_terkurasi extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    public function Index()
    {
        $data = $this->main_model->check_access('data_user');
        
        $data['title']       = 'Data User';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']           = 'admin/user/data_user_terkurasi';
        $this->load->view('admin/index', $data);
    }

    function _sql() 
    {
        $fil_date               = trim($this->input->get('fil_date') ?? '');
        $fil_provinsi           = trim($this->input->get('fil_provinsi') ?? '');
        $fil_pendidikan         = trim($this->input->get('fil_pendidikan') ?? '');
        $fil_kategori_user      = trim($this->input->get('fil_kategori_user') ?? '');
        $fil_jumlah_anggota     = trim($this->input->get('fil_jumlah_anggota') ?? '');
        $fil_dapat_informasi    = trim($this->input->get('fil_dapat_informasi') ?? '');
        $fil_umur               = trim($this->input->get('fil_umur') ?? '');
        $fil_channel            = trim($this->input->get('fil_channel') ?? '');

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
            *
        ');

        $this->db->from('tb_user u');
        $this->db->where('u.status_kurasi', 'Yes');

        if ($fil_date) {
            $this->db->where('u.date_create BETWEEN "'.$tgl_start_query .'" AND "'.$tgl_end_query.'" ');
        }

        if ($fil_channel) {
            if ($fil_channel == 1) {
                $this->db->where(" u.channel LIKE '%2023%' ");
            } elseif($fil_channel == 2) {
                $this->db->where(" u.channel LIKE '%2024%' ");
            } elseif($fil_channel == 3) {
                $this->db->where(" u.channel LIKE '%2023, 2024%' ");
            } elseif($fil_channel == 3) {
                $this->db->where(" u.channel LIKE '%2023, 2024%' ");
            } else {

            }
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

        if ($fil_dapat_informasi) {
            $this->db->where('u.dapat_informasi', $fil_dapat_informasi);
        }

        if ($fil_jumlah_anggota) {
            if ($fil_jumlah_anggota == 1) {
                $this->db->where('u.jumlah_anggota BETWEEN "1" AND "5" ');
            } elseif($fil_jumlah_anggota == 2) {
                $this->db->where('u.jumlah_anggota BETWEEN "6" AND "10" ');
            } elseif($fil_jumlah_anggota == 3) {
                $this->db->where('u.jumlah_anggota >=', '11');
            } else {

            }
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

        return $this->db->get();
    }

    function datatables()
    {
        $valid_columns = array(
            0 => 'id_user',
            1 => 'nama_startup',
            2 => 'nama',
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

            // photo
            $url_image = $this->main_model->url_image($key['foto'], 'image-profile');

            // terakhir login
            if ($key['date_create'] == "0000-00-00 00:00:00") {
                $date_create = '-';
            } else {
                $date_create = time_ago_from_3($key['date_create']);
            }

            $url_detail = base_url().'admin/user/detail_user/detail/'.encrypt_url($key['id_user']);
            
            if ($key['file_pitchdeck']) {
                $file_pitchdeck = base_url().'file_media/file-user/'.$key['file_pitchdeck'];
                $action_file = '<a href="'.$file_pitchdeck.'" target="_blank" class="dropdown-item"><ion-icon name="book-sharp"></ion-icon> File Pitchdeck</a>';
            } else {
                $action_file = '';
            }

            if ($key['status'] == 1) {
                $status = '<span class="badge rounded-pill bg-success">Sudah</span> ';
            } else {
                $status = '<span class="badge rounded-pill bg-danger">Belum</span>';
            }

            $data[] = array(
                '<label class="checkbox-custome"><input type="checkbox" name="check-record" value="'.$key['id_user'].'" class="check-record"></label>',
                $no,
                $key['channel'],
                $kategori_user,
                // '<img src="'.$url_image.'" class="img-fluid">',
                '<a href="'.$url_detail.'" target="_blank">'.character_limiter($key['nama'], 20).'</a>',
                // character_limiter($key['email'], 20),
                '62'.$key['telp'],
                character_limiter($key['nama_startup'], 20),
                $date_create,
                $status,
                '<div class="dropdown dropdown-action '.$cact['access_action'].' options ms-auto text-center">
                    <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" id="dropdown-action">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end" aria-labelledby="dropdown-action">
                        <a href="'.$url_detail.'" target="_blank" class="dropdown-item"><ion-icon name="eye-sharp"></ion-icon> Detail</a>'.
                        $action_file
                        .'<a href="javascript:void(0)" class="dropdown-item '.$cact['access_delete'].'" id="delete-data" data="'.$key['id_user'].'"><ion-icon name="trash-sharp"></ion-icon> Delete</a>
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

    function delete_data()
    {
        $method = $this->input->post('method');

        if($method == 'single')
        {
            $id = $this->input->post('id');

            //$query = $this->db->query("UPDATE tb_user SET status = 1 WHERE id_user = '".$id."' ");
            $this->delete_single_image($id);
            $query = $this->db->query("DELETE FROM tb_user WHERE id_user = ".$id." ");

            if($query) {
                $response['status']  = 1;
                $response['message'] = 'Data berhasil dihapus.';
            } else {
                $response['status']  = 0;
                $response['message'] = 'Gagal menghapus data.';
            }
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

                //$query = $this->db->query("UPDATE tb_user SET status = 1 WHERE id_user in (".$id_str.")");
                $this->delete_multiple_image($id_str);
                $query = $this->db->query("DELETE FROM tb_user WHERE id_user in (".$id_str.") ");

                if($query) {
                    $response['status']  = 2;
                    $response['message'] = 'Data berhasil dihapus.';
                } else {
                    $response['status']  = 0;
                    $response['message'] = 'Gagal menghapus data.';
                }
            }
        }

        json_response($response);
    }

    function un_kurasi_data()
    {
       
        $json = $this->input->post('id');
        $id = array();

        if (strlen($json) > 0) {
            $id = json_decode($json);
        }

        if (count($id) > 0) {
            $id_str = "";
            $id_str = implode(',', $id);

            $query = $this->db->query("UPDATE tb_user SET status_kurasi = 'No' WHERE id_user in (".$id_str.")");

                if($query) {
                    $response['status']  = 2;
                    $response['message'] = 'Data berhasil dikurasi.';
                } else {
                    $response['status']  = 0;
                    $response['message'] = 'Gagal mengkurasi data.';
                }
            }

        json_response($response);
    }

    private function delete_single_image($id)
    {
        $query = $this->db->query("SELECT foto FROM tb_user WHERE id_user = ".$id." ")->row_array();

        if ($query['foto']) {
            if (file_exists(FCPATH.'file_media/image-user/'.$query['foto'])) {
                $filename = explode(".", $query['foto'])[0];
                array_map('unlink', glob(FCPATH."file_media/image-user/$filename.*"));
            }
        }
    }

    private function delete_multiple_image($id)
    {   
        $return = '';
        $query = $this->db->query("SELECT foto FROM tb_user WHERE id_user in (".$id.") ")->result_array();

        foreach ($query as $key) {
            if ($key['foto']) {
                if (file_exists(FCPATH.'file_media/file-user/'.$key['foto'])) {
                    $filename = explode(".", $key['foto'])[0];
                    array_map('unlink', glob(FCPATH."file_media/image-user/$filename.*"));
                }
            }
        }
        return $return;
    }

    function export()
    {
        $this->load->library('table');
        
        $query = $this->_sql();

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
                    <th>Nama Komunitas</th>
                    <th>Jumlah Anggota Komunitas</th>
                    <th>Jabatan Komunitas</th>
                    <th>Akun Komunitas</th>
                    <th>File Pengajuan Kegiatan</th>
                    <th>File Analisa Skorlife</th>
                    <th>File Profile Komunitas</th>
                    <th>File Pitch deck</th>
                    <th>Verifikasi Email</th>
                    <th>Tanggal Daftar</th>
                    <th>Tanggal Update</th>
                </thead>
                <tbody>';
 
                $no = 1;
                foreach($query->result_array() as $key) {
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

                    if ($key['file_pengajuan_kegiatan']) {
                        $file_pengajuan_kegiatan = base_url().'file_media/file-user/'.$key['file_pengajuan_kegiatan'];
                    } else {
                        $file_pengajuan_kegiatan = '-';
                    }

                    if ($key['file_analisa_skorlife']) {
                        $file_analisa_skorlife = base_url().'file_media/file-user/'.$key['file_analisa_skorlife'];
                    } else {
                        $file_analisa_skorlife = '-';
                    }

                    if ($key['file_profile_komunitas']) {
                        $file_profile_komunitas = base_url().'file_media/file-user/'.$key['file_profile_komunitas'];
                    } else {
                        $file_profile_komunitas = '-';
                    }

                    if ($key['status'] == 1) {
                        $email = "Ya";
                    } else {
                        $email = 'Tidak';
                    }

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
                            <td>'.$key['nama_komunitas'].'</td>
                            <td>'.$key['jumlah_anggota_komunitas'].'</td>
                            <td>'.$key['jabatan_komunitas'].'</td>
                            <td>'.$key['akun_komunitas'].'</td>
                            <td>'.$file_pengajuan_kegiatan.'</td>
                            <td>'.$file_analisa_skorlife.'</td>
                            <td>'.$file_profile_komunitas.'</td>
                            <td>'.$file_pitchdeck.'</td>
                            <td>'.$email.'</td>
                            <td>'.$date_create.'</td>
                            <td>'.$date_update.'</td>
                        </tr>';
                }

                // <td '.$style_foto .'>'.$foto.'</td>

        $output .= 
                '</tbody>
            </table>';

        $filename = "Export-Data-User-".date('Y-m-d-h-i-s');

        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=".$filename.".xls");
        header("Cache-Control: max-age=0");

        echo $output;
    }

}

