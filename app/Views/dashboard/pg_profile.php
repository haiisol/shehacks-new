<div class="card card-style">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-5">
                <div class="section-title">
                    <h2 class="title">Profile</h2>
                    <p class="section-description-md">Masukkan informasi yang valid</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 m-lg-0 m-auto">

                <div id="feedback-profile" class="mb-3"></div>

                <form method="post" id="form-profile-update" class="form-style">
                    <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="row wrap-option-pilihan mb-3">
                                <div class="col-4">
                                    <div class="radio-style">
                                        <input type="radio" name="kategori_user_pilihan" id="kategori_user1" value="Ideasi" class="radio-button">

                                        <label for="kategori_user1" class="radio-tile">
                                            <div class="radio-box">
                                                <img src="<?php echo base_url();?>assets/front/img/icon/ideasi-icon.webp" alt="icon ideasi" class="img-fluid lazyload">
                                            </div>
                                            <p class="radio-text section-description">Ideasi</p>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="radio-style">
                                        <input type="radio" name="kategori_user_pilihan" id="kategori_user2" value="MVP" class="radio-button">

                                        <label for="kategori_user2" class="radio-tile">
                                            <div class="radio-box">
                                                <img src="<?php echo base_url();?>assets/front/img/icon/mvp-icon.webp" alt="icon mvp" class="img-fluid lazyload">
                                            </div>
                                            <p class="radio-text section-description">MVP</p>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="radio-style">
                                        <input type="radio" name="kategori_user_pilihan" id="kategori_user3" value="Pandu Perempuan Daerah" class="radio-button">

                                        <label for="kategori_user3" class="radio-tile">
                                            <div class="radio-box">
                                                <img src="<?php echo base_url();?>assets/front/img/icon/papeda-icon.webp" alt="icon Pandu Perempuan Daerah" class="img-fluid lazyload">
                                            </div>
                                            <p class="radio-text section-description">Pandu Perempuan Daerah</p>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="feedback-kategori-user"></div>

                            <div class="form-group">
                                <label for="nama" class="control-label">Nama Lengkap <span class="required">*</span></label>
                                <input type="text" name="nama" id="nama" placeholder="Masukan nama lengkap Anda" class="form-control val-capitalize">
                            </div>

                            <div class="form-group">
                                <label for="telp" class="control-label">Nomer Telepon <span class="required">*</span></label>
                                <div class="input-group input-telp">
                                    <div class="input-group-addon">
                                        <img data-src="<?php echo base_url();?>assets/front/img/ind.png" class="img-fluid flag-ind lazyload"> <span>+62</span>
                                    </div>
                                    <input type="tel" name="telp" id="telp" class="form-control val-telp" placeholder="82140xxxxxx" autocomplete="off" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="control-label">Email <span class="required">*</span></label>
                                <input type="text" name="email" id="email" placeholder="Masukan email Anda" class="form-control val-lowercase" autocomplete="off" disabled>
                            </div>

                        </div>

                        <div class="col-lg-5 wrap-not">
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
                                    <?php $get_pend = $this->main_model->get_data_order("tb_master_pendidikan", "nama DESC"); ?>
                                    <?php foreach ($get_pend as $key_pend) { ?>
                                        <option value="<?php echo $key_pend['id_pendidikan']; ?>"><?php echo $key_pend['nama']; ?></option>
                                    <?php }; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Pilih Provinsi <span class="required">*</span></label>
                                <select name="provinsi" id="provinsi" data-placeholder="Pilih lokasi" data-allow-clear="false" class="form-control select-custome-search provinsi">
                                    <option value="" selected disabled></option>
                                    <?php $get_prov = $this->main_model->get_data_order("tb_master_province", "name ASC"); ?>
                                    <?php foreach ($get_prov as $key_prov) { ?>
                                        <option value="<?php echo $key_prov['id']; ?>"><?php echo $key_prov['name']; ?></option>
                                    <?php }; ?>
                                </select>
                            </div>

                            <div class="wrap-ifoption wrap-ifoption-papeda d-none">
                                <div class="form-group">
                                    <label for="kabupaten" class="control-label">Kota/Kabupaten <span class="required">*</span></label>
                                    <select name="kabupaten" id="kabupaten" data-placeholder="Pilih kabupaten" data-allow-clear="false" class="form-control select-custome-search kabupaten">
                                        <option value="" selected disabled></option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="form-group wrap-not">
                            <label class="control-label">Darimana Anda mendapat informasi tentang SheHacks <span class="required">*</span></label>
                            <select name="dapat_informasi" id="dapat_informasi" data-placeholder="Pilih Informasi" data-allow-clear="false" class="form-control select-custome-search provinsi">
                                <option value="" selected disabled></option>
                                <?php $get_mdi = $this->main_model->get_data_order("tb_master_dapat_informasi", "urutan ASC"); ?>
                                <?php foreach ($get_mdi as $key_mdi) { ?>
                                    <option value="<?php echo $key_mdi['id_informasi']; ?>"><?php echo $key_mdi['nama']; ?></option>
                                <?php }; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row wrap-not">
                        <div class="wrap-ifoption wrap-ifoption-ideasi d-none">    
                            <div class="form-group">
                                <label for="problem_disekitar" class="control-label">Problem yang sedang dihadapi saat ini <span class="required">*</span></label>
                                <textarea name="problem_disekitar" id="problem_disekitar" rows="4" placeholder="Masukan jawaban Anda" class="form-control val-capitalize" autocomplete="off"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="solusi_yang_dibuat" class="control-label">Solusi yang ingin dibuat <span class="required">*</span></label>
                                <textarea name="solusi_yang_dibuat" id="solusi_yang_dibuat" rows="4" placeholder="Masukan jawaban Anda" class="form-control val-capitalize" autocomplete="off"></textarea>
                            </div>
                        </div>

                        <div class="wrap-ifoption wrap-ifoption-mvp d-none">
                            <div class="form-group">
                                <label for="nama_startup" class="control-label">Nama Startup <span class="required">*</span></label>
                                <input type="text" name="nama_startup" id="nama_startup" placeholder="Masukan nama startup Anda" class="form-control val-capitalize">
                            </div>
                        </div>
                    </div>

                    <div class="wrap-ifoption wrap-ifoption-ideasi wrap-ifoption-mvp d-none">
                        <div class="form-group">
                            <label for="jumlah_anggota" class="control-label">Jumlah Tim <span class="required">*</span></label>
                            <div class="group-inner e-icon style-1">
                                <input type="number" name="jumlah_anggota" id="jumlah_anggota" placeholder="Isikn 0 jika tidak ada" class="form-control">
                                <span class="group-inner-icon">Orang</span>
                            </div>
                        </div>
                    </div>

                    <div class="wrap-ifoption wrap-ifoption-mvp d-none">
                        <div class="form-group">
                            <label class="control-label">Upload Dokumen Profil Bisnis / Pitchdeck <span class="required">*</span> <br><span class="note"><i class="fa fa-circle-info me-1"></i>Maksimal ukuran <span class="fw-500">20MB</span>. Format file <span class="fw-500">PDF / PPT</span></span></label>
                            <input type="file" name="file_pitchdeck" id="file_pitchdeck" class="form-control file-input-custom" accept="application/pdf, application/vnd.ms-powerpoint">
                            <div class="file_pitchdeck_feed"></div>
                        </div>
                    </div>

                    <div class="wrap-ifoption wrap-ifoption-papeda d-none">
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
                            <label class="control-label">Upload File Profil Komunitas <br><span class="note"><i class="fa fa-circle-info me-1"></i>Maksimal ukuran <span class="fw-500">20MB</span>. Format file <span class="fw-500">PDF / PPT</span></span></label>
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
                                <br><span class="note"><i class="fa fa-circle-info me-1"></i>Dengan mengunggah KTP, Anda menyetujui penggunaan data untuk keperluan verifikasi, dan Anda berhak meminta perubahan atau penghapusan data kapan saja melalui <a href="mailto:adela@kumpul.id" target="_blank">adela@kumpul.id</a> sesuai dengan ketentuan perlindungan.</span>
                            </label>
                            <input type="file" name="file_analisa_skorlife" id="file_analisa_skorlife" class="form-control file-input-custom" accept="image/*">
                            <div class="file_analisa_skorlife_feed"></div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <input type="hidden" name="kategori_user" id="kategori_user">
                        <input type="hidden" name="token_user" id="token_user">
                        <button type="submit" id="btn-submit-profile" class="btn"><span>Simpan</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script defer src="<?php echo base_url();?>assets/front/vendor/select2/js/select2.min.js"></script>
