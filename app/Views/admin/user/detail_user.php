<div class="row">
    <div class="col-12 col-lg-6 col-xl-6">
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
    <div class="col-12 col-lg-6 col-xl-6">
        <div class="card radius-10">
            <div class="card-body">
                <h5 class="mb-3">Profile</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="list-info">
                            <div class="list-item">
                                <p class="label">Email :</p>
                                <a href="mailto:<?php echo $get_user['email']; ?>">
                                    <p class="value"><?php echo $get_user['email']; ?></p>
                                </a>
                            </div>
                            <div class="list-item">
                                <p class="label">Telepon :</p>
                                <a href="tel:62<?php echo $get_user['telp']; ?>">
                                    <p class="value">62<?php echo $get_user['telp']; ?></p>
                                </a>
                            </div>
                            <div class="list-item">
                                <p class="label">Tanggal Lahir :</p>
                                <p class="value"><?php echo $get_user['tanggal_lahir']; ?></p>
                            </div>
                            <div class="list-item">
                                <p class="label">Umur :</p>
                                <p class="value"><?php echo $get_user['umur']; ?> Tahun</p>
                            </div>
                            <div class="list-item">
                                <p class="label">Provinsi:</p>
                                <p class="value"><?php echo $get_user['provinsi']; ?></p>
                            </div>
                            <div class="list-item">
                                <p class="label">Kabupaten:</p>
                                <p class="value"><?php echo $get_user['kabupaten']; ?></p>
                            </div>
                            <div class="list-item">
                                <p class="label">Pendidikan terakhir :</p>
                                <p class="value"><?php echo $get_user['pendidikan']; ?></p>
                            </div>
                            <div class="list-item">
                                <p class="label">Darimana mengetahui informasi :</p>
                                <p class="value"><?php echo $get_user['dapat_informasi']; ?></p>
                            </div>
                            <div class="list-item">
                                <p class="label">Nama Startup :</p>
                                <p class="value"><?php echo $get_user['nama_startup']; ?></p>
                            </div>
                            <div class="list-item">
                                <p class="label">Jumlah Anggota Tim:</p>
                                <p class="value"><?php echo $get_user['jumlah_anggota']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="list-info">
                            <div class="list-item">
                                <p class="label">Problem di sekitar:</p>
                                <p class="value"><?php echo $get_user['problem_disekitar']; ?></p>
                            </div>
                            <div class="list-item">
                                <p class="label">Solusi yang dibuat :</p>
                                <p class="value"><?php echo $get_user['solusi_yang_dibuat']; ?></p>
                            </div>
                            <div class="list-item">
                                <p class="label">File Pitchdeck:</p>
                                <?php if ($get_user['file_pitchdeck']) { ?>
                                    <a href="<?php echo $get_user['file_pitchdeck']; ?>" target="_blank" class="dropdown-item"><ion-icon name="book-sharp"></ion-icon> URL Pitchdeck</a>
                                <?php } else { ?>
                                    <p class="value">-</p>
                                <?php } ?>
                                
                            </div>
                            <div class="list-item">
                                <p class="label">Tanggal Diupdate :</p>
                                <p class="value"><?php echo $get_user['date_update']; ?></p>
                            </div>
                        </div>
                    </div>
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