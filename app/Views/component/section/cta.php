<section class="yfuture-section section section-lg pb-5 overflow-hidden">
    <img src="<?php echo base_url();?>assets/front/img/background/p1.webp" alt="Pattern Image" class="img-fluid lazyload p1">
    <div class="container">
        <div class="inner">
            <img src="<?= before_load() ?>" data-src="<?php echo base_url();?>assets/front/img/icon/img-1.webp" alt="Your Future Image" class="img-fluid lazyload" width="120" height="123">

            <div class="section-title">    
                <h2 class="title section-heading-lg">Ready to Build Your Future?</h2>
                <p class="description section-description">Belajar langsung dari para mentor untuk melejitkan bisnis-mu</p>

                <?php 
                    $data = [
                        'text' => 'Daftar SheHacks 2025',
                        'class' => 'btn btn-hover-arrow-right',
                        'attributes' => 'data="Footer CTA Daftar"'
                    ]; 
                    echo view('component/ui/button_register', $data); 
                ?>
            </div>
        </div>
    </div>
</section>