<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->main_model->logged_in_admin();
        $this->id_admin = decrypt_url($this->session->userdata('key_auth_admin'));
    }

    public function Index()
    {
        $data = $this->main_model->check_access('content_page');
        
        $data['title']       = 'Data Gallery';
        $data['description'] = '';
        $data['keywords']    = '';
        $data['page']           = 'admin/content/gallery';
        $this->load->view('admin/index', $data);
    }

    function _sql() 
    {
        $this->db->select('c.*');
        $this->db->from('tb_content c');
        $this->db->where('c.status_delete', 0);
        $this->db->where('c.section', 'gallery');
        
        return $this->db->get();
    }

    function datatables()
    {
        $valid_columns = array(
            0 => null,
            1 => 'id',
            2 => 'heading',
        );

        $this->main_model->datatable($valid_columns);

        $query = $this->_sql();

        $no   = $this->input->post('start');
        $data = array();

        foreach($query->result_array() as $key) {
            $no++;

            $cact = $this->main_model->check_access_action('content_page');

            // image
            $url_image = $this->main_model->url_image($key['image'], 'image-content');

            // status
            if ($key['status'] == 1) {
                $status = '<span class="badge rounded-pill bg-success">Aktif</span>';
            } else {
                $status = '<span class="badge rounded-pill bg-warning">Tidak Aktif</span> ';
            }

            // date update
            if ($key['date_update'] == '0000-00-00 00:00:00') {
                if ($key['date_create'] == '0000-00-00 00:00:00') {
                    $date_update = '-';
                } else {
                    $date_update = time_ago_from_3($key['date_create']);;
                }
            } else {
                $date_update = time_ago_from_3($key['date_update']);
            }

            $data[] = array(
                '<label class="checkbox-custome"><input type="checkbox" name="check-record" value="'.$key['id'].'" class="check-record"></label>',
                $no,
                '<a href="javascript:void(0)" class="detail-data" data="'.$key['id'].'">'.character_limiter($key['heading'], 25).'</a>',
                '<img src="'.$url_image.'" class="img-fluid">',
                $status,
                $date_update,
                '<div class="dropdown dropdown-action '.$cact['access_action'].' options ms-auto text-center">
                    <div class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" id="dropdown-action">
                        <ion-icon name="ellipsis-horizontal-sharp"></ion-icon>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end" aria-labelledby="dropdown-action">
                        <a href="javascript:void(0)" class="dropdown-item edit-data '.$cact['access_edit'].'" data="'.$key['id'].'"><ion-icon name="pencil-sharp"></ion-icon> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item change-status '.$cact['access_edit'].'" data="'.$key['id'].'" param="'.$key['status'].'"><ion-icon name="swap-horizontal-sharp"></ion-icon> Change Status</a>
                        <a href="javascript:void(0)" class="dropdown-item '.$cact['access_delete'].'" id="delete-data" data="'.$key['id'].'"><ion-icon name="trash-sharp"></ion-icon> Delete</a>
                    </div>
                </div>'
            );
        }

        $response = array(
            "draw"            => intval($this->input->post("draw")),
            "recordsTotal"    => $this->_sql()->num_rows(),
            "recordsFiltered" => $this->_sql()->num_rows(),
            "data"            => $data
        );

        json_response($response);
    }

    function cek_value() 
    {
        $value = $this->input->post('value');

        $query = $this->db->query("SELECT c.id FROM tb_content c WHERE c.heading = '".$value."' ")->result();

        if ($query) {
            $response['status']  = 0;
            $response['message'] = '';
        } else {
            $response['status']  = 1;
            $response['message'] = '';
        }

        json_response($response);
    }

    function add_data()
    {
        if (!empty($_FILES['image']['name'])) {
            $data['image'] = $this->upload_image('image');
        }
        
        $data['heading']     = $this->input->post('heading');
        $data['section']     = 'gallery';
        $data['status']      = $this->input->post('status');
        $data['date_create'] = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');

        $query = $this->db->insert('tb_content', $data);

        if($query) {
            $response['status']  = 1;
            $response['message'] = 'Data berhasil ditambahkan.';
        } else {
            $response['status']  = 2;
            $response['message'] = 'Gagal menambah data.';
        }

        json_response($response);
    }

    function get_data()
    {
        $id = $this->input->post('id');

        $query = $this->db->query("SELECT c.* FROM tb_content c WHERE c.id=".$id."")->row_array();

        $response['id']      = $query['id'];
        $response['heading'] = $query['heading'];
        $response['status']  = $query['status'];
        $response['image']   = $this->main_model->url_image($query['image'], 'image-content');

        json_response($response);
    }

    function edit_data()
    {   
        $id = $this->input->post('id');

        if (!empty($_FILES['image']['name'])) {
            $data['image'] = $this->upload_image('image');
        }
        
        $data['heading']     = $this->input->post('heading');
        $data['status']      = $this->input->post('status');
        $data['date_update'] = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d H:i:s');

        $query = $this->main_model->update_data('tb_content', $data, 'id', $id);

        if($query) {
            $response['status']  = 3;
            $response['message'] = 'Data berhasil Disimpan.';
        } else {
            $response['status']  = 4;
            $response['message'] = 'Gagal menyimpan data.';
        }

        json_response($response);
    }

    private function upload_image($name)
    {
        $config['upload_path']  = './file_media/image-content/';
        $config['allowed_types']= 'jpg|jpeg|png';
        $config['file_name']    = 'intro-'.substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
        $config['quality']      = '60%';

        if ($this->input->post('param') == 'edit') {
            $id = $this->input->post('id');
            $query = $this->db->query("SELECT c.image FROM tb_content c WHERE id=".$id." ")->row_array();

            if ($name == 'image') {
                if($query['image']) {
                    if (file_exists(FCPATH.'file_media/image-content/'.$query['image'])) {
                        $config['overwrite'] = true;
                        unlink(FCPATH.'file_media/image-content/'.$query['image']);
                    }
                }
            }
        }
        
        $this->upload->initialize($config);

        if ($this->upload->do_upload($name)) {
            return $this->upload->data('file_name');
        }
        return '';
    }

    function detail_data()
    {
        $id = $this->input->post('id');

        $query = $this->db->query("
            SELECT c.* 
            FROM tb_content c 
            WHERE c.id = ".$id."
        ")->row_array();


        // status
        if ($query['status'] == 1) {
            $status = '<span class="badge rounded-pill bg-success">Aktif</span>';
        } else {
            $status = '<span class="badge rounded-pill bg-warning">Tidak Aktif</span> ';
        }

        // date update
        if ($query['date_update'] == "0000-00-00 00:00:00") {
            if ($query['date_create'] == "0000-00-00 00:00:00") {
                $date_update = '-';
            } else {
                $date = date_ind(date('Y-m-d', strtotime($query['date_create'])), 'full');
                $date_update = $date.' '.date('h.i', strtotime($query['date_create']));
            }
        } else {
            $date = date_ind(date('Y-m-d', strtotime($query['date_create'])), 'full');
            $date_update = $date.' '.date('h.i', strtotime($query['date_create']));
        }

        $response['id']      = $query['id'];
        $response['heading'] = $query['heading'];
        $response['image']   = $this->main_model->url_image($query['image'], 'image-content');
        $response['status']  = $status;
        $response['date']    = $date_update;

        json_response($response);
    }

    function delete_data()
    {
        $method = $this->input->post('method');

        if($method == 'single')
        {
            $id = $this->input->post('id');

            // $query = $this->db->query("UPDATE tb_content SET status_delete = 1 WHERE id = '".$id."' ");
            $this->delete_single_image($id);
            $query = $this->db->query("DELETE FROM tb_content WHERE id = ".$id." ");

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

                // $query = $this->db->query("UPDATE tb_content SET status_delete = 1 WHERE id in (".$id_str.")");
                $this->delete_multiple_image($id_str);
                $query = $this->db->query("DELETE FROM tb_content WHERE id in (".$id_str.") ");

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

    private function delete_single_image($id)
    {
        $query = $this->db->query("SELECT image FROM tb_content WHERE id = ".$id." ")->row_array();

        if ($query['image']) {
            if (file_exists(FCPATH.'file_media/image-content/'.$query['image'])) {
                $filename = explode(".", $query['image'])[0];
                array_map('unlink', glob(FCPATH."file_media/image-content/$filename.*"));
            }
        }
    }

    private function delete_multiple_image($id)
    {
        $return = '';
        $query = $this->db->query("SELECT image FROM tb_content WHERE id in (".$id.") ")->result_array();

        foreach ($query as $key) {
            if ($key['image']) {
                if (file_exists(FCPATH.'file_media/image-content/'.$key['image'])) {
                    $filename = explode(".", $key['image'])[0];
                    array_map('unlink', glob(FCPATH."file_media/image-content/$filename.*"));
                }
            }
        }
        return $return;
    }


    function change_status()
    {
        $id    = $this->input->post('id');
        $param = $this->input->post('param');

        if ($param == 1) {
            $data['status'] = 0;
        } 
        else if ($param == 0) {
            $data['status'] = 1;
        }

        $query = $this->main_model->update_data('tb_content', $data, 'id', $id);

        if($query) {

            $response['status'] = 1;

            if ($param == 1) {
                $response['message'] = 'Berhasil me-nonaktifkan.';
            } 
            else if ($param == 0) {
                $response['message'] = 'Berhasil meng-aktifkan.';
            }
        } 
        else {
            $response['status']  = 0;
            $response['message'] = 'Gagal mengubah status.';
        }
        json_response($response);
    }

}

