<?php

namespace App\Controllers\Modul;

use Config\Database;
use App\Controllers\FrontController;

class ModulMaster extends FrontController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function pelajari($slug, $id_modul_enc)
    {
        $id_modul = decrypt_url($id_modul_enc);

        $get_modul = $this->db->table('edu_modul')
            ->select('id_modul, modul')
            ->where('id_modul', $id_modul)
            ->get()
            ->getRowArray();

        if (!$get_modul) {
            return redirect()->to('/');
        }

        $data = [
            'id_modul' => $id_modul,
            'title' => ucwords($get_modul['modul']),
            'description' => ucwords($get_modul['modul']),
            'page' => 'modul/master_modul'
        ];

        $this->data = array_merge($this->data, $data);

        return view('index', $this->data);
    }

    function fetch_data_info()
    {
        $id_user = key_auth();

        $get_user = $this->db->table('tb_user')
            ->select('nama, email, kategori_user AS package_name')
            ->where('id_user', $id_user)
            ->get()
            ->getRowArray();

        $response = [
            'user_photo' => base_url('assets/backoffice/images/no-image-user.png'),
            'user_name' => $get_user['nama'] ?? '',
            'user_email' => $get_user['email'] ?? '',
            'user_package' => $get_user['package_name'] ?? ''
        ];

        return json_response($response);
    }


    function fetch_data_video()
    {
        $id_modul = $this->request->getPost('id_modul');
        $id_param = $this->request->getPost('id');
        $param = $this->request->getPost('param');

        $video = $this->db->table('edu_video')
            ->where([
                'id_video' => $id_param,
                'id_modul' => $id_modul,
                'status_delete' => 0
            ])
            ->get()
            ->getRowArray();

        if (!$video) {
            return json_response([
                'status' => 0,
                'message' => ''
            ]);
        }

        if ($video['jenis'] === 'url') {
            $url_video = 'https://www.youtube.com/embed/' . $video['url'] . '?autoplay=1';
        } else {
            $url_video = base_url('file_media/file-modul/video/' . $video['file_video']);
        }

        $this->submit_progress($id_modul, $id_param, $param);

        return json_response([
            'status' => 1,
            'message' => '',
            'id_video' => $video['id_video'],
            'judul' => $video['judul'],
            'jenis' => $video['jenis'],
            'url_video' => $url_video
        ]);
    }

    function fetch_data_quiz()
    {
        session()->remove(['offset', 'skor']);

        $id_modul = $this->request->getPost('id_modul');
        $param = $this->request->getPost('param');

        $quiz = $this->db->table('quiz')
            ->select('id_quiz, pertanyaan')
            ->where([
                'id_modul' => $id_modul,
                'status_delete' => 0
            ])
            ->limit(1)
            ->offset(0)
            ->get()
            ->getRowArray();

        if (!$quiz) {
            return json_response([
                'status' => 0,
                'message' => 'Quiz tidak ditemukan'
            ]);
        }

        $quiz_selection = $this->db->table('quiz_jawaban')
            ->select('id_jawaban, jawaban')
            ->where([
                'id_quiz' => $quiz['id_quiz'],
                'status_delete' => 0
            ])
            ->get()
            ->getResultArray();

        $this->submit_progress($id_modul, '', $param);

        return json_response([
            'status' => 1,
            'message' => '',
            'data' => [
                'id_quiz' => $quiz['id_quiz'],
                'quiz_question' => $quiz['pertanyaan'],
                'quiz_selection' => $quiz_selection,
                'quiz_param' => $param
            ]
        ]);

    }

    function submit_quiz()
    {
        $session = session();
        $skor = $session->get('skor');
        $id_modul = $this->request->getPost('id_modul');
        $id_user = $this->request->getPost('id_user');
        $id_quiz = $this->request->getPost('id_quiz');
        $id_jawaban = $this->request->getPost('id_jawaban');
        $quiz_param = $this->request->getPost('quiz_param');

        if ($skor) {
            $id_skor = $skor;
        } else {
            $this->db->table('quiz_skor_user')->insert([
                'id_user' => $id_user,
                'id_modul' => $id_modul,
                'jenis_quiz' => $quiz_param,
                'tanggal' => date('Y-m-d H:i:s')
            ]);

            $id_skor = $this->db->insertID();
            $session->set('skor', $id_skor);
        }

        if ($id_jawaban) {
            $jawaban = $this->db->table('quiz_jawaban')
                ->select('jawaban')
                ->where([
                    'id_jawaban' => $id_jawaban,
                    'status_delete' => 0
                ])
                ->get()
                ->getRow('jawaban');

            $answer = $this->db->table('quiz')
                ->select('answer')
                ->where('id_quiz', $id_quiz)
                ->get()
                ->getRow('answer');

            $status_jawaban = ($answer == $id_jawaban) ? 'TRUE' : 'FALSE';

        } else {
            $jawaban = '';
            $status_jawaban = 'FALSE';
        }

        $insert = $this->db->table('quiz_jawaban_user')->insert([
            'id_skor' => $id_skor,
            'id_quiz' => $id_quiz,
            'id_jawaban' => $id_jawaban,
            'jawaban' => $jawaban,
            'status_jawaban' => $status_jawaban
        ]);

        if (!$insert) {
            return json_response(['status' => 0, 'message' => '']);
        }

        // ===== cek jumlah quiz =====
        $count_quiz = $this->db->table('quiz')
            ->where([
                'id_modul' => $id_modul,
                'status_delete' => 0
            ])
            ->countAllResults();

        $offset = $session->get('offset') ?? 0;
        $cek_offset = $offset + 1;

        if ($cek_offset == $count_quiz) {
            $data = $this->fetch_data_quiz_skor($id_modul, $id_user, $quiz_param);

            return json_response([
                'status' => 1,
                'message' => '',
                'data' => $data
            ]);
        }

        return json_response([
            'status' => 2,
            'message' => '',
            'data' => $this->fetch_data_quiz_next($id_modul, $quiz_param)
        ]);
    }

    function fetch_data_quiz_next($id_modul, $quiz_param)
    {
        $session = session();

        $offset = ($session->get('offset') ?? 0) + 1;
        $session->set('offset', $offset);

        $quiz = $this->db->table('quiz')
            ->select('id_quiz, pertanyaan')
            ->where([
                'id_modul' => $id_modul,
                'status_delete' => 0
            ])
            ->limit(1)
            ->offset($offset)
            ->get()
            ->getRowArray();

        $quiz_selection = $this->db->table('quiz_jawaban')
            ->select('id_jawaban, jawaban')
            ->where([
                'id_quiz' => $quiz['id_quiz'],
                'status_delete' => 0
            ])
            ->get()
            ->getResultArray();

        return [
            'id_quiz' => $quiz['id_quiz'],
            'quiz_question' => $quiz['pertanyaan'],
            'quiz_selection' => $quiz_selection,
            'quiz_param' => $quiz_param
        ];
    }

    function fetch_data_quiz_skor($id_modul, $id_user, $quiz_param)
    {
        $skor = $this->db->table('quiz_skor_user')
            ->where([
                'id_user' => $id_user,
                'id_modul' => $id_modul
            ])
            ->orderBy('id_skor', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        $total = $this->db->table('quiz_jawaban_user')
            ->where('id_skor', $skor['id_skor'])
            ->countAllResults();

        $benar = $this->db->table('quiz_jawaban_user')
            ->where([
                'id_skor' => $skor['id_skor'],
                'status_jawaban' => 'TRUE'
            ])
            ->countAllResults();

        $salah = $this->db->table('quiz_jawaban_user')
            ->where([
                'id_skor' => $skor['id_skor'],
                'status_jawaban' => 'FALSE'
            ])
            ->countAllResults();

        $nilai = round(($benar / $total) * 100);

        $this->mainModel->update_data(
            'quiz_skor_user',
            ['skor' => $nilai],
            'id_skor',
            $skor['id_skor']
        );

        // ===== sertifikat =====
        if ($quiz_param === 'POST - TEST' && $nilai >= 60) {

            $progress = $this->db->table('edu_modul_user_progress')
                ->where([
                    'id_user' => $id_user,
                    'id_modul' => $id_modul
                ])
                ->orderBy('id', 'DESC')
                ->limit(1)
                ->get()
                ->getRowArray();

            if ($progress) {
                $this->mainModel->update_data(
                    'edu_modul_user_progress',
                    ['date_sertifikat' => date('Y-m-d')],
                    'id',
                    $progress['id']
                );
            }
        }

        return [
            'tanggal' => date('d F Y H:i', strtotime($skor['tanggal'])),
            'benar' => $benar,
            'salah' => $salah,
            'skor' => $nilai,
            'id_modul' => $id_modul,
            'param' => $quiz_param
        ];
    }



    function modul_get_data()
    {
        $id = $this->request->getPost('id');

        $query = $this->db->table('edu_modul')
            ->where('id_modul', $id)
            ->get()
            ->getRowArray();

        $data = '';

        if ($query) {
            if (!empty($query['cover'])) {
                $img_url = base_url('assets/files/modul/' . $query['cover']);
                $data .= '<img class="mb-2 mt-2" src="' . $img_url . '" alt="Cover Modul">';
            }

            $data .= '<div class="separator"></div>';
            $data .= '<p class="mt-3">' . $query['deskripsi_modul'] . '</p>';

            $response = [
                'success' => 1,
                'data' => $data
            ];
        } else {
            $response = [
                'success' => 0,
                'data' => ''
            ];
        }

        return json_response($response);
    }

    function check_learn_progress()
    {
        $id_modul = $this->request->getPost('id_modul');
        $id_user = $this->request->getPost('id_user');

        $data_skor = '';
        $status = 0;
        $i_post = 0;
        $id_param = '';
        $param = 'PRE - TEST';

        // Cek progress complete
        $get_modul_progress_cek_complete = $this->db->table('edu_modul_progress')
            ->select('id')
            ->where([
                'id_user' => $id_user,
                'id_modul' => $id_modul
            ])
            ->where('id_quiz !=', 0)
            ->where('quiz_pre !=', 0)
            ->where('quiz_post !=', 0)
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        // Progress terakhir
        $get_modul_progress = $this->db->table('edu_modul_progress e')
            ->select('e.*')
            ->join('edu_modul m', 'm.id_modul = e.id_modul', 'left')
            ->where([
                'e.id_user' => $id_user,
                'e.id_modul' => $id_modul,
                'm.status_delete' => 0
            ])
            ->orderBy('e.id', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        // Total video progress
        $cek_total_progress_video = $this->db->table('edu_modul_progress e')
            ->selectCount('e.id', 'total')
            ->join('edu_modul m', 'm.id_modul = e.id_modul', 'left')
            ->where([
                'e.id_user' => $id_user,
                'm.id_modul' => $id_modul,
                'm.status_delete' => 0
            ])
            ->where('e.id_video !=', 0)
            ->get()
            ->getRowArray();

        $total_i_video = $cek_total_progress_video['total'] ?? 0;

        if (empty($get_modul_progress_cek_complete)) {

            if (empty($get_modul_progress)) {
                $status = 0;
                $param = 'PRE - TEST';

            } else {
                $status = 1;

                if ($get_modul_progress['quiz_pre'] == 1 && $get_modul_progress['quiz_post'] == 1) {
                    $param = 'POST - TEST';
                    $id_param = $id_modul;
                } else {
                    if ($get_modul_progress['id_video'] != 0) {
                        $param = 'VIDEO';
                        $id_param = $get_modul_progress['id_video'];
                        $i_post = $total_i_video;

                    } elseif ($get_modul_progress['id_quiz'] != 0) {

                        if ($get_modul_progress['quiz_pre'] != 0) {
                            $param = 'PRE - TEST';
                            $id_param = $id_modul;
                        } else {
                            $param = 'POST - TEST';
                            $id_param = $id_modul;
                        }
                    }
                }
            }

        } else {

            // Cek skor post-test
            $get_modul_progress_cek_complete_quiz_post = $this->db->table('quiz_skor_user')
                ->select('id_skor')
                ->where([
                    'id_user' => $id_user,
                    'id_modul' => $id_modul,
                    'jenis_quiz' => 'POST - TEST'
                ])
                ->where('skor >', 60)
                ->orderBy('id_skor', 'DESC')
                ->limit(1)
                ->get()
                ->getRowArray();

            // Total video modul
            $get_modul_video = $this->db->table('edu_video')
                ->selectCount('id_video', 'total')
                ->where([
                    'id_modul' => $id_modul,
                    'status_delete' => 0
                ])
                ->get()
                ->getRowArray();

            $status = 1;
            $i_post = ($get_modul_video['total'] ?? 0) + 1;
            $param = 'POST - TEST';
            $id_param = $id_modul;

            if ($get_modul_progress_cek_complete_quiz_post) {
                $data_skor = $this->fetch_data_quiz_skor($id_modul, $id_user, 'POST - TEST');
            }
        }

        $response = [
            'status' => $status,
            'id_param' => $id_param,
            'param' => $param,
            'i_post' => $i_post,
            'data_skor' => $data_skor
        ];

        return json_response($response);
    }

    function cek_trigger_menu()
    {
        $param = $this->request->getPost('param');
        $id_modul = $this->request->getPost('id_modul');
        $id = $this->request->getPost('id');
        $i_post = 0;

        // Total video
        $get_video = $this->db->table('edu_video')
            ->selectCount('id_video', 'total')
            ->where([
                'id_modul' => $id_modul,
                'status_delete' => 0
            ])
            ->get()
            ->getRowArray();

        if ($param === 'VIDEO') {
            $videos = $this->db->table('edu_video')
                ->select('id_video')
                ->where([
                    'id_modul' => $id_modul,
                    'status_delete' => 0
                ])
                ->get()
                ->getResultArray();

            $row = 0;
            foreach ($videos as $video) {
                $row++;
                if ($video['id_video'] == $id) {
                    $i_post = $row;
                    break;
                }
            }
        } else {
            $i_post = ($get_video['total'] ?? 0) + 1;
        }

        return json_response([
            'status' => 1,
            'success' => 'success',
            'i_post' => $i_post
        ]);

    }

    function cek_data_modul()
    {
        $i_post = (int) $this->request->getPost('i_post');
        $id_modul = $this->request->getPost('id_modul');

        $array = [];

        // PRE TEST
        $get_quiz_pre = $this->db->table('quiz')
            ->where([
                'id_modul' => $id_modul,
                'status_delete' => 0
            ])
            ->get()
            ->getResultArray();

        if ($get_quiz_pre) {
            $array[] = [
                'id_param' => '',
                'menu_active' => 'menu-qpr-' . $id_modul,
                'param' => 'PRE - TEST'
            ];
        }

        // VIDEO
        $get_video = $this->db->table('edu_video')
            ->where([
                'id_modul' => $id_modul,
                'status_delete' => 0
            ])
            ->get()
            ->getResultArray();

        if ($get_quiz_pre) {
            foreach ($get_video as $video) {
                $array[] = [
                    'id_param' => $video['id_video'],
                    'menu_active' => 'menu-vid' . $video['id_video'] . '-' . $id_modul,
                    'param' => 'VIDEO'
                ];
            }
        }

        // POST TEST
        $get_quiz_post = $this->db->table('quiz')
            ->where([
                'id_modul' => $id_modul,
                'status_delete' => 0
            ])
            ->get()
            ->getResultArray();

        if ($get_quiz_post) {
            $array[] = [
                'id_param' => '',
                'menu_active' => 'menu-qpo-' . $id_modul,
                'param' => 'POST - TEST'
            ];
        }

        // Hitung next menu
        if ($i_post === 0) {
            $i_plus = 1;
            $i_array = $array[1] ?? null;
        } else {
            $i_plus = $i_post + 1;
            $i_array = $array[$i_post + 1] ?? null;
        }

        return json_response([
            'status' => 1,
            'data' => $array,
            'i_post' => $i_plus,
            'data_post' => $i_array
        ]);
    }

    function submit_progress($id_modul, $id_param, $param)
    {
        $id_user = key_auth();
        $now = date('Y-m-d H:i:s');

        // === VIDEO ===
        if ($param === 'VIDEO') {

            $exists = $this->db->table('edu_modul_progress')
                ->where([
                    'id_user' => $id_user,
                    'id_modul' => $id_modul,
                    'id_video' => $id_param
                ])
                ->get()
                ->getRowArray();

            if (!$exists) {
                $this->db->table('edu_modul_progress')->insert([
                    'id_user' => $id_user,
                    'id_modul' => $id_modul,
                    'id_video' => $id_param,
                    'date_create' => $now
                ]);
            }

        } else {

            $progress = $this->db->table('edu_modul_progress')
                ->where([
                    'id_user' => $id_user,
                    'id_modul' => $id_modul,
                    'id_quiz' => 1
                ])
                ->get()
                ->getRowArray();

            if (!$progress) {
                $this->db->table('edu_modul_progress')->insert([
                    'id_user' => $id_user,
                    'id_modul' => $id_modul,
                    'id_quiz' => 1,
                    'quiz_pre' => 1,
                    'date_create' => $now
                ]);
            } else {
                if ($param === 'PRE - TEST') {
                    $this->db->table('edu_modul_progress')
                        ->where('id', $progress['id'])
                        ->update([
                            'quiz_pre' => 1,
                            'date_update' => $now
                        ]);
                } elseif ($param === 'POST - TEST') {
                    $this->db->table('edu_modul_progress')
                        ->where('id', $progress['id'])
                        ->update([
                            'quiz_post' => 1,
                            'date_update' => $now
                        ]);
                }
            }
        }

        // === UPDATE USER PROGRESS ===
        $total_video = $this->db->table('edu_video')
            ->where([
                'id_modul' => $id_modul,
                'status_delete' => 0
            ])
            ->countAllResults();

        $modul = $this->db->table('edu_modul')
            ->where([
                'id_modul' => $id_modul,
                'status_delete' => 0
            ])
            ->get()
            ->getRow();

        $quiz = ($modul && $modul->status_quiz == 1) ? 1 : 0;

        $video_progress = $this->db->table('edu_modul_progress p')
            ->join('edu_video v', 'p.id_video = v.id_video', 'left')
            ->where([
                'p.id_user' => $id_user,
                'p.id_modul' => $id_modul,
                'v.status_delete' => 0
            ])
            ->countAllResults();

        $total_param = $total_video + $quiz;
        $total_done = $video_progress + $quiz;
        $persentase = ($total_param > 0) ? ($total_done / $total_param) * 100 : 0;

        $user_progress = $this->db->table('edu_modul_user_progress')
            ->where([
                'id_modul' => $id_modul,
                'id_user' => $id_user
            ])
            ->get()
            ->getRow();

        if ($user_progress) {
            $this->db->table('edu_modul_user_progress')
                ->where('id', $user_progress->id)
                ->update([
                    'persentase' => $persentase,
                    'date_update' => $now
                ]);
        } else {
            $this->db->table('edu_modul_user_progress')->insert([
                'id_modul' => $id_modul,
                'id_user' => $id_user,
                'persentase' => $persentase,
                'date_create' => $now
            ]);
        }
    }
}