<script defer src="<?php echo base_url();?>assets/front/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script defer src="<?php echo base_url();?>assets/front/vendor/input-mask/input-mask.min.js"></script>
<script defer src="<?php echo base_url();?>assets/front/vendor/input-file/input-file.js"></script>

<script src="<?php echo base_url();?>assets/front/js/function.js"></script>

<script type="text/javascript">
	$(document).ready(function () {

        $('#tanggal_lahir').inputmask();

        // validation phone
        $('.val-telp').on('input propertychange paste', function (e) {
            var val = $(this).val()
            var reg = /^0/gi;
            if (val.match(reg)) {
                $(this).val(val.replace(reg, ''));
            }
        });
        
        // validation capitalize
        $('.val-capitalize').on('input propertychange paste', function (e) {
            var str = $(this).val()
            str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            });
            $(this).val(str);
        });

        // validation lowercase
        $('.val-lowercase').on('input propertychange paste', function (e) {
            var str = $(this).val()
            str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toLowerCase();
            });
            $(this).val(str);
        });


		// --------------------------- profile ---------------------------
            load_data_profile();

            function load_data_profile() 
            {
                $.ajax({
                    url      : '<?php echo base_url();?>dashboard/dashboard/fetch_data_profile',
                    dataType : 'json',
                    success: function(response) {

                        // data personal
                        $('#nama').val(response.nama);
                        $('#token_user').val(response.token);
                        $('#kategori_user').val(response.kategori_user);
                        $('#telp').val(response.telp);
                        $('#email').val(response.email);
                        $('#tanggal_lahir').val(response.tanggal_lahir);
                        $('#pendidikan').select2().val(response.pendidikan).trigger('change.select2');
                        $('#jenis_kelamin').select2().val(response.jenis_kelamin).trigger('change.select2');

                        $('.datepicker').datepicker({
                            format: 'yyyy-mm-dd',
                            todayHighlight: false,
                            autoclose: true,
                            endDate: 'today',
                            beforeShowDay: function(date) {
                                if($.inArray(date.toString(), response.tanggal_lahir)) {
                                    return [true, 'css-class-to-highlight', 'tooltip text'];
                                } else {
                                    return [true, '', ''];
                                }
                            }
                        }); 
                        
                        if (response.kategori_user == 'MVP') {
                            $('.wrap-option-pilihan').addClass('d-none');
                            $('.wrap-ifoption').addClass('d-none');
                            $('.wrap-ifoption-mvp').removeClass('d-none');

                            // data startup
                            $('#nama_startup').val(response.nama_startup);
                            $('#jumlah_anggota').val(response.jumlah_anggota);
                        }
                        else if (response.kategori_user == 'Ideasi') 
                        {   
                            $('.wrap-option-pilihan').addClass('d-none');
                            $('.wrap-ifoption').addClass('d-none');
                            $('.wrap-ifoption-ideasi').removeClass('d-none');

                            // data ideasi
                            $('#problem_disekitar').val(response.problem_disekitar);
                            $('#solusi_yang_dibuat').val(response.solusi_yang_dibuat);
                            $('#jumlah_anggota').val(response.jumlah_anggota);

                        } else if (response.kategori_user == 'Pandu Perempuan Daerah') 
                        {   
                            $('.wrap-option-pilihan').addClass('d-none');
                            $('.wrap-ifoption').addClass('d-none');
                            $('.wrap-ifoption-papeda').removeClass('d-none');

                            $('#nama_komunitas').val(response.nama_komunitas);
                            $('#jumlah_anggota_komunitas').val(response.jumlah_anggota_komunitas);
                            $('#jabatan_komunitas').val(response.jabatan_komunitas);
                            $('#akun_komunitas').val(response.akun_komunitas);

                        } else {
                            $('.wrap-ifoption-mvp').addClass('d-none');
                            $('.wrap-ifoption-ideasi').addClass('d-none');
                        }

                        $('#dapat_informasi').select2().val(response.dapat_informasi).trigger('change.select2');
                        $('#provinsi').select2().val(response.provinsi).trigger('change.select2');

                        if (response.provinsi != 0) {
                            setTimeout(function() { get_kab(); }, 500);
                            if (response.kabupaten != 0) {
                                setTimeout(function() { $('#kabupaten').select2().val(response.kabupaten).trigger('change.select2'); }, 1000);
                            } 
                        }
                    
                        if (response.file_pitchdeck) {
                            var file_pitchdeck = '<a href="'+response.url_file_pitchdeck+'" target="_blank" class="position-relative link hover-1 section-description-sm fw-500 text-second2" style="z-index: 999;">Lihat File</a>';
                            $('#file_pitchdeck').before(file_pitchdeck);
                        }

                        if (response.file_profile_komunitas) {
                            var file_profile_komunitas = '<a href="'+response.url_file_profile_komunitas+'" target="_blank" class="position-relative link hover-1 section-description-sm fw-500 text-second2" style="z-index: 999;">Lihat File</a>';
                            $('#file_profile_komunitas').before(file_profile_komunitas);
                        }

                        if (response.file_analisa_skorlife) {
                            var file_analisa_skorlife = '<a href="'+response.url_file_analisa_skorlife+'" target="_blank" class="position-relative link hover-1 section-description-sm fw-500 text-second2" style="z-index: 999;">Lihat File</a>';
                            $('#file_analisa_skorlife').before(file_analisa_skorlife);
                        }

                        if (response.file_pengajuan_kegiatan) {
                            var file_pengajuan_kegiatan = '<a href="'+response.url_file_pengajuan_kegiatan+'" target="_blank" class="position-relative link hover-1 section-description-sm fw-500 text-second2" style="z-index: 999;">Lihat File</a>';
                            $('#file_pengajuan_kegiatan').before(file_pengajuan_kegiatan);
                        }
                    }
                });
            }

            $('input[name*="kategori_user_pilihan"]').on('change', function() {
                var val = $(this).val();

                if (val == 'Ideasi') {
                    $('.wrap-ifoption').addClass('d-none');
                    $('.wrap-ifoption-ideasi').removeClass('d-none');
                } else if (val == 'MVP') {
                    $('.wrap-ifoption').addClass('d-none');
                    $('.wrap-ifoption-mvp').removeClass('d-none');
                } else {
                    $('.wrap-ifoption').addClass('d-none');
                    $('.wrap-ifoption-papeda').removeClass('d-none');

                    setTimeout(function() { get_kab(); }, 1000);
                }
            });
            
            // cek phone
            // $.validator.addMethod('cek_phone', function(value, element) {
            //     $.ajax({
            //         url    : '<?php echo base_url();?>dashboard/dashboard/cek_phone',
            //         data   : { value:value },
            //         dataType : 'json',
            //         success: function(response) {
            //             if(response.status == 1) {
            //                 result = true;
            //             } else {
            //                 result = false;
            //             }
            //         },
            //         async: false
            //     });
            //     return result;
            // });

            // cek file pitchdeck
            $.validator.addMethod('cek_file_pitchdeck', function(value, element) {
                var result = true;
                $.ajax({
                    url    : '<?php echo base_url();?>dashboard/dashboard/cek_file_pitchdeck',
                    dataType : 'json',
                    success: function(response) {
                        if (response.status == 0) {
                            result = false;
                        } 
                    }
                });

                return result;
            });

            $.validator.addMethod('cek_file_pengajuan_kegiatan', function(value, element) {
                var result = true;
                $.ajax({
                    url    : '<?php echo base_url();?>dashboard/dashboard/cek_file_pengajuan_kegiatan',
                    dataType : 'json',
                    success: function(response) {
                        if (response.status == 0) {
                            result = false;
                        } 
                    },
                    async: false
                });

                return result;
            });

            var validate_form_profile = $('#form-profile-update').validate({
                rules: {
                    nama: {
                        required: true
                    },
                    telp: {
                        required: true,
                        maxlength: 12,
                        // cek_phone: true
                    },
                    tanggal_lahir: {
                        required: true
                    },
                    pendidikan: {
                        required: true
                    },
                    problem_disekitar: {
                        required: true
                    },
                    solusi_yang_dibuat: {
                        required: true
                    },
                    dapat_informasi: {
                        required: true
                    },
                    nama_startup: {
                        required: true
                    },
                    jumlah_anggota: {
                        required: true
                    },
                    provinsi: {
                        required: true
                    },
                    kabupaten: {
                        required: true
                    },
                    file_pitchdeck: {
                        extension: 'pdf|ppt|pptx',
                        filesize: 20000000,
                        cek_file_pitchdeck: false
                    },
                    file_pengajuan_kegiatan: {
                        extension: 'pdf|ppt|pptx',
                        filesize: 20000000,
                        cek_file_pengajuan_kegiatan: false
                    },
                    kategori_user_pilihan: {
                        required: true
                    },
                },
                messages: {
                    nama: {
                        required: 'Nama lengkap harus diisi.'
                    },
                    telp: {
                        required: 'Nomor telepon harus diisi.',
                        maxlength: 'Masukkan tidak lebih dari 12 karakter.',
                        cek_phone: 'Nomor telepon telah terdaftar.'
                    },
                    tanggal_lahir: {
                        required: 'Tanggal lahir harus diisi.'
                    },
                    pendidikan: {
                        required: 'Pilih pendidikan terakhir.'
                    },
                    problem_disekitar: {
                        required: 'Problem disekitar harus diisi.'
                    },
                    solusi_yang_dibuat: {
                        required: 'Solusi yang dibuat harus diisi.'
                    },
                    dapat_informasi: {
                        required: 'Dari mana mendapatkan informasi harus diisi.'
                    },
                    nama_startup: {
                        required: 'Nama startup harus diisi.'
                    },
                    jumlah_anggota: {
                        required: 'Jumlah tim harus diisi.'
                    },
                    provinsi: {
                        required: 'Pilih provinsi Anda.'
                    },
                    kabupaten: {
                        required: 'Silahkan pilih kabupaten.'
                    },
                    file_pitchdeck: {
                        extension: 'Unggah file dengan format .PDF / .PPT',
                        filesize: 'File maksimal 20 Mb.',
                        cek_file_pitchdeck: 'Unggah dokumen profil bisnis Anda.',
                    },
                    file_pengajuan_kegiatan: {
                        extension: 'Unggah file dengan format .PDF / .PPT',
                        filesize: 'File maksimal 20 Mb.',
                        cek_file_pengajuan_kegiatan: 'Unggah dokumen pengajuan kegiatan Anda.',
                    },
                    kategori_user_pilihan: {
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
                    if(element.parent('.input-group').length) {
                        error.insertAfter(element.parent('.input-group'));
                    } 
                    else if(element.is('input[name=kategori_user_pilihan]')){
                        error.insertAfter('.feedback-kategori-user');
                        error.addClass('has-error mb-3 text-center d-block');
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
                    else {
                        error.appendTo(element.parent());
                    }
                }
            });

            $('#form-profile-update').submit(function(e) {
                e.preventDefault();

                if (validate_form_profile.valid()) {
                    $('#btn-submit-profile').buttonLoader('start');

                    $.ajax({
                        method   : 'post',
                        url      : '<?php echo base_url(); ?>dashboard/dashboard/post_update_profile',
                        data     : new FormData(this),
                        dataType : 'json',
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.status == 1) {
                                trigger_cta_event('Profil Update Form');
                                load_data_profile();
                                $('#feedback-profile').alertNotification('success', response.message);
                            } 
                            else {
                                $('#feedback-profile').alertNotification('danger', response.message);
                            }
                            
                            $('#btn-submit-profile').buttonLoader('stop');
                            scrollTop();
                        }
                    })
                }
            });
        // --------------------------- end profile ---------------------------


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

            function get_kab()
            {
                var provinsi = $('#provinsi').val();

                if (provinsi) {
                
                    $.ajax({
                        url  : '<?php echo base_url();?>home/get_address',
                        data : { id:provinsi, param:'provinsi' },
                        dataType : 'json',
                        success: function(response) {
                            $('.kabupaten').html(response.data);
                        }
                    });
                }
            }
        // --------------------------- end handle address ---------------------------


        new InputFile({
            buttonText: 'Pilih File',
            hint: 'atau tahan & letakkan file disini.',
		});

        var scrollTop = function() {
            $('html, body').animate({ scrollTop: 0 }, 'slow');
        }
        
        // lazyload
        $('.lazyload').Lazy({
            effect: 'fadeIn',
            effectTime: 500,
            threshold: 0,
            visibleOnly: true,
            combined: true,
            delay: 5000
        });

    });
</script>