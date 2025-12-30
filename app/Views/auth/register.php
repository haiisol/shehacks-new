<section class="auth-section section section-md">
    <div class="container">
        
        <!-- nav step -->
        <div class="auth-form">
            <ul class="nav nav-step" id="nav-step">
                <li class="nav-item nav-step-item nav-step-item-1 active">
                    <div class="number">1</div>
                </li>
                <li class="nav-item nav-step-item nav-step-item-2">
                    <div class="number">2</div>
                </li>
                <li class="nav-item nav-step-item nav-step-item-3">
                    <div class="number">3</div>
                </li>
            </ul>
            <div id="feedback-register"></div>
        </div>

        <!-- fieldset step -->
        <div class="wrap-step wrap-step-1 animated fadeIn">
            <div class="auth-form">
                <div class="section-title">
                    <h2 class="title section-heading-sm">Pilih Profil</h2>
                    <p class="description section-description-md">Pilih profil di bawah ini sesuai dengan keadaan Anda saat ini</p>
                </div>

                <form method="post" id="form-profile" class="form-style">
                    <div class="category-area">
                        <div class="category-item">
                            <div class="radio-style">
                                <input type="radio" name="kategori_user" id="kategori_user1" value="Ideasi" class="radio-button">

                                <label for="kategori_user1" class="radio-tile">
                                    <div class="radio-box">
                                        <img src="<?php echo base_url();?>assets/front/img/icon/ideasi-icon.webp" alt="icon ideasi" class="img-fluid lazyload">
                                        <p class="radio-text section-description-sm">Ideasi</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="category-item">
                            <div class="radio-style">
                                <input type="radio" name="kategori_user" id="kategori_user2" value="MVP" class="radio-button">

                                <label for="kategori_user2" class="radio-tile">
                                    <div class="radio-box">
                                        <img src="<?php echo base_url();?>assets/front/img/icon/mvp-icon.webp" alt="icon mvp" class="img-fluid lazyload">
                                        <p class="radio-text section-description-sm">MVP</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="category-item">
                            <div class="radio-style">
                                <input type="radio" name="kategori_user" id="kategori_user3" value="Pandu Perempuan Daerah" class="radio-button">

                                <label for="kategori_user3" class="radio-tile">
                                    <div class="radio-box">
                                        <img src="<?php echo base_url();?>assets/front/img/icon/papeda-icon.webp" alt="icon mvp" class="img-fluid lazyload">
                                        <p class="radio-text section-description-sm">Pandu Perempuan Daerah</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="feedback-kategori-user"></div>

                    <div class="form-actions">
                        <button type="submit" id="submit-form-profile" class="btn w-100"><span>SELANJUTNYA</span></button>
                    </div>
                </form>
            </div>
        </div>


        <!-- fieldset step -->
        <div class="wrap-step wrap-step-2 animated fadeIn d-none">
            <div class="auth-form">
                <div class="section-title">
                    <h2 class="title section-heading-sm">Create An Account</h2>
                    <p class="section-description-md"><span>Already have an account?</span> <a href="<?php echo base_url();?>login" class="hover-1 text-accent fw-500">Login</a></p>
                </div>

                <form method="post" id="form-personal" class="form-style">
                    <div class="form-group">
                        <label for="nama" class="control-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="nama" id="nama" placeholder="Masukan nama lengkap Anda" class="form-control val-capitalize">
                    </div>

                    <div class="form-group">
                        <label for="telp" class="control-label">Nomer Handphone <span class="required">*</span></label>
                        <div class="input-group input-telp">
                            <div class="input-group-addon">
                                <img src="<?php echo base_url();?>assets/front/img/ind.png" class="img-fluid flag-ind lazyload"> <span>+62</span>
                            </div>
                            <input type="tel" name="telp" id="telp" class="form-control val-telp" placeholder="82140xxxxxx" autocomplete="off" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_lahir" class="control-label">Tanggal Lahir <span class="required">*</span></label>
                        <input type="text" name="tanggal_lahir" id="tanggal_lahir" data-masked="" data-inputmask="'mask': '99/99/9999'" placeholder="Masukan tanggal lahir Anda" class="form-control" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="jenis_kelamin" class="control-label">Jenis Kelamin <span class="required">*</span></label>
                        <select name="jenis_kelamin" id="jenis_kelamin" data-placeholder="Pilih Jenis Kelamin" data-allow-clear="false" class="form-control select-custome">
                            <option value="" readonly selected hidden>Pilih Jenis Kelamin</option>
                            <option value="Laki - Laki">Laki - Laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Pendidikan <span class="required">*</span></label>
                        <select name="pendidikan" id="pendidikan" data-placeholder="Pilih pendidikan" data-allow-clear="false" class="form-control select-custome-search pendidikan">
                            <option value="" selected disabled></option>
                            <?php foreach ($get_pend as $key_pend) { ?>
                                <option value="<?php echo $key_pend['id_pendidikan']; ?>"><?php echo $key_pend['nama']; ?></option>
                            <?php }; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="email" class="control-label">Email <span class="required">*</span></label>
                        <input type="text" name="email" id="email" placeholder="Masukan email Anda" class="form-control val-lowercase" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="password" class="control-label">Password <span class="required">*</span></label>
                        <div class="group-inner e-icon">
                            <input type="password" name="password" id="password-register" placeholder="Masukan password Anda" class="form-control" autocomplete="off">
                            <span toggle="#password-register" class="fa fa-eye-slash show-password"></span>
                        </div>
                    </div> 

                    <div class="form-group">
                        <label class="control-label">Darimana Anda mendapat informasi tentang SheHacks <span class="required">*</span></label>
                        <select name="dapat_informasi" id="dapat_informasi" data-placeholder="Pilih Informasi" data-allow-clear="false" class="form-control select-custome-search">
                            <option value="" selected disabled></option>
                            <?php foreach ($get_mdi as $key_mdi) { ?>
                                <option value="<?php echo $key_mdi['id_informasi']; ?>"><?php echo $key_mdi['nama']; ?></option>
                            <?php }; ?>
                        </select>
                    </div>

                    <div class="form-actions d-flex justify-content-between">
                        <button type="button" id="prev-form-personal" class="btn btn-light prev-step" data="1"><span>KEMBALI</span></button>
                        <button type="submit" id="submit-form-personal" class="btn"><span>SELANJUTNYA</span></button>
                    </div>
                </form>
            </div>
        </div>


        <!-- fieldset step -->
        <div class="wrap-step wrap-step-3 animated fadeIn d-none">
            <div class="auth-form">
                <div class="section-title">
                    <h2 class="title section-heading-sm">Create An Account</h2>
                    <p class="section-description-md"><span>Already have an account?</span> <a href="<?php echo base_url();?>login" class="hover-1 text-accent fw-500">Login</a></p>
                </div>

                <form method="post" id="form-startup" class="form-style">
                    <div class="form-group">
                        <label class="control-label">Pilih Provinsi <span class="required">*</span></label>
                        <select name="provinsi" id="provinsi" data-placeholder="Pilih provinsi" data-allow-clear="false" class="form-control select-custome-search provinsi">
                            <option value="" selected disabled></option>
                            <?php foreach ($get_prov as $key_prov) { ?>
                                <option value="<?php echo $key_prov['id']; ?>"><?php echo $key_prov['name']; ?></option>
                            <?php }; ?>
                        </select>
                    </div>

                    
                    <div class="wrap-ifoption ifoption-ideasi d-none">    
                        <div class="form-group">
                            <label for="problem_disekitar" class="control-label">Problem yang sedang dihadapi saat ini <span class="required">*</span></label>
                            <textarea name="problem_disekitar" id="problem_disekitar" rows="4" placeholder="Masukan jawaban Anda" class="form-control val-capitalize" autocomplete="off"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="solusi_yang_dibuat" class="control-label">Solusi yang ingin dibuat <span class="required">*</span></label>
                            <textarea name="solusi_yang_dibuat" id="solusi_yang_dibuat" rows="4" placeholder="Masukan jawaban Anda" class="form-control val-capitalize" autocomplete="off"></textarea>
                        </div>
                    </div>

                    
                    <div class="wrap-ifoption ifoption-mvp d-none">
                        <div class="form-group">
                            <label for="nama_startup" class="control-label">Nama Startup <span class="required">*</span></label>
                            <input type="text" name="nama_startup" id="nama_startup" placeholder="Masukan nama startup Anda" class="form-control val-capitalize">
                        </div>
                    </div>

                    <div class="wrap-ifoption ifoption-ideasi ifoption-mvp d-none">
                        <div class="form-group">
                            <label for="jumlah_anggota" class="control-label">Jumlah Anggota Tim/Karyawan <span class="required">*</span></label>
                            <div class="group-inner e-icon style-1">
                                <input type="number" name="jumlah_anggota" id="jumlah_anggota" placeholder="Isikan 0 jika tidak ada" class="form-control">
                                <span class="group-inner-icon">Orang</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="wrap-ifoption ifoption-mvp d-none">
                        <div class="form-group">
                            <label class="control-label">Upload Dokumen Profil Bisnis / Pitchdeck <span class="required">*</span> <br><span class="note"><i class="fa fa-circle-info me-1"></i>Maksimal ukuran <span class="fw-500">20MB</span>. Format file <span class="fw-500">PDF / PPT</span></span></label>
                            <input type="file" name="file_pitchdeck" id="file_pitchdeck" class="form-control file-input-custom" accept="application/pdf, application/vnd.ms-powerpoint">
                            <div class="file_pitchdeck_feed"></div>
                        </div>
                    </div>

                    <div class="wrap-ifoption ifoption-papeda d-none">
                        <div class="form-group">
                            <label for="kabupaten" class="control-label">Kota/Kabupaten <span class="required">*</span></label>
                            <select name="kabupaten" id="kabupaten" data-placeholder="Pilih kabupaten" data-allow-clear="false" class="form-control select-custome-search kabupaten">
                                <option value="" selected disabled></option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="nama_komunitas" class="control-label">Nama Komunitas</label>
                            <input type="text" name="nama_komunitas" id="nama_komunitas" placeholder="Masukan nama komunitas Anda" class="form-control val-capitalize">
                        </div>

                        <div class="form-group">
                            <label for="jumlah_anggota_komunitas" class="control-label">Jumlah Anggota Komunitas</label>
                            <div class="group-inner e-icon style-1">
                                <input type="number" name="jumlah_anggota_komunitas" id="jumlah_anggota_komunitas" placeholder="Masukan jumlah anggota komunitas" class="form-control">
                                <span class="group-inner-icon">Anggota</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="jabatan_komunitas" class="control-label">Jabatan dalam Komunitas</label>
                            <input type="text" name="jabatan_komunitas" id="jabatan_komunitas" placeholder="Masukan jabatan dalam komunitas Anda" class="form-control val-capitalize">
                        </div>

                        <div class="form-group">
                            <label for="akun_komunitas" class="control-label">Akun Media Sosial Komunitas</label>
                            <input type="text" name="akun_komunitas" id="akun_komunitas" placeholder="Masukan Akun media sosial komunitas" class="form-control val-capitalize">
                        </div>

                        <div class="form-group">
                            <label class="control-label">Upload Profil Komunitas / Portofolio <span class="required">*</span> 
                            <br><span class="note"><i class="fa fa-circle-info me-1"></i>Maksimal ukuran <span class="fw-500">20MB</span>. Format file <span class="fw-500">PDF / PPT</span></span></label>
                            <input type="file" name="file_profile_komunitas" id="file_profile_komunitas" class="form-control file-input-custom" accept="application/pdf, application/vnd.ms-powerpoint">
                            <div class="file_profile_komunitas_feed"></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Upload File pengajuan kegiatan daerah <span class="required">*</span> <br><span class="note"><i class="fa fa-circle-info me-1"></i>Maksimal ukuran <span class="fw-500">20MB</span>. Format file <span class="fw-500">PDF / PPT</span></span></label>
                            <input type="file" name="file_pengajuan_kegiatan" id="file_pengajuan_kegiatan" class="form-control file-input-custom" accept="application/pdf, application/vnd.ms-powerpoint">
                            <div class="file_pengajuan_kegiatan_feed"></div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label">Unggah File KTP <span class="required">*</span>
                                <br><span class="note"><i class="fa fa-circle-info me-1"></i>Maksimal ukuran <span class="fw-500">1.5MB</span>. Format file <span class="fw-500">PNG / JPG /JPEG</span></span>
                                <br><span class="note"><i class="fa fa-circle-info me-1"></i>Dengan mengunggah KTP, Anda menyetujui penggunaan data untuk keperluan verifikasi, dan Anda berhak meminta perubahan atau penghapusan data kapan saja melalui <a href="mailto:adela@kumpul.id" target="_blank">adela@kumpul.id</a> sesuai dengan ketentuan perlindungan data pribadi yang berlaku.</span>
                            </label>
                            <input type="file" name="file_analisa_skorlife" id="file_analisa_skorlife" class="form-control file-input-custom" accept="image/*">
                            <div class="file_analisa_skorlife_feed"></div>
                        </div>
                    </div>

                    <div class="form-check xform-check-inline custome-checkbox">
                        <input class="form-check-input" type="checkbox" name="agreement_check" id="agreement_check">
                        <label class="form-check-label section-description-sm" for="agreement_check">Saya setuju bahwa data pribadi saya akan dikumpulkan, disimpan, dan diproses untuk keperluan pelaksanaan program SheHacks sesuai dengan kebijakan privasi yang berlaku.</label>
                    </div>

                    <div class="form-actions d-flex justify-content-between">
                        <button type="button" id="prev-form-startup" class="btn btn-light prev-step" data="2"><span>KEMBALI</span></button>
                        <button type="submit" id="submit-form-startup" class="btn"><span>DAFTAR SEKARANG</span></button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>


