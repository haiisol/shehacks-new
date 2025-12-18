<?php 
    $web = $this->main_model->get_admin_web(); 
    $logged_in_front = $this->session->userdata('logged_in_front');
    
    if ($web['logo']) {
        $logo = $this->main_model->url_image($web['logo'], 'image-logo');
    }

    if ($web['logo_sponsor']) {
        $logo_sponsor = $this->main_model->url_image($web['logo_sponsor'], 'image-logo');
    }
?>

<section class="coming-soon-section section section-sm">
	<div class="container">
        <img src="<?php echo base_url();?>assets/front/img/background/p2.webp" alt="Cover" width="660" height="660" class="img-fluid lazyload cover c1">
        <img src="<?php echo base_url();?>assets/front/img/background/p2.webp" alt="Cover" width="660" height="660" class="img-fluid lazyload cover c2">

        <div class="inner">
            <div class="brand-area">
                <?php if ($web['logo']) { ?>
                    <img src="<?php echo $logo; ?>" alt="<?php echo $web['name']; ?>" width="180" height="50" class="img-fluid lazyload brand-img mt-2">
                <?php } ?>
                <?php if ($web['logo_sponsor']) { ?>
                    <img src="<?php echo $logo_sponsor; ?>" alt="Logo Sponsor" width="180" height="50" class="img-fluid lazyload brand-img">
                <?php } ?>
            </div>

            <div class="content-area">
                <div class="section-title">
                    <p class="subtitle section-description">#SpiritOfInovation</p>
                    <h2 class="title section-heading-lg">Coming Soon</h2>
                    <h1 class="title">SheHacks 2025</h1>
                </div>
            </div>

            <div class="countdown-box">
                <ul class="countdown-area" id="load_data_countdown">
                    <li class="countdown-item">
                        <span class="countdown-value days">00</span><p class="countdown-label days_text">Days</p>
                    </li>
                    <li class="countdown-item">
                        <span class="countdown-value hours">00</span><p class="countdown-label hours_text">Hours</p>
                    </li>
                    <li class="countdown-item">
                        <span class="countdown-value minutes">00</span><p class="countdown-label minutes_text">Minutes</p>
                    </li>
                    <li class="countdown-item">
                        <span class="countdown-value seconds">00</span><p class="countdown-label seconds_text">Seconds</p>
                    </li>
                </ul>
            </div>

            <p class="description section-description-md">Program inovasi untuk perempuan Indonesia yang bertujuan untuk mengurangi kesenjangan gender pada ekonomi, pendidikan, kesehatan, dan lingkungan melalui inovasi teknologi.</p>
        </div>
	</div>
</section>

<script src="<?php echo base_url();?>assets/front/vendor/jquery-countdown/jquery.countdown.min.js"></script>

<script type="text/javascript">
    $(function() {
        $('#load_data_countdown').countdown({
            date   : '<?php echo date('m/d/Y H:i:s', strtotime($coming_soon_date)); ?>',
            offset : +7,
            day    : 'Day',
            days   : 'Days',
            hideOnComplete: true
        }, function (container) {
            reload_countdown();
        });

        function reload_countdown() {
            $.ajax({
                url      : '<?php echo base_url();?>home/reload_countdown',
                dataType : 'json',
                beforeSend: function() {},
                success:function(response) {
                    location.reload();
                }
            });
        }
    });
</script>