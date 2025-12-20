<footer class="footer section">
    <div class="container">
        <div class="footer-wrap">
            <div class="top-area">
                <div class="logo-area">
                    <img data-src="<?php echo $logo; ?>" alt="logo" class="img-fluid lazyload footer-logo" width="240" height="65">
                </div>

                <div class="social-area">
                    <ul class="footer-list social-media">
                        <?php if ($instagram) { ?>
                            <li>
                                <a href="<?php echo $instagram; ?>" class="icon" target="_blank" aria-label="Follow us on Instagram">
                                    <img data-src="<?php echo base_url(); ?>assets/front/img/icon/sm-instagram.webp" alt="Instagram" width="26" height="26" class="img-fluid lazyload">
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($facebook) { ?>
                            <li>
                                <a href="<?php echo $facebook; ?>" class="icon" target="_blank" aria-label="Follow us on Facebook">
                                    <img data-src="<?php echo base_url(); ?>assets/front/img/icon/sm-facebook.webp" alt="Facebook" width="26" height="26" class="img-fluid lazyload">
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($youtube) { ?>
                            <li>
                                <a href="<?php echo $youtube; ?>" class="icon" target="_blank" aria-label="Follow us on Youtube">
                                    <img data-src="<?php echo base_url(); ?>assets/front/img/icon/sm-youtube.webp" alt="Youtube" width="26" height="26" class="img-fluid lazyload">
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($twitter) { ?>
                            <li>
                                <a href="<?php echo $twitter; ?>" class="icon" target="_blank" aria-label="Follow us on Twitter">
                                    <img data-src="<?php echo base_url(); ?>assets/front/img/icon/sm-twitter.webp" alt="Twitter" width="26" height="26" class="img-fluid lazyload">
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>

            <div class="bottom-area">
                <div class="footer-link">
                    <div class="link-item">
                        <a href="mailto:admin@shehacks.id" target="_blank" class="hover">admin@shehacks.id</a>
                    </div>
                    <div class="link-item">
                        <a href="<?php echo base_url(); ?>privacy-policy" class="hover">Privacy Policy</a>
                    </div>
                    <div class="link-item">
                        <a href="<?php echo base_url(); ?>terms-of-service" class="hover">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="copyright">
            <p class="crname section-description-md">Shehacks 2025</p>
            <p class="mb-0">Program inovasi untuk perempuan Indonesia yang bertujuan untuk mengurangi kesenjangan gender pada ekonomi, pendidikan, kesehatan, dan lingkungan melalui inovasi teknologi.</p>
        </div>
    </div>
</footer>