<div class="modal modal-style fade" id="modal-success-registration" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" aria-labelledby="ModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="inner-modal py-5 text-center">
                <div class="icon mb-4">
                        <svg width="158" height="158" viewBox="0 0 158 158" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M106.894 21.8374C81.5953 11.7081 50.8933 17.4841 33.0538 37.1524L33.0523 37.154C24.2695 46.8323 18.7609 59.0376 17.3111 72.032C15.8613 85.0264 18.5441 98.1474 24.9775 109.527C31.411 120.906 41.2671 129.964 53.1424 135.41C65.0178 140.856 78.307 142.413 91.1179 139.859L91.1201 139.859C116.896 134.728 136.107 111.399 141.235 85.734C142.152 81.1459 146.611 78.1704 151.194 79.088C155.778 80.0056 158.751 84.4689 157.834 89.057C151.677 119.872 128.344 149.725 94.4218 156.478C78.1177 159.727 61.2051 157.745 46.0917 150.814C30.9776 143.883 18.4335 132.355 10.2455 117.872C2.05744 103.389 -1.35706 86.6897 0.488163 70.1513C2.3333 53.6137 9.34364 38.0803 20.521 25.7627M20.5225 25.7611C43.6251 0.291737 82.0543 -6.35739 113.18 6.10521C117.52 7.84293 119.631 12.7734 117.895 17.1177C116.159 21.462 111.234 23.5751 106.894 21.8374" fill="#EC008C"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M155.273 24.2625C158.713 27.4583 158.927 32.8557 155.752 36.3179L87.9524 110.255C86.388 111.961 84.2004 112.952 81.8944 112.998C79.5884 113.045 77.3632 112.143 75.7323 110.501L47.4823 82.0638C44.1726 78.7321 44.1726 73.3304 47.4823 69.9988C50.792 66.6671 56.158 66.6671 59.4677 69.9988L81.4805 92.1576L143.298 24.7447C146.472 21.2826 151.834 21.0667 155.273 24.2625Z" fill="#EC008C"/>
                        </svg>
                    </svg>
                </div>

                <h4 class="section-heading-sm text-second2">Sukses Mendaftar</h4>
                <p class="section-description-md">Terima kasih telah melengkapi informasi pendaftaran. Silahkan cek email terdaftar di <span class="fw-600">Kotak Masuk / Folder Spam</span> untuk melakukan verifikasi akun Anda.</p>
                <!-- <a href="<?php echo base_url();?>" class="btn btn-accent mt-3">Home</a> -->
            </div>
        </div>
    </div>
