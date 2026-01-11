<?php 
    // Kondisi demo 2025
    if (!$event_running) {
        if ($page != 'home_announcement' AND $page != 'page/coming_soon' AND $page != 'artikel/artikel' AND $page != 'page/faq' AND $page != 'page/contact' AND $page != 'page/webinar') {
            $menu = 'show';
        } else {
            $menu = 'hide';
        }
    } else {
        $menu = 'show';
    }
?>

<nav id="navbar" class="navbar navbar-expand-lg">
    <div class="container">
        <div class="navbar-inner">
            <div class="navbar-brand">
                <?php if ($logo) { ?>
                    <a class="brand-item" href="<?php echo base_url(); ?>">
                        <img src="<?= before_load(); ?>" data-src="<?php echo $logo; ?>" alt="<?php echo $site_name; ?>" width="180" height="50" class="img-fluid lazyload brand-img mt-1">
                    </a>
                <?php } ?>
                <?php if ($logo_sponsor) { ?>
                    <a class="brand-item" href="<?php echo base_url(); ?>">
                        <img src="<?= before_load(); ?>" data-src="<?php echo $logo_sponsor; ?>" alt="Sponsor" width="180" height="50" class="img-fluid lazyload brand-img">
                    </a>
                <?php } ?>
            </div>

            <div class="navbar-area">
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav" id="navbar-navlist">

                        <!-- Kondisi demo 2025 -->
                        <?php if ($menu == 'show') { ?>
                            <li class="nav-item">
                                <a class="nav-link <?php if ($page == 'page/program') { echo 'active'; } ?>" href="<?php echo base_url();?>program">Program</a>
                            </li>
                        <?php } ?>

                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'page/webinar') { echo 'active'; } ?>" href="<?php echo base_url();?>webinar">Webinar</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'artikel/artikel') { echo 'active'; } ?>" href="<?php echo base_url();?>artikel">Artikel</a>
                        </li> 

                        <!-- Kondisi demo 2025 -->
                        <?php if ($menu == 'show') { ?>
                            <li class="nav-item">
                                <a class="nav-link <?php if ($page == 'page/faq') { echo 'active'; } ?>" href="<?php echo base_url();?>faq">FAQ</a>
                            </li>
                        <?php } ?>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'startups/startups') { echo 'active'; } ?>" href="<?php echo base_url();?>startups">Portal Alumni</a>
                        </li>

    
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'page/impact_report') { echo 'active'; } ?>" href="<?php echo base_url();?>impact-report">Impact Report</a>
                        </li>

                        <!-- <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'startups/startups') { echo 'active'; } ?>" href="<?php echo base_url();?>startups">Demo Start-Up</a>
                        </li> -->

                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'page/contact') { echo 'active'; } ?>" href="<?php echo base_url();?>contact">Kontak</a>
                        </li>
                    </ul>
                </div>

                <div class="navbar-action">
                    <?php 
                        if ($logged_in_front == FALSE) {
                            if ($register_button) {
                                $btn_nav = 'Masuk/Daftar';
                            } else {
                                $btn_nav = ($menu == 'show' ? 'Masuk/Daftar' : 'Masuk');
                            } 
                        ?>

                        <div class="nav-item">
                            <a class="nav-link btn cta-btn-trigger d-lg-inline d-none" data="Masuk/Daftar" href="<?php echo base_url();?>login"><span><?php echo $btn_nav; ?></span></a>
                            <a class="nav-link btn cta-btn-trigger d-lg-none d-flex" data="Masuk/Daftar" href="<?php echo base_url();?>login"><i class="fa fa-user"></i></a>
                        </div>
                    <?php } else { ?>
                        <div class="nav-item nav-item-user dropdown">
                            <a class="nav-link dropdown-toggle" href="#" onclick="event.preventDefault();" data-bs-toggle="dropdown">
                                <div class="img-user"><?php echo strtoupper(initial_value($user_name)); ?></div>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item cta-btn-trigger" data="Button Navbar Dashboard" href="<?php echo base_url();?>dashboard">
                                        <div class="icon"><i class="fa-solid fa-dashboard"></i></div>
                                        <div class="text"><span>Dashboard</span></div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item cta-btn-trigger" data="Button Navbar Profile" href="<?php echo base_url();?>dashboard?profile">
                                        <div class="icon"><i class="fa-solid fa-user-pen"></i></div>
                                        <div class="text"><span>Profile</span></div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item cta-btn-trigger" data="Button Navbar Dashboard" href="<?php echo base_url();?>dashboard?password">
                                        <div class="icon"><i class="fa-solid fa-unlock"></i></div>
                                        <div class="text"><span>Password</span></div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item cta-btn-trigger" data="Button Navbar Logout" href="<?php echo base_url();?>logout">
                                        <div class="icon"><i class="fa-solid fa-right-from-bracket"></i></div>
                                        <div class="text"><span>Logout</span></div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php } ?>
                    
                    <div class="nav-item d-lg-none">
                        <div class="nav-link">
                            <a href="#" onclick="event.preventDefault();" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="icon-bar top-bar"></span>
                                <span class="icon-bar middle-bar"></span>
                                <span class="icon-bar bottom-bar"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>