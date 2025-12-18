<?php 
    $web = $this->main_model->get_admin_web(); 
?>

<section class="voting-section section section-md">
    <div class="container">
        <div class="section-title">
            <p class="description section-heading-sm fw-500">Beraksi untuk Inovasi! 'Innovate' atau 'Ideasi' – Tentukan Siapa Yang Menginspirasi! Suara Anda, Kekuatan Kita!</p>
        </div>

        <ul class="nav nav-tabs nav-tabs-style" id="tab-voting-nav"></ul>
        <div class="tab-content tab-content-style" id="tab-voting-content"></div>
    </div>
</section>


<section class="intro-section section section-lg">
    <img src="<?php echo base_url();?>assets/front/img/background/p1.webp" alt="Pattern Image" class="img-fluid lazyload p1">
    <div class="container">
        <div class="inner" id="load_data_intro_header"></div>
    </div>
</section>


<section class="series-section section section-md pt-4">
    <div class="container">
        <div class="section-title">
            <p class="subtitle section-description">Follow The Agenda</p>
            <h2 class="title section-heading">Rangkaian Event SheHacks 2023</h2>
        </div>

        <div id="load_data_schedule"></div>

        <div class="series-note">
            <p class="section-description-md">Webinar (selama 2 minggu sekali sejak April - Desember 2023)</p>
        </div>
    </div>
</section>


<section class="event-section section section-sm">
    <img src="<?php echo base_url();?>assets/front/img/background/p1.webp" alt="Pattern Image" class="img-fluid lazyload p1">
    <div class="container">
        <div class="section-title">
            <p class="subtitle section-description">Follow The Agenda</p>
            <h2 class="title section-heading">Agenda SheHacks 2023 Terdekat</h2>
        </div>

        <div class="row" id="load_data_agenda"></div>
    </div>
</section>


<!-- <section class="video-section section section-md">
    <div class="container">
        <div class="section-title">
            <p class="subtitle section-description">Follow The Agenda</p>
            <h2 class="title section-heading">Video Tutorial Registrasi  dan Pengumpulan Proposal SheHacks 2023</h2>
        </div>

        <div class="row scrollable h-scrollable" id="load_data_video"></div>
    </div>
</section> -->


<?php include 'component/testimonial.php'; ?>


<!-- <section class="partner-section section section-lg">
    <div class="container">
        <div id="load_data_partner"></div>
    </div>
</section> -->


<section class="join-section section section-lg">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 order-xl-1 order-0">
                <div class="content">
                    <div class="section-title">
                        <p class="subtitle section-description">You Deserve Better Career</p>
                        <h2 class="title section-heading-sm">Bergabunglah bersama SheHacks 2023 dan wujudkan inovasimu jadi solusi nyata!</h2>
                    </div>

                    <ul class="list-check">
                        <li class="list-item section-description-md">Program Inkubasi</li>
                        <li class="list-item section-description-md">Pelatihan (Bootcamp)</li>
                        <li class="list-item section-description-md">Bimbingan Mentoring</li>
                        <li class="list-item section-description-md">Program Pendampingan</li>
                        <li class="list-item section-description-md">Webinar Bulanan</li>
                    </ul>

                    <?php if ($web['register_button'] == 'true') { ?>
                        <a href="<?php echo base_url();?>register" class="btn btn-hover-arrow-right">Join Now</a>
                    <?php } ?>
                </div>
            </div>

            <div class="col-xl-6 order-xl-0 order-1">
                <div class="thumb">
                    <img data-src="<?php echo base_url();?>assets/front/img/thumb/thumb-1.webp" alt="Join Image" class="img-fluid lazyload thumb-img">

                    <img data-src="<?php echo base_url();?>assets/front/img/thumb/thumb-1-2.webp" alt="Join Image" class="img-fluid lazyload t-img t1">
                    <!-- <img data-src="<?php echo base_url();?>assets/front/img/thumb/thumb-1-1.webp" alt="Join Image" class="img-fluid lazyload t-img t2"> -->
                    <img data-src="<?php echo base_url();?>assets/front/img/thumb/thumb-1-3.webp" alt="Join Image" class="img-fluid lazyload t-img t3">
                    <img data-src="<?php echo base_url();?>assets/front/img/thumb/thumb-1-4.webp" alt="Join Image" class="img-fluid lazyload t-img t4">
                </div>
            </div>
        </div>
    </div>
