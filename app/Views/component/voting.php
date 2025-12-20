<section class="voting-section section section-md mt-5">
    <div class="container">
        <div class="section-title">
            <p class="description section-heading-sm fw-500">Beraksi untuk Inovasi! 'Innovate' atau 'Ideasi' – Tentukan Siapa Yang Menginspirasi! Suara Anda, Kekuatan Kita!</p>
        </div>

        <div id="feedback-voting"></div>

        <ul class="nav nav-tabs nav-tabs-style" id="tab-voting-nav"></ul>
        <div class="tab-content tab-content-style" id="tab-voting-content"></div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function() {

        var logged_in_front = '<?php echo $logged_in_front; ?>';

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

                                var vote_trigger = '';

                                if (val.status_vote == 0) {
                                    var vote_trigger = 
                                        '<a '+url_vote+' class="btn btn-padd-sm btn-accent w-100 trigg-vote mt-3" data="'+val.id_voting_enc+'">Vote <i class="lni lni-pointer-top"></i><a>';
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

                                                vote_trigger+
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
                                    '<p class="section-description-md text-dark"><b>Shehacks Innovate</b> adalah workshop offline selama 2 hari di kota Jayapura, Nias, Ambon, Banyuwangi, dan Pamekasan, yang dirancang untuk UMKM daerah mendapatkan keterampilan digital terkait strategi bisnis & cara mengembangkan usaha mereka yang lebih berkelanjutan.</p>'+
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
                            $('#feedback-voting').alertNotification('danger', response.message);
                            scrollTop();
                        }

                    }
                });
            });

            var scrollTop = function() {
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            }
        // --------------------------- end load data voting ---------------------------

    });
</script>