<div class="card card-style">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2 class="title">Pilot Project</h2>
                    <p class="section-description-md">Pilot Project merupakan proyek yang dirancang bersama mitra untuk menguji respons pasar dan menganalisis dampak yang dihasilkan dari proyek tersebut. Silakan cek contoh pada link berikut</p>
                    <a href="https://drive.google.com/file/d/19mNY3k6aWTi9sMh9_CxwmhHnYXP18VQ1/view?usp=sharing" target="_blank" class="btn btn-accent" data="Contoh Rencana Pilot Project"><span>Contoh Rencana Pilot Project</span></a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 m-lg-0 m-auto">

                <div id="feedback-profile" class="mb-3"></div>

                <form method="post" id="form-profile-update" class="form-style">
                    <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">

                    <div class="row">
                        <div class="">    
                            <div class="form-group">
                                <label for="pp_background_masalah" class="control-label">Background Masalah <span class="required">*</span></label>
                                <textarea name="pp_background_masalah" id="pp_background_masalah" rows="4" placeholder="Masukan background masalah" class="form-control" autocomplete="off"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="pp_nama" class="control-label">Nama Pilot Project <span class="required">*</span></label>
                                <input type="text" name="pp_nama" id="pp_nama" placeholder="Masukan Nama Pilot Project" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="pp_deskripsi" class="control-label">Deskripsi Pilot Project <span class="required">*</span></label>
                                <textarea name="pp_deskripsi" id="pp_deskripsi" rows="4" placeholder="Masukan Deskripsi Pilot Project" class="form-control" autocomplete="off"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="pp_timeline" class="control-label">Rencana Timeline Pilot Project <span class="required">*</span></label>
                                <textarea name="pp_timeline" id="pp_timeline" rows="4" placeholder="Masukan Timeline Pilot Project" class="form-control" autocomplete="off"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="pp_target" class="control-label">Target Pilot Project <span class="required">*</span></label>
                                <textarea name="pp_target" id="pp_target" rows="4" placeholder="Masukan Target Pilot Project" class="form-control" autocomplete="off"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="pp_potential_partner" class="control-label">Potential Partner <span class="required">*</span></label>
                                <textarea name="pp_potential_partner" id="pp_potential_partner" rows="4" placeholder="Masukan Potential Partner" class="form-control" autocomplete="off"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="pp_kebutuhan_ahli" class="control-label">Kebutuhan Ahli <span class="required">*</span>
                                    <br><span class="note"><i class="fa fa-circle-info me-1"></i>Jenis keahlian yang dimiliki mentor dibutuhkan startup untuk menjalankan pilot project tersebut beserta apa yang ingin dicapai/ditingkatkan bersama mentor</span>
                                </label>
                                <textarea name="pp_kebutuhan_ahli" id="pp_kebutuhan_ahli" rows="4" placeholder="Kebutuhan Ahli" class="form-control" autocomplete="off"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="pp_pembeda" class="control-label">Pembeda dari Program yang Biasa Dibuat <span class="required">*</span>
                                    <br><span class="note"><i class="fa fa-circle-info me-1"></i>Penjelasan tentang apa yang membedakan pilot project ini dari program-program serupa yang sudah pernah dilakukan</span>
                                </label>
                                <textarea name="pp_pembeda" id="pp_pembeda" rows="4" placeholder="Masukan Pembeda dari Program yang Biasa Dibuat" class="form-control" autocomplete="off"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <input type="hidden" name="token_user" id="token_user">
                        <button type="submit" id="btn-submit-profile" class="btn"><span>Simpan</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url();?>assets/front/js/function.js"></script>

<script type="text/javascript">
	$(document).ready(function () {

		// --------------------------- profile ---------------------------
            load_data_profile();

            function load_data_profile() {

                $.ajax({
                    type     : 'POST',
                    url      : '<?php echo base_url();?>dashboard/dashboard/fetch_data_profile',
                    dataType : 'json',
                    success: function(response) {

                        // data personal
                        $('#pp_background_masalah').val(response.pp_background_masalah);
                        $('#token_user').val(response.token);
                        $('#pp_nama').val(response.pp_nama);
                        $('#pp_deskripsi').val(response.pp_deskripsi);
                        $('#pp_timeline').val(response.pp_timeline);
                        $('#pp_target').val(response.pp_target);
                        $('#pp_potential_partner').val(response.pp_potential_partner);
                        $('#pp_kebutuhan_ahli').val(response.pp_kebutuhan_ahli);
                        $('#pp_pembeda').val(response.pp_pembeda);
                    
                    }
                });
            }

            var validate_form_profile = $('#form-profile-update').validate({
                rules: {
                    pp_background_masalah: {
                        required: true,
                    },
                    pp_nama: {
                        required: true,
                    },
                    pp_deskripsi: {
                        required: true,
                    },
                    pp_timeline: {
                        required: true,
                    },
                    pp_target: {
                        required: true,
                    },
                    pp_potential_partner: {
                        required: true,
                    },
                    pp_kebutuhan_ahli: {
                        required: true,
                    },
                    pp_pembeda: {
                        required: true,
                    }
                },
                messages: {
                    pp_background_masalah: {
                        required: 'Background masalah harus diisi.',
                    },
                    pp_nama: {
                        required: 'Nama Pilot project harus diisi.',
                    },
                    pp_deskripsi: {
                        required: 'Deskripsi harus diisi.',
                    },
                    pp_timeline: {
                        required: 'Timeline harus diisi.',
                    },
                    pp_target: {
                        required: 'Target harus diisi.',
                    },
                    pp_potential_partner: {
                        required: 'Potential partner harus diisi.',
                    },
                    pp_kebutuhan_ahli: {
                        required: 'Kebutuhan ahli harus diisi.',
                    },
                    pp_pembeda: {
                        required: 'Pembeda harus diisi.',
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
                        url      : '<?php echo base_url(); ?>dashboard/dashboard/post_update_pilot_project',
                        data     : new FormData(this),
                        dataType : 'json',
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.status == 1) {
                                trigger_cta_event('Pilot Project Form');
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


        var scrollTop = function() {
            $('html, body').animate({ scrollTop: 0 }, 'slow');
        }

        function trigger_cta_event(data){
            var url_visit = getUrlVisit();
            $.ajax({
                 method   : 'POST',
                 url      : '<?php echo base_url();?>analytic/post_cta_btn',
                 data     : { data:data, url:url_visit },
                 dataType : 'json',
                 success:function(response) {

                 }
            });
            return false;
        }

    });
</script>