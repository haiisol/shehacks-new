<section class="hero-section hero-sm section">
    <div class="container">
        <div class="content center">
            <h2 class="title section-heading-lg mb-0">Gallery</h2>
        </div>
    </div>
</section>


<section class="gallery-section section section-lg pt-0">
    <div class="container">
        <div class="inner">
            <div class="gallery-area row" id="load_data_gallery"></div>    

            <div class="load-more-area d-none" id="show_load_more">
                <a href="javascript:void(0)" id="btn-load-more" class="btn btn-light btn-arrow"><span>Load More</span></a>
            </div>
        </div>
    </div>
</section>

<input type="hidden" name="offset" id="offset" value="0">

<script type="text/javascript">
    $(document).ready(function() {

        // --------------------------- load data gallery ---------------------------
            var limit    = 15;
            var offset   = $('#offset').val();

            get_data(limit, offset, 'html');

            function get_data(limit, offset, load) {
                $.ajax({
                    method   : 'POST',
                    url      : '<?php echo base_url();?>page/gallery/fetch_data_gallery',
                    data     : { limit:limit, offset:offset },
                    dataType : 'json',
                    beforeSend: function() {},
                    success:function(response) {
                        // reset
                        $('#btn-load-more').buttonLoader('stop');

                        // data return
                        if (response.status == 1 ) {

                            var load_data = '';

                            $.each(response.data, function(i, val) {
                                
                                if (val.image) {
                                    
                                    load_data += 
                                        '<div class="col-lg-4 mb-4">'+
                                            '<div class="gallery-item">'+
                                                '<a href="'+val.image+'">'+
                                                    '<img src="'+val.image+'" alt="'+val.heading+'" class="img-fluid gallery-img hv-obj-zoom">'+
                                                '</a>'+
                                            '</div>'+
                                        '</div>';
                                } 
                            });

                            $('#offset').val(response.offset);

                            if (response.load_more == 1) {
                                $('#show_load_more').removeClass('d-none');
                            } else {
                                $('#show_load_more').addClass('d-none');
                            }

                            if (load == 'html') {
                                $('#load_data_gallery').html(load_data);
                            } else {
                                $('#load_data_gallery').append(load_data);
                            }
                        }

                        // magnific popup
                        $('.gallery-item > a').magnificPopup({
                            type: 'image',
                            preloader: true,
                            closeOnContentClick: true,
                            closeBtnInside: true,
                            fixedContentPos: true,
                            mainClass: 'mfp-no-margins mfp-with-zoom',
                            gallery: {
                                enabled: true
                            },
                            image: {
                                verticalFit: true
                            },
                            zoom: {
                                enabled: true,
                                duration: 400
                            }
                        });
                    }
                });
            }

            $(document).on('click', '#btn-load-more',function() {
                
                $(this).buttonLoader('start');

                var offset = $('#offset').val();

                get_data(limit, offset, 'append');
            });
        // --------------------------- end load data gallery ---------------------------
    });
</script>