</section>


<section class="question-section section section-lg">
    <div class="container">
        <div class="section-title center">
            <p class="subtitle section-description">Tanya SheHacks 2023</p>
            <h2 class="title section-heading">Frequently Asked Questions 😊</h2>
        </div>

        <div class="inner">
            <div class="row accordion" id="load_data_faq"></div>
        </div>
    </div>
</section>


<?php include 'component/cta.php'; ?>


<div class="modal modal-style style-2 fade banner-popup" id="banner-popup" aria-hidden="true" aria-labelledby="ModalToggleLabel" tabindex="2">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="inner-modal">
                <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                
                <div class="banner-popup-area" id="load_data_banner_popup"></div>
            </div>
        </div>
    </div>
</div>


<div class="modal modal-style style-2 fade voting-success" id="voting-success" aria-hidden="true" aria-labelledby="ModalToggleLabel" tabindex="2">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="inner-modal">
                <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                
                <div class="d-md-flex align-items-md-center gap-3">
                    <div class="mb-md-0 mb-4 text-md-start text-center">
                        <img src="<?php echo base_url(); ?>assets/front/img/icon/icon-sucsess.png" alt="image" class="img-fluid m-auto">
                    </div>
                    <div class="text-md-start text-center">
                        <p class="section-heading-xs fw-500 mb-0">Selamat, Anda berhasil melakukan voting!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {

        var logged_in_front = '<?php echo $this->session->userdata('logged_in_front'); ?>';

        // --------------------------- load data voting ---------------------------
            load_data_voting();

            function load_data_voting() {
                
                $.ajax({
                    url      : '<?php echo base_url();?>fetch/fetch_data/fetch_data_voting',
                    dataType : 'json',
                    beforeSend: function() {},
                    success:function(response) {
                        
                        var load_data = '';
                        
                        if (response.status == 1) {
                            
                            // navigation
                            load_data_nav = 
                                '<li class="nav-item">'+
                                    '<a href="javascript:void(0)" class="nav-link section-description-md active" data-bs-toggle="tab" data-bs-target="#q1">Innovate</a>'+
                                '</li>'+
                                '<li class="nav-item">'+
                                    '<a href="javascript:void(0)" class="nav-link section-description-md" data-bs-toggle="tab" data-bs-target="#q2">Ideasi</a>'+
                                '</li>';

                            $('#tab-voting-nav').html(load_data_nav);


                            // mvp
                            var load_data_mvp = '';

                            $.each(response.data_mvp, function(i, val) {
                                
                                if (logged_in_front == false) {
                                    var url_vote = 'href="<?php echo base_url();?>login"';
                                } else {
                                    var url_vote = 'href="javascript:void(0)"';
                                }

                                if (val.status_vote == 1) {
                                    var status_vote = 'd-none';
                                } else {
                                    var status_vote = '';
                                }

                                load_data_mvp += 
                                    '<div class="col-lg-4 col-md-6">'+
                                        '<div class="voting-area">'+
                                            '<div class="video-area">'+
                                                '<a href="https://www.youtube.com/watch?v='+val.video_upload+'" class="play-video video-popup"><span></span></a>'+
                                                '<img src="https://img.youtube.com/vi/'+val.video_upload+'/hqdefault.jpg" alt="" class="cover cover-video-showcase lazyload">'+
                                                '<video class="player" id="player-video-showcase" autoplay preload crossoriginx controls="false" controlslist="false" muted="false">'+
                                                    '<source src="https://www.youtube.com/embed/'+val.video_upload+'" type="video/mp4">'+
                                                '</video>'+
                                            '</div>'+

                                            '<div class="body-area">'+
                                                '<div class="meta-info">'+
                                                    '<div class="logo-area">'+
                                                        '<img src="'+val.logo+'" alt="image" class="img-fluid logo-img">'+
                                                    '</div>'+

                                                    '<div class="content-area">'+
                                                        '<h3 class="section-description-md text-limit-2-row business-name">'+val.nama_usaha+'</h3>'+
                                                        '<p class="section-description-sm founder-name">'+val.nama_founders+'</p>'+
                                                    '</div>'+
                                                '</div>'+

                                                '<p class="section-description-sm business-category"><span class="text-accent">'+val.bidang_usaha+'</span>, <span class="text-2">'+val.domisili+'</span></p>'+
                                                '<p class="section-description-sm desc text-limit-3-row">'+val.description+'</p>'+

                                                '<a '+url_vote+' class="btn btn-padd-sm btn-accent w-100 trigg-vote mt-3 '+status_vote+'" data="'+val.id_voting_enc+'">Vote <i class="lni lni-pointer-top"></i><a>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>';
                            });
                            

                            // ideasi
                            var load_data_ideasi = '';

                            $.each(response.data_ide, function(i, val) {
                                
                                if (logged_in_front == false) {
                                    var url_vote = 'href="<?php echo base_url();?>login"';
                                } else {
                                    var url_vote = 'href="javascript:void(0)" click="trigg-vote"';
                                }

                                if (val.status_vote == 1) {
                                    var status_vote = 'd-none';
                                } else {
                                    var status_vote = '';
                                }

                                load_data_ideasi += 
                                    '<div class="col-lg-4 col-md-6">'+
                                        '<div class="voting-area">'+
                                            '<div class="video-area">'+
                                                '<a href="https://www.youtube.com/watch?v='+val.video_upload+'" class="play-video video-popup"><span></span></a>'+
                                                '<img src="https://img.youtube.com/vi/'+val.video_upload+'/hqdefault.jpg" alt="" class="cover cover-video-showcase lazyload">'+
                                                '<video class="player" id="player-video-showcase" autoplay preload crossoriginx controls="false" controlslist="false" muted="false">'+
                                                    '<source src="https://www.youtube.com/embed/'+val.video_upload+'" type="video/mp4">'+
                                                '</video>'+
                                            '</div>'+

                                            '<div class="body-area">'+
                                                '<div class="meta-info">'+
                                                    '<div class="logo-area">'+
                                                        '<img src="'+val.logo+'" alt="image" class="img-fluid logo-img">'+
                                                    '</div>'+

                                                    '<div class="content-area">'+
                                                        '<h3 class="section-description-md text-limit-2-row business-name">'+val.nama_usaha+'</h3>'+
                                                        '<p class="section-description-sm founder-name">'+val.nama_founders+'</p>'+
                                                    '</div>'+
                                                '</div>'+
                                                
                                                '<p class="section-description-sm business-category"><span class="text-accent">'+val.bidang_usaha+'</span>, <span class="text-2">'+val.domisili+'</span></p>'+
                                                '<p class="section-description-sm desc text-limit-3-row">'+val.description+'</p>'+

                                                '<a '+url_vote+' class="btn btn-padd-sm btn-accent w-100 trigg-vote mt-3 '+status_vote+'" data="'+val.id_voting_enc+'">Vote <i class="lni lni-pointer-top"></i><a>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>';
                            });

                            // load data main
                            load_data += 
                                '<div class="tab-pane fade active show" id="q1" role="tabpanel">'+
                                    '<p class="section-description-md text-dark"><b>Shehacks Innovate</b> adalah workshop offline yang di lakukan selama dua hari di Labuan Bajo dan Magelang sebagai wadah bagi wirausaha wanita untuk mengembangkan bisnis kearah basis digital, serta berjejaring untuk memperluas jangkauan bisnis dengan mentor bisnis dan sesama wirausaha.</p>'+
                                    '<p class="section-description-md text-dark"><b>Kamu hanya dapat memilih satu kali dari dua kategori dengan satu akun untuk melalukan vote</b></p>'+
                                    '<div class="row">'+
                                        load_data_mvp+
                                    '</div>'+
                                '</div>'+

                                '<div class="tab-pane fade" id="q2" role="tabpanel">'+
                                    '<p class="section-description-md text-dark"><b>Shehacks Ideasi</b> adalah wadah bagi perempuan indonesia yang memiliki ide solutif, inovatif, dan mempunyai dampak sosial yang tinggi dimana ide tersebut berpotensi menyelesaikan permasalahan yang terjadi di lingkungan sekitar mereka.</p>'+
                                    '<p class="section-description-md text-dark"><b>Kamu hanya dapat memilih satu kali dari dua kategori dengan satu akun untuk melalukan vote</b></p>'+
                                    '<div class="row">'+
                                        load_data_ideasi+
                                    '</div>'+
                                '</div>';


                            $('#tab-voting-content').html(load_data);

                            $('.lazyload').Lazy({
                                effect: 'fadeIn',
                                effectTime: 800,
                                threshold: 200,
                                visibleOnly: true,
                                combined: true,
                            });

                            $('.video-popup').magnificPopup({
                                type: 'iframe',
                                mainClass: 'mfp-fade mfp-no-margins mfp-with-zoom',
                                disableOn: 320,
                                removalDelay: 150,
                                preloader: true,
                                closeOnContentClick: true,
                                fixedContentPos: true,
                                zoom: {
                                    enabled: true,
                                    duration: 400
                                },
                                iframe: {
                                    patterns: {
                                        youtube: {
                                            index: 'youtube.com/', 
                                            id: function(url) {        
                                                var m = url.match(/[\\?\\&]v=([^\\?\\&]+)/);
                                                if ( !m || !m[1] ) return null;
                                                return m[1];
                                            },
                                            src: '//www.youtube.com/embed/%id%?autoplay=1'
                                        },
                                    }
                                },
                            });
                        }
                    }
                });
            }

            $(document).on('click', '.trigg-vote', function(e) {
                e.preventDefault();
                
                var data_id = $(this).attr('data');

                $.ajax({
                    method   : 'POST',
                    url      : '<?php echo base_url();?>fetch/fetch_data/post_data_voting',
                    data     : { id_voting_enc:data_id },
                    dataType : 'json',
                    beforeSend: function() {},
                    success:function(response) {
                        
                        if (response.status == 1) {

                            $('#voting-success').modal('show');
                            load_data_voting();

                            setTimeout(() => {
                                $('#voting-success').modal('hide');
                            }, 2000);
                        }
                        else {

                        }

                    }
                });
            });
        // --------------------------- end load data voting ---------------------------


        // --------------------------- load data Intro Header ---------------------------
            $.ajax({
                url      : '<?php echo base_url();?>fetch/fetch_data/fetch_data_intro',
                data     : { param: 'home_intro' },
                dataType : 'json',
                beforeSend: function() {},
                success:function(response) {
                    var load_data = '';
                    
                    if (response.status == 1) {

                        $.each(response.data, function(i, val) {

                            var btn_reg_stat = "<?php echo $web['register_button']; ?>";

                            if (btn_reg_stat == 'true') {
                                var btn_reg_stat = '<a href="'+val.button_url+'" class="btn btn-hover-arrow-right">'+val.button_text+'</a>';
                            } else {
                                var btn_reg_stat = '';
                            }

                            load_data += 
                                '<div class="row align-items-center">'+
                                    '<div class="col-lg-6">'+
                                        '<div class="content">'+
                                            '<div class="section-title">'+
                                                '<p class="subtitle section-description">'+val.subheading+'</p>'+
                                                '<h2 class="title section-heading-xl">'+val.heading+'</h2>'+
                                                '<p class="description section-description-md">'+val.content+'</p>'+
                                            '</div>'+

                                            '<div class="act">'+
                                                btn_reg_stat+
                                                '<a href="'+val.instagram+'" target="_blank" class="btn btn-white btn-hover-icon-left"><span class="icon lni lni-instagram"></span> Follow our Instagram</a>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+

                                    '<div class="col-lg-6">'+
                                        '<div class="thumb">'+
                                            '<img src="'+val.image+'" alt="" class="img-fluid lazyload thumb-img">'+
                                        '</div>'+
                                    '</div>'+
                                '</div>';
                        });

                        $('#load_data_intro_header').html(load_data);
                    }
                }
            });
        // --------------------------- end load data Intro Header ---------------------------


        // --------------------------- load data Schedule ---------------------------
            $.ajax({
                url      : '<?php echo base_url();?>fetch/fetch_data/fetch_data_schedule',
                dataType : 'json',
                beforeSend: function() {},
                success:function(response) {
                    var load_data = '';
                    
                    if (response.status == 1) {

                        $.each(response.data, function(i, val) {
                         
                            load_data += 
                                '<img data-src="'+val.image+'" alt="'+val.heading+'" class="img-fluid lazyload series-img image-desktop">'+
                                '<img data-src="'+val.image_2+'" alt="'+val.heading+'" class="img-fluid lazyload series-img image-mobile">';
                        });

                        $('#load_data_schedule').html(load_data);

                        $('.lazyload').Lazy({
                            effect: 'fadeIn',
                            effectTime: 800,
                            threshold: 200,
                            visibleOnly: true,
                            combined: true,
                        });
                    }

                }
            });
        // --------------------------- end load data Schedule ---------------------------


        // --------------------------- load data Agenda ---------------------------
            $.ajax({
                url      : '<?php echo base_url();?>fetch/fetch_data/fetch_data_agenda',
                dataType : 'json',
                beforeSend: function() {},
                success:function(response) {
                    var load_data = '';
                    
                    if (response.status == 1) {

                        $.each(response.data, function(i, val) {
                         
                            load_data += 
                                '<div class="col-lg-4 col-md-6 mb-4">'+
                                    '<div class="post">'+
                                        '<div class="head"> '+   
                                            '<a href="javascript:void(0)">'+
                                                '<img data-src="'+val.image+'" alt="'+val.heading+'" class="img-fluid lazyload img" width="335" height="300">'+
                                            '</a>'+
                                            '<span class="badge badge-white">Agenda</span>'+
                                        '</div>'+

                                        '<div class="body">'+
                                            '<a href="javascript:void(0)">'+
                                                '<h2 class="title section-description">'+val.heading+'</h2>'+
                                            '</a>'+
                                            '<div class="meta-info line-separator">'+
                                                '<span class="text"><i class="icon lni lni-user"></i> Kumpul</span>'+
                                                '<span class="text"><i class="icon lni lni-calendar"></i>'+val.tanggal+'</span>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>';
                        });

                        $('#load_data_agenda').html(load_data);
                    }

                    $('.lazyload').Lazy({
                        effect: 'fadeIn',
                        effectTime: 800,
                        threshold: 200,
                        visibleOnly: true,
                        combined: true,
                    });
                }
            });
        // --------------------------- end load data Agenda ---------------------------


        // --------------------------- load data Video ---------------------------
            $.ajax({
                url      : '<?php echo base_url();?>fetch/fetch_data/fetch_data_video',
                dataType : 'json',
                beforeSend: function() {},
                success:function(response) {
                    var load_data = '';
                    
                    if (response.status == 1) {

                        $.each(response.data, function(i, val) {
                         
                            load_data += 
                                '<div class="col-6">'+
                                    '<div class="video-area">'+
                                        '<a href="javascript:void(0)" class="play-video play-video-showcase"><span></span></a>'+
                                        '<img data-src="https://img.youtube.com/vi/'+val.video+'/hqdefault.jpg" alt="" class="cover cover-video-showcase lazyload">'+
                                        '<video class="player" id="player-video-showcase" autoplay preload crossoriginx controls="false" controlslist="false" muted="false">'+
                                            '<source src="https://www.youtube.com/embed/'+val.video+'" type="video/mp4">'+
                                        '</video>'+
                                    '</div>'+
                                '</div>';
                        });

                        $('#load_data_video').html(load_data);
                    }

                    $('.lazyload').Lazy({
                        effect: 'fadeIn',
                        effectTime: 800,
                        threshold: 200,
                        visibleOnly: true,
                        combined: true,
                    });
                }
            });
        // --------------------------- end load data Video ---------------------------


        // --------------------------- load data Partner ---------------------------
            $.ajax({
                url      : '<?php echo base_url();?>fetch/fetch_data/fetch_data_partner',
                dataType : 'json',
                beforeSend: function() {},
                success:function(response) {
                    var load_data = '';
                    
                    if (response.status == 1) {

                        $.each(response.data, function(i, val) {
                         
                            load_data += 
                                    '<div class="partner-area">'+
                                        '<h3 class="partner-title section-description">'+val.nama+'</h3>'+
                                        '<div class="partner-list">';

                                            $.each(val.partner, function(i1, val1) {
                                                load_data += 
                                                    '<div class="partner-item">'+
                                                        '<img data-src="'+val1.image+'" alt="'+val1.nama+'" class="img-fluid lazyload partner-img">'+
                                                    '</div>';
                                                });

                                        load_data += 

                                        '</div>'+
                                    '</div>';
                        });

                    }

                    $('#load_data_partner').html(load_data);

                    $('.lazyload').Lazy({
                        effect: 'fadeIn',
                        effectTime: 800,
                        threshold: 200,
                        visibleOnly: true,
                        combined: true,
                    });
                }
            });
        // --------------------------- end load data Partner ---------------------------


        // --------------------------- load data banner popup ---------------------------
            $.ajax({
                url      : '<?php echo base_url();?>fetch/fetch_data/fetch_data_banner_popup',
                dataType : 'json',
                beforeSend: function() {},
                success:function(response) {
                    var load_data = '';
                    
                    if (response.status == 1) {

                        $.each(response.data, function(i, val) {
                            
                            if (val.button_url) {
                                var button_url = 'href="'+val.button_url+'" target="_blank"';
                            } else {
                                var button_url = 'href="javascript:void(0)"';
                            }

                            load_data += 
                                '<a '+button_url+'>'+
                                    '<img src="'+val.image+'" alt="banner" class="image-banner img-fluid lazyload" width="800" height="450">'+
                                '</a>';
                        });

                        // setTimeout(function () {
                        //     $('#banner-popup').modal('show');
                        // }, 2500);
                    }

                    $('#load_data_banner_popup').html(load_data);
                }
            });
        // --------------------------- end load data banner popup ---------------------------


        // --------------------------- load data FAQ ---------------------------
            $.ajax({
                url      : '<?php echo base_url();?>fetch/fetch_data/fetch_data_faq',
                data     : { limit: '6'},
                dataType : 'json',
                beforeSend: function() {},
                success:function(response) {
                    var load_data = '';
                    
                    if (response.status == 1) {

                        $.each(response.data, function(i, val) {
                            
                            load_data += 
                                '<div class="col-lg-6">'+
                                    '<div class="accordion-item" id="faq-val'+val.id+'">'+
                                        '<button type="button" class="accordion-head collapsed section-description" data-bs-toggle="collapse" data-bs-target="#faq-val'+val.id+'-collapse" aria-expanded="false" aria-controls="faq-val'+val.id+'-collapse">'+val.heading+
                                        '</button>'+

                                        '<div id="faq-val'+val.id+'-collapse" class="accordion-collapse collapse" aria-labelledby="faq-val1">'+
                                            '<div class="accordion-body section-description-md reset-style-content">'+val.description+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>';
                        });

                    }

                    $('#load_data_faq').html(load_data);
                }
            });
        // --------------------------- end load data FAQ ---------------------------

    });
</script>