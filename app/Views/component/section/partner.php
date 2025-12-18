<section class="partner-section section section-lg pt-4">
    <div class="container">
        <div class="head-inner mb-5">    
            <div class="section-title text-center">
                <h2 class="title section-heading">Partner Pendukung Program SheHacks</h2>
            </div>
        </div>

        <div class="swiper-container">
            <div class="swiper" id="swiper-partner">
                <div class="swiper-wrapper" id="load_data_partner_item"></div>
            </div>
            
            <div class="swiper-pagination pagination-bullet-2 swiper-pagination-testimonial"></div>
        </div>
        
        <!-- <div id="load_data_partner"></div> -->
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function() {
        /* --------------------------- load data Partner --------------------------- */
            $.ajax({
                url      : '<?php echo base_url();?>fetch/fetch_data/fetch_data_partner',
                dataType : 'json',
                beforeSend: function() {},
                success: function(response) {
                    // var load_data = '';
                    var partner_item = '';

                    if (response.status === 1) {
                        // $.each(response.data, function(i, val) {

                        //     var partner_item = '';
                        //     $.each(val.partner, function(i1, val1) {
                        //         partner_item += `
                        //             <div class="partner-item">
                        //                 <img data-src="${val1.image}" src="<?= before_load() ?>" alt="${val1.nama}" class="img-fluid lazyload partner-img" width="168" height="72">
                        //             </div>`;
                        //     });

                        //     load_data += `
                        //         <div class="partner-area">
                        //             <div class="partner-list">
                        //                 ${partner_item}
                        //             </div>
                        //         </div>`;
                        //         /* <h3 class="partner-title section-description">'+val.nama+'</h3> */
                        // });

                        $.each(response.data, function(i, val) {
                            $.each(val.partner, function(i1, val1) {
                                partner_item += `
                                    <div class="swiper-slide">
                                        <figure class="partner-item">
                                            <img src="${val1.image}" alt="${val1.nama}" class="img-fluid partner-img" width="168" height="72">
                                        </figure>
                                    </div>`;
                            });
                        });
                    }

                    $('#load_data_partner_item').html(partner_item);

                    // $('#load_data_partner').html(load_data);
                    lazyload();

                    new Swiper('#swiper-partner', {
                        centeredSlides: false,
                        speed: 5000 ,
                        autoplay_speed: 0,
                        autoplay: {
                            delay: 0,
                            disableOnInteraction: false,
                        },
                        grabCursor: true,
                        a11y: false,
                        infinite: true,
                        mousewheel: false,
                        navigation: false,
                        allowTouchMove: false,
                        loop: true,
                        lazy: true,
                        spaceBetween: 20,
                        breakpoints: {
                            0: {
                                slidesPerView: 3,
                            },
                            580: {
                                slidesPerView: 4,
                            },
                            1200: {
                                slidesPerView: 5,
                            },
                            1400: {
                                slidesPerView: 5,
                            }
                        }
                    });
                }
            });
        /* --------------------------- end load data Partner --------------------------- */
    });
</script>