<?php 
    $web = $this->main_model->get_admin_web(); 
?>

<section class="testimonial-section section section-lg">
    <div class="container">
        <div class="head-inner">    
            <div class="section-title white">
                <p class="subtitle section-description d-inline-block">Testimoni Peserta</p>
                <h2 class="title section-heading">Join Our Supportive Community 😊</h2>
                <p class="description">Bergabung bersama alumni Shehacks sebelumnya</p>
            </div>

            <?php 
                $data = [
                    'class' => 'btn btn-white btn-hover-arrow-right fix',
                ];
                $this->load->view('component/ui/button_register', $data); 
            ?>
        </div>
    </div>

    <div class="container-fluid px-0">
        <div class="content-inner">
            <div class="swiper-container">
                <div class="swiper" id="swiper-testimonial">
                    <div class="swiper-wrapper" id="load_data_testimoni"></div>
                </div>
                
                <div class="swiper-pagination pagination-bullet-2 swiper-pagination-testimonial"></div>
            </div>
        </div>
    </div>
</section>


<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
            url      : '<?php echo base_url();?>fetch/fetch_data/fetch_data_testimoni',
            dataType : 'json',
            beforeSend: function() {},
            success: function(response) {
                var load_data = '';
                
                if (response.status == 1) {

                    $.each(response.data, function(i, val) {
                        
                        load_data += 
                            '<div class="swiper-slide">'+
                                ' <div class="testimonial-item">'+
                                    '<div class="author">'+
                                        '<div class="photo">'+
                                            '<img src="'+val.image+'" alt="'+val.subheading+'" class="img-fluid swiper-lazy">'+
                                        '</div>'+

                                        '<div class="info">'+
                                            '<p class="name">'+val.heading+'</p>'+
                                            '<p class="company">'+val.subheading+'</p>'+
                                        '</div>'+
                                    '</div>'+

                                    '<p class="review section-description-md">'+val.content+'</p>'+
                                '</div>'+
                            '</div>';
                    });

                    $('#load_data_testimoni').html(load_data);

                    new Swiper('#swiper-testimonial', {
                        centeredSlides: true,
                        lazy: true,
                        speed: 2000,
                        autoplay: {
                            delay: 9000,
                            disableOnInteraction: false,
                        },
                        mousewheel: false,
                        loop: true,
                        lazy: true,
                        preloadImages: true,
                        navigation: {
                            nextEl: '.swiper-testimonial-next',
                            prevEl: '.swiper-testimonial-prev',
                        },
                        pagination: {
                            el: '.swiper-pagination-testimonial',
                            dynamicBullets: false,
                            clickable: true,
                        },
                        breakpoints: {
                            0: {
                                slidesPerView: 1.2,
                                spaceBetween: 15,
                            },
                            580: {
                                slidesPerView: 1.8,
                                spaceBetween: 20,
                            },
                            1200: {
                                slidesPerView: 2.4,
                                spaceBetween: 20,
                            },
                            1400: {
                                slidesPerView: 3.2,
                                spaceBetween: 20,
                            }
                        }
                    });
                }
            }
        });
    });
</script>