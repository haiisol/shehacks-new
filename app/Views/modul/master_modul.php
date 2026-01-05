<section class="learn-section section section-sm">
    <div class="container">

        <div class="row">
            <div class="col-lg-3 order-lg-0 order-1 mt-lg-0 mt-5 sticky-sidebar">
    			<div class="learn-sidebar sidebar-style">
                    <div class="sidebar-inner">
                        <ul class="sidebar-menu" id="modul-menu">
                            
                            <!-- pre test -->
                            <?php if ($get_quiz) { ?>
                                <?php 
                                    if(empty($get_quiz_pre_posisi)) { 
                                        $qpr_m_active = 'menu-nonactive';
                                    } else { 
                                        $qpr_m_active = 'menu-active';
                                    }
                                ?>
                                
                                <li class="menu-items">
                                    <a href="javascript:void(0)" 
                                        id="menu-qpr-<?php echo $id_modul; ?>"
                                        data="<?php echo $id_modul; ?>" 
                                        class="quiz-pre-trigg <?php echo $qpr_m_active; ?>" >
                                        <span class="menu-icon"><i class="lni lni-support"></i></span>
                                        <span class="menu-label">Pre Test</span>
                                    </a>
                                </li>
                            <?php } ?>


                            <!-- video -->
                            <?php if (!empty($videos))  { ?>
                                <?php foreach ($videos as $video) { ?>
                                    <li class="menu-items">
                                        <a href="javascript:void(0)" 
                                            id="menu-vid-<?php echo $video['id_video']; ?>"
                                            data="<?php echo $id_modul; ?>" 
                                            data_id="<?php echo $video['id_video']; ?>" 
                                            class="video-trigg <?php echo $video['menu_active']; ?>" >
                                            <span class="menu-icon"><i class="lni lni-video"></i></span>
                                            <span class="menu-label"><?php echo $video['judul']; ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                            <?php } ?>


                            <!-- post test -->
                            <?php if ($get_quiz) { ?>
                                <?php
                                    if(empty($get_quiz_post_posisi)) { 
                                        $qpo_m_active = 'menu-nonactive';
                                    } else { 
                                        $qpo_m_active = 'menu-active';
                                    }
                                ?>
                                
                                <li class="menu-items">
                                    <a href="javascript:void(0)" 
                                        id="menu-qpo-<?php echo $id_modul; ?>"
                                        data="<?php echo $id_modul; ?>" 
                                        class="quiz-post-trigg <?php echo $qpo_m_active; ?>" >
                                        <span class="menu-icon"><i class="lni lni-support"></i></span>
                                        <span class="menu-label">Post Test</span>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
	    		</div>
    		</div>


            <div class="col-lg-9 order-lg-1 order-0">
                <div class="learn-content">
                    <div class="section-title">
                        <h2 class="title section-heading-sm" id="learn_heading"></h2>
                    </div>

                    <div id="learn_content"></div>

                    <div id="learn_quiz" class="d-none">
                        <div class="note-area"><i class="fa fa-info"></i><span>Pilihlah salah satu jawaban yang paling benar.</span></div>

                        <div class="card card-style">
                            <div class="card-body">
                                <h4 class="section-description-md fw-600 mb-4" id="quiz_question"></h4>
                                
                                <form method="post" id="form-quiz">
                                    <input type="hidden" name="id_user" id="id_user" value="<?php echo $id_user; ?>">
                                    <input type="hidden" name="id_modul" id="id_modul" value="<?php echo $id_modul; ?>">
                                    <input type="hidden" name="id_quiz" id="id_quiz">
                                    <input type="hidden" name="quiz_param" id="quiz_param">
                                
                                    <div class="form-group">
                                        <div id="quiz_selection"></div>
                                        <span id="quiz_feedback" class="invalid-feedback font-13"><i class="lni lni-warning"></i> Silahkan pilih jawaban terlebih dahulu</span>
                                    </div>

                                    <div class="form-group mb-0 mt-4">
                                        <button type="submit" id="submit-form-quiz" class="submit_quiz btn btn-padd-sm btn-accent float-end"><span>Lanjut</span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="learn-act">
                        <div>
                            <button type="button" id="prev_learn" class="btn btn-white btn-hover-icon-left scroll-top"><span class="icon lni lni-arrow-left"></span> <span>Sebelumnya</span></button>
                        </div>
                        <div>
                            <button type="button" id="next_learn" class="btn btn-hover-arrow-right scroll-top"><span>Lanjutkan</span></button>
                            <a href="<?php echo base_url(); ?>dashboard" id="next_dashboard" class="d-none btn btn-hover-icon-left"><span class="icon lni lni-checkmark"></span> <span>Selesai</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<div class="modal modal-style style-2 fade banner-popup" id="popup-info" aria-hidden="true" aria-labelledby="ModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="inner-modal">
                
                <div class="banner-popup-area modul-info-area">
                    <div class="content">
                        <div class="section-title mb-0">    
                            <!-- <h2 class="title section-heading-sm"><?php echo $title; ?></h2> -->
                            <p class="description section-description-lg" id="info-deskripsi"></p>
                        </div>

                        <div class="form-actions text-center">
                            <a href="javascript:void(0)" class="btn w-100" data-bs-dismiss="modal" aria-label="Close"><span>LANJUTKAN</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="i_post" id="i_post" value="0">

<script type="text/javascript">
    $(document).ready(function() {
        var title    = '<?php echo $title; ?>';
        var id_user  = '<?php echo $id_user; ?>';
        var id_modul = '<?php echo $id_modul; ?>';

        // --------------------------- data edukasi ---------------------------
            check_learn_progress();

            function check_learn_progress()
            {
                $('#next_learn').removeClass('d-none');

                $.ajax({
                    type     : 'post',
                    url      : '<?php echo base_url();?>modul/modul_master/check_learn_progress',
                    data     : { id_modul:id_modul, id_user:id_user },
                    dataType : 'json',
                    success: function(response) {

                        if (response.status == 0) {
                            load_data('', 'PRE - TEST');
                        } 
                        else {
                            if (response.data_skor) {

                                $('#i_post').val(response.i_post);

                                $('#learn_heading').html('Post Test');
                                $('#learn_quiz').addClass('d-none');

                                load_data_result_quiz(response.data_skor);

                                $('#next_dashboard').removeClass('d-none');
                                $('#next_learn').addClass('d-none');

                                // learn menu
                                $('#menu-qpo-'+id_modul).removeClass('menu-nonactive');
                                $('#menu-qpo-'+id_modul).addClass('menu-open');
                            } 
                            else {
                                $('#i_post').val(response.i_post);
                                load_data(response.id_param, response.param);
                            }
                        }
                    }
                });
            }

            function load_data_result_quiz(data)
            {
                var load_data = 
                    '<div class="card card-style">'+
                        '<div class="card-body">'+
                            '<div class="row">'+
                                '<div class="col-lg-5 m-auto">'+
                                    '<div class="result-quiz">'+
                                        '<h4 class="section-description"><b>Hasil Test</b></h4>'+
                                        '<div class="quiz-score">'+
                                            '<span class="value">'+data.skor+'</span>'+
                                            '<span class="label">Skor Kamu</span>'+
                                        '</div>'+

                                        '<div class="quiz-info">'+
                                            '<div class="true">'+
                                                '<svg viewBox="0 0 376 312" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M348 28L124 284L28 188" stroke-width="55" stroke-linecap="round" stroke-linejoin="round"/></svg>'+
                                                '<div>Benar <span>'+data.benar+'</span></div>'+
                                            '</div>'+
                                            '<div class="false">'+
                                                '<svg viewBox="0 0 298 291" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M186.173 145.5L290.222 43.8951C295.16 39.0814 297.938 32.5494 297.944 25.7358C297.95 18.9223 295.184 12.3854 290.255 7.56329C285.325 2.74114 278.636 0.0287097 271.659 0.0226925C264.681 0.0166752 257.987 2.71757 253.049 7.53121L149 109.136L44.951 7.53121C40.0128 2.70906 33.3153 0 26.3317 0C19.3481 0 12.6505 2.70906 7.71237 7.53121C2.77422 12.3534 0 18.8936 0 25.7131C0 32.5327 2.77422 39.0729 7.71237 43.8951L111.761 145.5L7.71237 247.105C2.77422 251.927 0 258.467 0 265.287C0 272.106 2.77422 278.647 7.71237 283.469C12.6505 288.291 19.3481 291 26.3317 291C33.3153 291 40.0128 288.291 44.951 283.469L149 181.864L253.049 283.469C257.987 288.291 264.685 291 271.668 291C278.652 291 285.349 288.291 290.288 283.469C295.226 278.647 298 272.106 298 265.287C298 258.467 295.226 251.927 290.288 247.105L186.173 145.5Z"/></svg>'+
                                                '<div>Salah <span>'+data.salah+'</span></div>'+
                                            '</div>'+
                                        '</div>'+
                                        '<p class="section-description-sm text-muted">Tanggal : <span>'+data.tanggal+'</span></p>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>';

                $('#learn_content').html(load_data);
            }

            function load_data(id, param)
            {
                if (param == 'VIDEO') {
                    var url = '<?php echo base_url();?>modul/modul_master/fetch_data_video';
                }
                else if (param == 'PRE - TEST') {
                    var url = '<?php echo base_url();?>modul/modul_master/fetch_data_quiz';
                }
                else if (param == 'POST - TEST') {
                    var url = '<?php echo base_url();?>modul/modul_master/fetch_data_quiz';
                }

                $.ajax({
                    method   : 'POST',
                    url      : url,
                    data     : { id_modul:id_modul, id:id, param:param },
                    dataType : 'json',
                    success: function(response) {
                        
                        $('#modul-menu li a').removeClass('menu-open');
                        $('#learn_content').html('');

                        if (param == 'VIDEO') {

                            $('#learn_quiz').addClass('d-none');

                            // learn heading
                            $('#learn_heading').html(response.judul);

                            // handle button
                            $('#prev_learn').show();
                            $('#next_learn').show();

                            // learn menu
                            $('#menu-vid-'+response.id_video).removeClass('menu-nonactive');
                            $('#menu-vid-'+response.id_video).addClass('menu-open');

                            if (response.jenis == 'url') {
                                var load_data_content = '<iframe class="frame-video" src="'+response.url_video+'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                            } else {
                                var load_data_content = '<video class="frame-video" autoplay controls controlsList="nodownload"><source src="'+response.url_video+'" type="video/mp4"></video>';
                            }

                            // content
                            $('#learn_content').html(load_data_content);

                            cek_ipost_position('VIDEO', id_modul, response.id_video);
                        }
                        else if (param == 'PRE - TEST') {

                            $('#i_post').val('0');

                            $('#learn_quiz').removeClass('d-none');

                            // learn heading
                            $('#learn_heading').html('Pre Test');

                            // handle button
                            $('#prev_learn').hide();
                            $('#next_learn').hide();
                            $('#next_dashboard').hide();

                            // learn menu
                            $('#menu-qpr-'+id_modul).removeClass('menu-nonactive');
                            $('#menu-qpr-'+id_modul).addClass('menu-open');

                            // quiz selection
                            var quiz_selection = '';
                            $.each(response.data.quiz_selection, function(i, val) {
                                quiz_selection += 
                                    '<div class="custom-radio">'+
                                        '<input class="custom-control-input" type="radio" name="id_jawaban" id="quiz_'+val.id_jawaban+'" value="'+val.id_jawaban+'">'+
                                        '<label class="custom-control-label" for="quiz_'+val.id_jawaban+'">'+val.jawaban+'</label>'+
                                    '</div>';
                            });

                            $('#id_quiz').val(response.data.id_quiz);
                            $('#quiz_question').html(response.data.quiz_question);
                            $('#quiz_selection').html(quiz_selection);
                            $('#quiz_param').val(response.data.quiz_param);

                            setTimeout(function () {
                                $('#popup-info').modal('show');
                                $('#info-deskripsi').html('Halo! Sebelum menonton modul setiap materinya, Anda harus melakukan Pre Test. Pre Tes ini hanya akan dilakukan sekali sebelum modul “'+title+'” ditayangkan');
                            }, 800);
                        } 
                        else if (param == 'POST - TEST') {

                            $('#learn_quiz').removeClass('d-none');

                            // learn heading
                            $('#learn_heading').html('Post Test');

                            // handle button
                            $('#prev_learn').show();
                            $('#next_learn').hide();

                            // learn menu
                            $('#menu-qpo-'+id_modul).removeClass('menu-nonactive');
                            $('#menu-qpo-'+id_modul).addClass('menu-open');

                            // quiz selection
                            var quiz_selection = '';
                            $.each(response.data.quiz_selection, function(i, val) {
                                quiz_selection += 
                                    '<div class="custom-radio">'+
                                        '<input class="custom-control-input" type="radio" name="id_jawaban" id="quiz_'+val.id_jawaban+'" value="'+val.id_jawaban+'">'+
                                        '<label class="custom-control-label" for="quiz_'+val.id_jawaban+'">'+val.jawaban+'</label>'+
                                    '</div>';
                            });

                            $('#id_quiz').val(response.data.id_quiz);
                            $('#quiz_question').html(response.data.quiz_question);
                            $('#quiz_selection').html(quiz_selection);
                            $('#quiz_param').val(response.data.quiz_param);

                            cek_ipost_position('POST - TEST', id_modul, 0);

                            setTimeout(function () {
                                $('#popup-info').modal('show');
                                $('#info-deskripsi').html('Halo! Selamat Anda telah menyelesaikan modul pembelajaran, saatnya kamu menyelesaikan Post Test. Post Test ini hanya akan dilakukan sekali setelah Anda menonton modul “'+title+'”. Terimakasih.');
                            }, 800);
                        } 
                    }
                });
            }

            function cek_ipost_position(param, id_modul, id){
                $.ajax({
                    type     : 'post',
                    url      : '<?php echo base_url();?>modul/modul_master/cek_trigger_menu',
                    data     : { param: param, id_modul:id_modul, id:id },
                    dataType : 'json',
                    success: function(response) {
                        $('#i_post').val(response.i_post);
                    }
                });
            }

            $('#next_learn').on('click', function() {

                var i_post = $('#i_post').val();

                $.ajax({
                    type     : 'post',
                    url      : '<?php echo base_url();?>modul/modul_master/cek_data_modul',
                    data     : { id_modul:id_modul, i_post:i_post },
                    dataType : 'json',
                    success: function(response) {

                        $('#i_post').val(response.i_post);
                        $('#'+response.data_post.menu_active).addClass('menu-active');
                        $('#'+response.data_post.menu_active).removeClass('menu-nonactive');

                        load_data(response.data_post.id_param, response.data_post.param);
                    }
                });

                scrolltop()
            })

            $('#prev_learn').on('click', function() {

                $('#next_dashboard').addClass('d-none');
                $('#next_learn').removeClass('d-none');

                var i_post_get = $('#i_post').val();
                var i_post     = i_post_get-2;

                $.ajax({
                    type     : 'post',
                    url      : '<?php echo base_url();?>modul/modul_master/cek_data_modul',
                    data     : { id_modul:id_modul, i_post:i_post },
                    dataType : 'json',
                    success: function(response) {

                        $('#i_post').val(response.i_post);
                        $('#'+response.data_post.menu_active).addClass('menu-active');

                        load_data(response.data_post.id_param, response.data_post.param);
                    }
                });

                scrolltop()
            })
        // --------------------------- end data edukasi ---------------------------

        
        // --------------------------- submit quiz ---------------------------
            $('#form-quiz').submit(function(e) {
                e.preventDefault();

                var id_jawaban = $('input[name="id_jawaban"]:checked').val();

                if (id_jawaban) {
                    $('#quiz_feedback').removeClass('d-block');

                    var formData = new FormData(this);
                    formData.append('id_jawaban', id_jawaban);
                    
                    $.ajax({
                        method   : 'post',
                        url      : '<?php echo base_url();?>modul/modul_master/submit_quiz',
                        data     : formData,
                        dataType : 'json',
                        contentType: false,
				        processData: false,
                        success:function(response) {
                            
                            if (response.status == 1) {

                                $('#learn_quiz').addClass('d-none');
                                
                                load_data_result_quiz(response.data);

                                if (response.data.param == 'PRE - TEST') {
                                    $('#learn_heading').html('Pre Test');
                                    $('#next_learn').removeClass('d-none');
                                    $("#next_learn").show();
                                    $('#menu-qpr-'+id_modul).addClass('menu-active');
                                } else {
                                    $('#next_dashboard').removeClass('d-none');
                                    $('#next_dashboard').show();
                                    $('#learn_heading').html('Post Test');
                                    $('#next_learn').addClass('d-none');
                                    $('#menu-qpo-'+id_modul).addClass('menu-active');
                                }
                            } 
                            else if (response.status == 2) {

                                // quiz selection
                                var quiz_selection = '';
                                $.each(response.data.quiz_selection, function(i, val) {
                                    quiz_selection += 
                                        '<div class="custom-control custom-radio">'+
                                            '<input class="custom-control-input" type="radio" name="id_jawaban" id="quiz_'+val.id_jawaban+'" value="'+val.id_jawaban+'">'+
                                            '<label class="custom-control-label" for="quiz_'+val.id_jawaban+'">'+val.jawaban+'</label>'+
                                        '</div>';
                                });

                                $('#id_quiz').val(response.data.id_quiz);
                                $('#quiz_question').html(response.data.quiz_question);
                                $('#quiz_selection').html(quiz_selection);
                                $('#quiz_param').val(response.data.quiz_param);
                            }
                        }
                    })
                } 
                else {
                    $('#quiz_feedback').addClass('d-block');
                }
            });
        // --------------------------- end submit quiz ---------------------------


        // --------------------------- menu sidebar click ---------------------------
            $('.video-trigg').on('click', function() {
                var id = $(this).attr('data_id');
                load_data(id, 'VIDEO');
                scrolltop()
            });

            $('.quiz-pre-trigg').on('click', function() {
                var id = $(this).attr('data_quiz');
                load_data('', 'PRE - TEST');
                scrolltop()
            });

            $('.quiz-post-trigg').on('click', function() {
                var id = $(this).attr('data_quiz');
                load_data('', 'POST - TEST');
                scrolltop()
            });
        // --------------------------- end menu sidebar click ---------------------------

        function scrolltop() {
            $('html, body').animate({ scrollTop: $('html').offset().top }, 0);
        }
    });
</script>