</div>

<script defer src="<?php echo base_url();?>assets/front/vendor/select2/js/select2.min.js"></script>
<script defer src="<?php echo base_url();?>assets/front/vendor/input-file/input-file.js"></script>
<!-- <script defer src="<?php echo base_url();?>assets/front/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script> -->
<script defer src="<?php echo base_url();?>assets/front/vendor/input-mask/input-mask.min.js"></script>

<script src="<?php echo base_url();?>assets/front/js/function.js"></script>

<script>
    $(document).ready(function() {

        // $('#modal-success-registration').modal('show');
        
        // --------------------------- handle register step ---------------------------
            var scrollTop = function() {
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            }

            $('.prev-step').on('click', function() {
                var step = $(this).attr('data');
                prev_step(step);
            });

            function next_step(step)
            {
                scrollTop();
                
                $('.nav-step-item').removeClass('active');
                $('.nav-step-item-'+step).addClass('active');

                $('.wrap-step').addClass('d-none');
                $('.wrap-step-'+step).removeClass('d-none');

                $('#feedback-register').html('');
            }

            function prev_step(step)
            {
                scrollTop();

                $('.nav-step-item').removeClass('active');
                $('.nav-step-item-'+step).addClass('active');

                $('.wrap-step').addClass('d-none');
                $('.wrap-step-'+step).removeClass('d-none');

                $('#feedback-register').html('');
            }


            $('input[name*="kategori_user"]').on('change', function() {
                var val = $(this).val();
                
                if (val == 'Ideasi') {
                    $('.wrap-ifoption').addClass('d-none');
                    $('.ifoption-ideasi').removeClass('d-none');
                } else if (val == 'MVP') {
                    $('.wrap-ifoption').addClass('d-none');
                    $('.ifoption-mvp').removeClass('d-none');
                } else {
                    $('.wrap-ifoption').addClass('d-none');
                    $('.ifoption-papeda').removeClass('d-none');
                }
            });
        // --------------------------- end handle register step ---------------------------


        // --------------------------- register step 1 ---------------------------
            var validate_form_profile = $('#form-profile').validate({
                rules: {
                    kategori_user: {
                        required: true
                    },
                },
                messages: {
                    kategori_user: {
                        required: 'Silahkan pilih profil Anda.'
                    },
                },
                errorElement: 'em',
                errorClass: 'has-error',
                highlight: function(element, errorClass) {
                    $(element).parent().addClass('has-error')
                    $(element).addClass('has-error')
                },
                unhighlight: function(element, errorClass) {
                    $(element).parent().removeClass('has-error')
                    $(element).removeClass('has-error')
                },
                errorPlacement: function(error, element) {
                    if(element.is('input[name=kategori_user]')) {
                        error.insertAfter('.feedback-kategori-user');
                        error.addClass('has-error mb-3 text-center d-block');
                    } 
                    else {
                        error.appendTo(element.parent());
                    }
                }
            });
            
            $('#form-profile').submit(function(e) {
                e.preventDefault();

                if (validate_form_profile.valid()) {
                    $('#submit-form-profile').buttonLoader('start');

                    $.ajax({
                        method   : 'post',
                        url      : '<?php echo base_url(); ?>auth/register/post_register_profile',
                        data     : new FormData(this),
                        dataType : 'json',
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $('#submit-form-profile').buttonLoader('stop');

                            if (response.status == 1) {
                                trigger_cta_event('Register Step 1');
                                next_step(2);
                            } 
                            else {
                                $('#feedback-register').alertNotification('danger', response.message);
                            }

                            scrollTop();
                        }
                    })
                }
            });
        // --------------------------- end register step 1 ---------------------------


        // --------------------------- register step 2 ---------------------------
            // cek date
            $.validator.addMethod('cek_date', function(value, element) {
                if (dateValidator(element) == true) {
                    return true;
                } else {
                    return false;
                }
            });

            function dateValidator(ele) {
                var dateRegex = /^(?=\d)(?:(?:31(?!.(?:0?[2469]|11))|(?:30|29)(?!.0?2)|29(?=.0?2.(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(?:\x20|$))|(?:2[0-8]|1\d|0?[1-9]))([-.\/])(?:1[012]|0?[1-9])\1(?:1[6-9]|[2-9]\d)?\d\d(?:(?=\x20\d)\x20|$))?(((0?[1-9]|1[012])(:[0-5]\d){0,2}(\x20[AP]M))|([01]\d|2[0-3])(:[0-5]\d){1,2})?$/;
                return dateRegex.test(ele.value); 
            }

            // cek password
            $.validator.addMethod('cek_password', function(value, element) {

                var re = /^(?=.*\d)(?=.*[@#$%^&*?~;+\(/):_|{}[-])(?=.*[^!])(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
                var sr = /^[^!]*$/;

                if (re.test(value) == true) {
                    if (sr.test(value) == true) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            });

            var validate_form_personal = $('#form-personal').validate({
                rules: {
                    nama: {
                        required: true
                    },
                    tanggal_lahir: {
                        required: true,
                        cek_date: true
                    },
                    pendidikan: {
                        required: true
                    },
                    telp: {
                        required: true,
                        maxlength: 12
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    password: {
                        required: true,
                        cek_password: true
                    },
                    dapat_informasi: {
                        required: true
                    },
                    jenis_kelamin: {
                        required: true
                    },
                },
                messages: {
                    nama: {
                        required: 'Nama lengkap harus diisi.'
                    },
                    tanggal_lahir: {
                        required: 'Tanggal lahir harus diisi.',
                        cek_date: 'Masukkan tanggal lahir dengan benar.'
                    },
                    pendidikan: {
                        required: 'pendidikan harus dipilih.'
                    },
                    telp: {
                        required: 'Nomor telepon harus diisi.',
                        maxlength: 'Masukkan tidak lebih dari 12 karakter.',
                    },
                    email: {
                        required: 'Alamat email harus diisi.',
                        email: 'Masukkan alamat email dengan benar.',
                    },
                    password: {
                        required: 'Password harus diisi.',
                        cek_password: 'Password harus memiliki minimal 8 karakter yang terdiri dari kombinasi huruf besar, huruf kecil, angka dan symbol (kecuali !=<>"]).'
                    },
                    dapat_informasi: {
                        required: 'Silahkan pilih jawaban Anda.'
                    },
                    jenis_kelamin: {
                        required: 'Silahkan pilih Jenis Kelamin Anda.'
                    },
                },
                errorElement: 'em',
                errorClass: 'has-error',
                highlight: function(element, errorClass) {
                    $(element).parent().addClass('has-error')
                    $(element).addClass('has-error')
                },
                unhighlight: function(element, errorClass) {
                    $(element).parent().removeClass('has-error')
                    $(element).removeClass('has-error')
                },
                errorPlacement: function(error, element) {
                    if(element.parent('.input-group').length) {
                        error.insertAfter(element.parent('.input-group'));
                    } 
                    else if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                        var controls = element.closest('div[class*="form-group"]');
                        if(controls.find(':checkbox,:radio').length > 1) 
                            controls.append(error);
                        else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                        error.addClass('has-error');
                    }
                    else {
                        error.appendTo(element.parent());
                    }
                }
            });

            $('#form-personal').submit(function(e) {
                e.preventDefault();

                if (validate_form_personal.valid()) {
                    $('#submit-form-personal').buttonLoader('start');

                    $.ajax({
                        method   : 'post',
                        url      : '<?php echo base_url(); ?>auth/register/post_register_personal',
                        data     : new FormData(this),
                        dataType : 'json',
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $('#submit-form-personal').buttonLoader('stop');

                            if (response.status == 1) {
                                trigger_cta_event('Register Step 2');
                                next_step(3);
                            } 
                            else {
                                $('#feedback-register').alertNotification('danger', response.message);
                            }
                        }
                    })
                }
            });
        // --------------------------- end register step 2 ---------------------------


        // --------------------------- register step 3 ---------------------------
            var validate_form_startup = $('#form-startup').validate({
                rules: {
                    provinsi: {
                        required: true
                    },
                    kabupaten: {
                        required: true
                    },
                    problem_disekitar: {
                        required: true
                    },
                    solusi_yang_dibuat: {
                        required: true
                    },
                    nama_startup: {
                        required: true
                    },
                    jumlah_anggota: {
                        required: true
                    },
                    file_pitchdeck: {
                        required: true,
                        extension: 'pdf|ppt|pptx',
                        filesize: 20000000
                    },
                    file_pengajuan_kegiatan: {
                        required: true,
                        extension: 'pdf|ppt|pptx',
                        filesize: 20000000
                    },
                    file_profile_komunitas: {
                        required: true,
                        extension: 'pdf|ppt|pptx',
                        filesize: 20000000
                    },
                    file_analisa_skorlife: {
                        required: true,
                        extension: 'jpg|jpeg|png|webp',
                        filesize: 1600000
                    },
                    agreement_check: {
                        required: true,
                    }
                },
                messages: {
                    provinsi: {
                        required: 'Silahkan pilih provinsi.'
                    },
                    kabupaten: {
                        required: 'Silahkan pilih kabupaten.'
                    },
                    problem_disekitar: {
                        required: 'Silahkan masukan jawaban Anda.'
                    },
                    solusi_yang_dibuat: {
                        required: 'Silahkan masukan jawaban Anda.'
                    },
                    nama_startup: {
                        required: 'Nama startup harus diisi.'
                    },
                    jumlah_anggota: {
                        required: 'Jumlah tim harus diisi.'
                    },
                    file_pitchdeck: {
                        required: 'Unggah dokumen profil bisnis Anda.',
                        extension: 'Unggah file dengan format .PDF / .PPT',
                        filesize: 'File maksimal 20 Mb.'
                    },
                    file_pengajuan_kegiatan: {
                        required: 'Unggah dokumen pengajuan kegiatan Anda.',
                        extension: 'Unggah file dengan format .PDF / .PPT',
                        filesize: 'File maksimal 20 Mb.'
                    },
                    file_profile_komunitas: {
                        required: 'Unggah dokumen Profil Komunitas / Portofolio.',
                        extension: 'Unggah file dengan format .PDF / .PPT',
                        filesize: 'File maksimal 20 Mb.'
                    },
                    file_analisa_skorlife: {
                        required: 'Unggah file KTP Anda.',
                        extension: 'Unggah file dengan format .jpg/.jpeg/.png/.webp',
                        filesize: 'File maksimal 1.5 Mb.'
                    },
                    agreement_check: {
                        required: ''
                    }
                },
                errorElement: 'em',
                errorClass: 'has-error',
                highlight: function(element, errorClass) {
                    $(element).parent().addClass('has-error')
                    $(element).addClass('has-error')
                },
                unhighlight: function(element, errorClass) {
                    $(element).parent().removeClass('has-error')
                    $(element).removeClass('has-error')
                },
                errorPlacement: function(error, element) {
                    if(element.parent('.input-group').length) {
                        error.insertAfter(element.parent('.input-group'));
                    } 
                    else if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                        var controls = element.closest('div[class*="form-group"]');
                        if(controls.find(':checkbox,:radio').length > 1) 
                            controls.append(error);
                        else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                        error.addClass('has-error');
                    } 
                    else if(element.is('input[name="file_pitchdeck"]')) {
                        error.insertAfter('.file_pitchdeck_feed');
                    }
                    else if(element.is('input[name="file_pengajuan_kegiatan"]')) {
                        error.insertAfter('.file_pengajuan_kegiatan_feed');
                    }
                    else if(element.is('input[name="file_profile_komunitas"]')) {
                        error.insertAfter('.file_profile_komunitas_feed');
                    }
                    else if(element.is('input[name="file_analisa_skorlife"]')) {
                        error.insertAfter('.file_analisa_skorlife_feed');
                    }
                    else {
                        error.appendTo(element.parent());
                    }
                }
            });

            $('#form-startup').submit(function(e) {
                e.preventDefault();

                if (validate_form_startup.valid()) {
                    $('#submit-form-startup').buttonLoader('start');

                    $.ajax({
                        method   : 'post',
                        url      : '<?php echo base_url(); ?>auth/register/post_register_startup',
                        data     : new FormData(this),
                        dataType : 'json',
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $('#submit-form-startup').buttonLoader('stop');

                            if (response.status == 1) {
                                trigger_cta_event('Register Step 3');

                                // Facebook Pixel: track CompleteRegistration event
                                fbq('track', 'CompleteRegistration');

                                $('#form-personal')[0].reset();
                                $('#form-startup')[0].reset();
                                $('#modal-success-registration').modal('show');

                                setTimeout(function() {
                                    top.location.href = '<?php echo base_url();?>login-verify';
                                }, 1500);
                                
                            } 
                            else {
                                $('#feedback-register').alertNotification('danger', response.message);
                                scrollTop()
                            }
                        }
                    })
                }
            });
        // --------------------------- end register step 3 ---------------------------


        // --------------------------- handle address ---------------------------
            $(document).on('change', '.provinsi', function() {
                var id = $(this).val();

                if (id) {
                
                    $.ajax({
                        url  : '<?php echo base_url();?>home/get_address',
                        data : { id:id, param:'provinsi' },
                        dataType : 'json',
                        success: function(response) {
                            $('.kabupaten').html(response.data);
                        }
                    });
                }
            });
        // --------------------------- end handle address ---------------------------

        new InputFile({
            buttonText: 'Pilih File',
            hint: 'atau tahan & letakkan file disini.',
        });

        $('#tanggal_lahir').inputmask('00/00/0000');
        // $('#tanggal_lahir').inputmask();

    });
</script>