<div class="row">
    <div class="col-12 col-lg-12 col-xl-12">
        <div class="card overflow-hidden radius-10">
            <div class="profile-cover bg-dark position-relative mb-4">
                <div class="user-profile-avatar shadow position-absolute top-50 start-0 translate-middle-x">
                    <img src="<?php echo $get_user['foto']; ?>" alt="<?php echo $get_user['nama']; ?>">
                </div>
            </div>
            
            <div class="card-body mt-5">
                <div class="mb-4">
                    <h5 class="mb-2">Kode user : <?php echo $get_user['kode_user']; ?></h5>
                    <h3 class="mb-2"><?php echo $get_user['nama']; ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card radius-10">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h4 class="card-title">Modul yang dipelajari</h4>
                    </div>
                </div>

                <div class="table-responsive pt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Modul</th>
                                <th>Video Dilihat</th>
                                <th>Hasil Test</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($modul) { 
                                $no = 0;
                                foreach ($modul as $key) { 
                                    $no++;
                                    ?>
                                    <tr>
                                        <th><?php echo $no; ?>.</th>
                                        <td><?php echo $key['modul']; ?></td>
                                        <td>
                                            <?php $his_video = $this->db->query("SELECT p.id_user, p.id_modul, v.judul
                                                FROM edu_modul_progress p
                                                LEFT JOIN edu_video v ON p.id_video = v.id_video
                                                WHERE p.id_user = '".$id_user."' 
                                                AND p.id_modul = '".$key['id_modul']."'
                                                AND p.id_video != 0
                                                ORDER BY p.id ASC ")->result_array(); ?>
                                                <table class="mt-3">
                                                    <?php 
                                                    $noo = 0;
                                                    foreach ($his_video as $keyv) {  
                                                        $noo++;
                                                        ?>  
                                                        <tr>
                                                            <td class="border-0" width="20%"><?php echo $noo; ?>. </td>
                                                            <td class="border-0"><?php echo $keyv['judul']; ?></td>
                                                        </tr>   
                                                    <?php } ?>
                                                </table>
                                            </td>
                                            <td>
                                                <?php $sekor_pre   = $this->db->query("SELECT *
                                                    FROM quiz_skor_user
                                                    WHERE id_user = '".$id_user."' 
                                                    AND id_modul = '".$key['id_modul']."'
                                                    AND jenis_quiz = 'PRE - TEST'
                                                    ORDER BY id_skor DESC LIMIT 1 ")->row_array(); ?>

                                                <?php  $sekor_post   = $this->db->query("SELECT *
                                                    FROM quiz_skor_user
                                                    WHERE id_user = '".$id_user."' 
                                                    AND id_modul = '".$key['id_modul']."'
                                                    AND jenis_quiz = 'POST - TEST'
                                                    ORDER BY id_skor DESC LIMIT 1 ")->row_array(); ?>

                                                    <table>
                                                        <tr>
                                                            <td class="border-0" width="60%">
                                                                <?php if ($sekor_pre) { ?>
                                                                    <p>Kuis sebelum belajar :</p> 
                                                                    <h5 class="mb-0"><b>SKOR: <?php echo $sekor_pre['skor']; ?></b></h5>
                                                                <?php } else { ?>
                                                                    <p>Kuis sebelum belajar :</p> 
                                                                    <h5 class="mb-0"><b>SKOR: 0</b></h5>
                                                                <?php } ?>
                                                            </td>
                                                            <td class="border-0">
                                                                <p class="mt-3">Tanggal :</p>
                                                                <p><b>
                                                                    <?php if ($sekor_pre) { ?>
                                                                        <?php if ($sekor_pre['tanggal'] != '0000-00-00 00:00:00') {
                                                                            echo date('d F Y H:i', strtotime($sekor_pre['tanggal']));
                                                                        } else { 
                                                                            echo '-';
                                                                        } ?>
                                                                    <?php } else { echo '-'; } ?>
                                                                </b></p>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="border-0" width="60%">
                                                                <?php if ($sekor_post) { ?>
                                                                    <p>Kuis setelah belajar :</p> 
                                                                    <h5 class="mb-0"><b>SKOR: <?php echo $sekor_post['skor']; ?></b></h5>
                                                                <?php } else { ?>
                                                                    <p>Kuis setelah belajar :</p> 
                                                                    <h5 class="mb-0"><b>SKOR: 0</b></h5>
                                                                <?php } ?>
                                                            </td>
                                                            <td class="border-0">
                                                                <p class="mt-3">Tanggal :</p>
                                                                <p><b>
                                                                    <?php if ($sekor_post) { ?>
                                                                        <?php if ($sekor_post['tanggal'] != '0000-00-00 00:00:00') {
                                                                            echo date('d F Y H:i', strtotime($sekor_post['tanggal']));
                                                                        } else { 
                                                                            echo '-';
                                                                        } ?>
                                                                    <?php } else { echo '-'; } ?>
                                                                </b></p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        <?php } ?>

                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="3">
                                                <p class="text-center" style="padding-top: 50px; padding-bottom: 50px;">Opps, data tidak tersedia</p>
                                            </td>
                                        </tr>

                                    <?php } ?>
                                </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $.getScript("<?php echo base_url();?>assets/backoffice/js/custome.js");
    });
</script>