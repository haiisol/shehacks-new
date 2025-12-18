<section class="hero-section style-1 hero-md section">
    <img data-src="<?php echo base_url();?>assets/front/img/background/bg-hero.webp" alt="cover" class="img-fluid lazyload hero-cover">
    <div class="container">
        <div class="content center">
            <h2 class="title section-heading-lg fw-500 mb-0">Webinar SheHacks 2025</h2>
        </div>
    </div>
</section>


<section class="video-section section section-md">
    <div class="container">
        <div class="section-title">
            <p class="description section-description fw-500">Pilihlah dan tonton webinar di bawah ini dan dapatkan inspirasi dari mentor berpengalaman.</p>
        </div>

        <div class="row" id="load_data_video"></div>

        <div class="load-more-area d-none" id="show_load_more">
            <a href="javascript:void(0)" id="btn-load-more" class="btn btn-light btn-arrow"><span>Load More</span></a>
        </div>
    </div>
</section>

<input type="hidden" name="offset" id="offset" value="0">

<script type="text/javascript">
    $(document).ready(function() {

        // --------------------------- load data Webinar ---------------------------
            var limit    = 15;
            var offset   = $('#offset').val();

            get_data(limit, offset, 'html');

            function get_data(limit, offset, load) {
                $.ajax({
                    url      : '<?php echo base_url();?>page/webinar/fetch_data_webinar',
                    data     : { limit:limit, offset:offset },
                    dataType : 'json',
                    beforeSend: function() {},
                    success:function(response) {
                        var load_data = '';
                        
                        if (response.status == 1) {
                            $.each(response.data, function(i, val) {
                                load_data += `
                                    <div class="col-lg-6 col-12">
                                        <div>
                                            <div class="video-area">
                                                <a href="https://www.youtube.com/watch?v=${val.video}" class="play-video video-popup" data="${val.id_webinar}"><span></span></a>
                                                <img src="<?= before_load(); ?>" data-src="https://img.youtube.com/vi/${val.video}/hqdefault.jpg" alt="${val.heading}" class="cover cover-video-showcase lazyload">
                                                <video class="player" id="player-video-showcase" autoplay preload crossoriginx controls="false" controlslist="false" muted="false">
                                                    <source src="https://www.youtube.com/embed/${val.video}" type="video/mp4">
                                                </video>
                                            </div>
                                            <div class="mt-3">
                                                <h3 class="title section-description-lg mb-0">${val.heading}</h3>
                                            </div>
                                        </div>
                                    </div>`;
                            });

                            $('#offset').val(response.offset);

                            if (load == 'html') {
                                $('#load_data_video').html(load_data);
                            } else {
                                $('#load_data_video').append(load_data);
                            }

                        }

                        lazyload();

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
                });
            }

            $(document).on('click', '.play-video', function(e) {
                e.preventDefault();
                var id = $(this).attr('data');

                $.ajax({
                    method   : 'POST',
                    url      : '<?php echo base_url();?>analytic/post_webinar_viewer',
                    data     : { id:id },
                    dataType : 'json',
                    success: function(response) { }
                });
                return false;
            });


            $(document).on('click', '#btn-load-more',function() {
                $(this).buttonLoader('start');
                var offset = $('#offset').val();
                get_data(limit, offset, 'append');
            });
        // --------------------------- end load data Webinar ---------------------------
        
    });
</script>