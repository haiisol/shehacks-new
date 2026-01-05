<section class="yfuture-section section section-lg pb-5">
    <img src="<?php echo base_url();?>assets/front/img/background/p1.webp" alt="Pattern Image" class="img-fluid lazyload p1">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 m-auto">        
                <div class="inner mw-100">
                    <img src="<?php echo base_url();?>assets/front/img/icon/img-1.webp" alt="Your Future Image" class="img-fluid lazyload">

                    <div class="section-title">    
                        <h2 class="title section-heading-lg">Nantikan SheHacks 2025 <br>& wujudkan inovasimu jadi solusi nyata!</h2>
                        <p class="description section-description">Sebuah program inovasi untuk perempuan Indonesia yang bertujuan untuk menutup kesenjangan gender dalam hasil ekonomi, pendidikan, kesehatan, dan lingkungan dengan memanfaatkan teknologi.</p>
                        
                        <?php if ($logged_in_front == FALSE) { ?>
                            <a href="<?php echo base_url();?>login" data-bs-toggle="modal" class="btn">Masuk</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>