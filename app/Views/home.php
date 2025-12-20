
<?php 
    echo view('component/section/hero_image');

    if ($voting_running) {
        echo view('component/voting'); 
    } 
?>

<section class="intro-section section section-lg">
    <img src="<?php echo base_url();?>assets/front/img/background/p1.webp" alt="Pattern Image" class="img-fluid lazyload p1">
    <div class="container">
        <div class="inner" id="load_data_intro_header"></div>
    </div>
</section>

<div class="bg-gradient-1 wrap-sec-1 section">
    <section class="join-section join-section-2 section section-lg">
        <div class="container">
            <div class="inner">
                <div class="content">
                    <div class="section-title white">
                        <h2 class="title section-heading">Bergabunglah bersama SheHacks 2025 dan wujudkan inovasimu jadi solusi nyata!</h2>
                    </div>

                    <ul class="list-check">
                        <li class="list-item">Program Inkubasi</li>
                        <li class="list-item">Kelas Eksklusif </li>
                        <li class="list-item">⁠Mentoring 1on1</li>
                        <li class="list-item">⁠Networking & Kolaborasi</li>
                        <li class="list-item">⁠Akses ke Investor</li>
                    </ul>

                    <?php 
                        $data = [
                            'class' => 'btn btn-white btn-hover-arrow-right fix',
                            'attributes' => ''
                        ];
                        echo view('component/ui/button_register', $data); 
                    ?>
                </div>

                <div class="thumb">
                    <img src="<?= before_load() ?>" data-src="<?php echo base_url();?>assets/front/img/thumb/thumb-3.webp" alt="Join Image" class="img-fluid lazyload thumb-img" width="648" height="648">
                </div>
            </div>
        </div>
    </section>
</div>

<section class="series-section section section-md pt-lg-4 pt-5 mt-3">
    <div class="container">
        <div class="section-title">
            <h2 class="title section-heading">Rangkaian Event SheHacks 2025</h2>
        </div>

        <div id="load_data_schedule"></div>
        <!-- <div class="series-note"><p>Webinar (selama 2 minggu sekali sejak April - Desember 2025)</p></div> -->
    </div>
</section>

<?php echo view('component/section/partner'); ?>

<div class="bg-gradient-1 wrap-sec-1 section">
    <?php echo view('component/section/cerita_inspiratif'); ?>
</div>

<?php echo view('component/section/cta'); ?>


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


<script>
    $(document).ready(function() {

        var logged_in_front = '<?php echo $logged_in_front; ?>';

        /* --------------------------- load data intro header --------------------------- */
            $.ajax({
                url      : '<?php echo base_url();?>fetch/fetch_data/fetch_data_intro',
                data     : { param: 'home_intro' },
                dataType : 'json',
                beforeSend: function() {},
                success:function(response) {
                    var load_data = '';
                    
                    if (response.status == 1) {

                        $.each(response.data, function(i, val) {

                            var btn_reg_stat = "<?php echo $register_button; ?>";
                            var web_running  = "<?php echo $event_running; ?>";

                            var btn_reg = '';

                            if (logged_in_front == 'TRUE') {
                                if (web_running == 'true') {
                                    if (btn_reg_stat == 'true') {
                                        btn_reg = `<a href="${val.button_url}" class="btn btn-hover-arrow-right cta-btn-trigger" data="Daftar Sekarang"><span>${val.button_text}</span></a>`;
                                    }
                                } else {
                                    btn_reg = `<a href="${val.button_url}" class="cta-btn-trigger btn btn-hover-arrow-right" data="Daftar Sekarang"><span>${val.button_text}</span></a>`;
                                }
                            }

                            load_data += `
                                <div class="row align-items-center">
                                    <div class="col-lg-7 m-auto">
                                        <div class="content">
                                            <div class="section-title">
                                                <p class="subtitle section-description-md d-inline-block">${val.subheading}</p>
                                                <h2 class="title section-heading-lg">${val.heading}</h2>
                                                <p class="description section-description">${val.content}</p>
                                            </div>

                                            <div class="act">
                                                ${btn_reg}
                                                <a href="${val.instagram}" target="_blank" class="btn btn-white btn-hover-icon-left cta-btn-trigger" data="Follow our Instagram"><span class="icon lni lni-instagram"></span> <span>Follow our Instagram</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;

                                /* <div class="thumb">
                                     <img src="${val.image}" alt="Shehacks Image" class="img-fluid lazyload thumb-img">
                                </div> */
                        });

                        $('#load_data_intro_header').html(load_data);
                    }
                }
            });
        /* --------------------------- end load data intro header --------------------------- */


        /* --------------------------- load data Schedule --------------------------- */
            $.ajax({
                url      : '<?php echo base_url();?>fetch/fetch_data/fetch_data_schedule',
                dataType : 'json',
                beforeSend: function() {},
                success: function(response) {
                    var load_data = '';
                    if (response.status === 1) {
                        $.each(response.data, function(i, val) {
                            load_data += `
                                <img src="<?= before_load(); ?>" data-src="${val.image}" alt="${val.heading}" class="img-fluid lazyload series-img image-desktop" width="1400" height="609">
                                <img src="<?= before_load(); ?>" data-src="${val.image_2}" alt="${val.heading}" class="img-fluid lazyload series-img image-mobile" width="516" height="750">`;
                        });

                        $('#load_data_schedule').html(load_data);
                    }

                    lazyload();
                }
            });
        /* --------------------------- end load data Schedule --------------------------- */

    });
</script>