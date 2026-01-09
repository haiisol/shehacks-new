<?php
$cm_user        = $menu['data_user'] ?? 'd-none';
$cm_report      = $menu['report'] ?? 'd-none';
$cm_blog        = $menu['blog'] ?? 'd-none';
$cm_modul       = $menu['modul'] ?? 'd-none';
$cm_webinar     = $menu['webinar'] ?? 'd-none';
$cm_contact     = $menu['contact'] ?? 'd-none';
$cm_voting      = $menu['voting'] ?? 'd-none';
$cm_startup     = $menu['startup'] ?? 'd-none';

// group menu master
$cm_master      = $menu['data_master'] ?? 'd-none';

// group menu pengaturan
$cm_content_page    = $menu['content_page'] ?? 'd-none';
$cm_content_home    = $menu['content_home'] ?? 'd-none';
$cm_content_program = $menu['content_program'] ?? 'd-none';

$cm_website     = $menu['website'] ?? 'd-none';
$cm_akun_email  = $menu['akun_email'] ?? 'd-none';

$cm_user_role   = $menu['user_role'] ?? 'd-none';
$cm_operator    = $menu['operator'] ?? 'd-none';

// group logic
$groupm_setting = ($cm_website === 'd-none' && $cm_akun_email === 'd-none') ? 'd-none' : '';
$groupm_akun    = ($cm_user_role === 'd-none' && $cm_operator === 'd-none') ? 'd-none' : '';

// label menu
$labelm_setting = (
    $groupm_setting === 'd-none' &&
    $groupm_akun === 'd-none' &&
    $cm_content_page === 'd-none' &&
    $cm_content_home === 'd-none' &&
    $cm_content_program === 'd-none'
) ? 'd-none' : '';

// --------------------------- end group menu pengaturan ---------------------------

?>

<!-- sidebar -->
<aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="<?php echo $favicon_img; ?>" class="logo-icon" alt="<?php echo $site_name; ?>">
        </div>
        <div>
            <img src="<?php echo base_url(); ?>assets/backoffice/images/shehacks-text.png" class="logo-text logo-icon-text" alt="<?php echo $site_name; ?>">
        </div>
        <div class="toggle-icon ms-auto"><ion-icon name="menu-sharp"></ion-icon></div>
    </div>

    <ul class="metismenu" id="menu">
        <li>
            <a href="<?php echo base_url(); ?>panel-dashboard">
                <div class="parent-icon"><ion-icon name="pie-chart-sharp"></ion-icon></div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        <li class="<?php echo $cm_report; ?>">
            <a href="javascript:void(0);" class="has-arrow">
                <div class="parent-icon"><ion-icon name="stats-chart-sharp"></ion-icon></div>
                <div class="menu-title">Analytic Report</div>
            </a>
            <ul>
                <li class=""><a href="<?php echo base_url(); ?>admin/report/visitor"><ion-icon name="ellipse-outline"></ion-icon>Report Pengunjung</a></li>
                <li class=""><a href="<?php echo base_url(); ?>admin/report/blog"><ion-icon name="ellipse-outline"></ion-icon>Report Blog/berita</a></li>
                <li class=""><a href="<?php echo base_url(); ?>admin/report/webinar"><ion-icon name="ellipse-outline"></ion-icon>Report Webinar</a></li>
                <li class=""><a href="<?php echo base_url(); ?>admin/report/cta_btn"><ion-icon name="ellipse-outline"></ion-icon>Report CTA</a></li>
                <li class=""><a href="<?php echo base_url(); ?>admin/report/lms"><ion-icon name="ellipse-outline"></ion-icon>Report LMS</a></li>
            </ul>
        </li>
        <li class="<?php echo $cm_user; ?>">
            <a href="<?php echo base_url(); ?>admin/user/data_user">
                <div class="parent-icon"><ion-icon name="people-sharp"></ion-icon></div>
                <div class="menu-title">Data User</div>
            </a>
        </li>

        <li class="<?php echo $cm_user; ?>">
            <a href="<?php echo base_url(); ?>admin/user/data_user_terkurasi">
                <div class="parent-icon"><ion-icon name="people-sharp"></ion-icon></div>
                <div class="menu-title">User Terkurasi</div>
            </a>
        </li>

        <li class="<?php echo $cm_contact; ?>">
            <a href="<?php echo base_url(); ?>admin/contact/contact">
                <div class="parent-icon"><ion-icon name="send-sharp"></ion-icon></div>
                <div class="menu-title">Data Contact</div>
            </a>
        </li>

        <li class="<?php echo $cm_startup; ?>">
            <a href="<?php echo base_url(); ?>admin/startup/startup">
                <div class="parent-icon"><ion-icon name="business-sharp"></ion-icon></div>
                <div class="menu-title">Data Startup</div>
            </a>
        </li>

        <li class="<?php echo $cm_voting; ?>">
            <a href="javascript:void(0);" class="has-arrow">
                <div class="parent-icon"><ion-icon name="ribbon-sharp"></ion-icon></div>
                <div class="menu-title">Data Voting</div>
            </a>
            <ul>
                <li class=""><a href="<?php echo base_url(); ?>admin/voting/voting"><ion-icon name="ellipse-outline"></ion-icon>Data Voting</a></li>
                <li class=""><a href="<?php echo base_url(); ?>admin/voting/hasil"><ion-icon name="ellipse-outline"></ion-icon>Hasil Voting</a></li>
            </ul>
        </li>

        <li class="<?php echo $cm_blog; ?>">
            <a href="javascript:void(0);" class="has-arrow">
                <div class="parent-icon"><ion-icon name="newspaper-sharp"></ion-icon></div>
                <div class="menu-title">Data Artikel</div>
            </a>
            <ul>
                <li class=""><a href="<?php echo base_url(); ?>admin/blog/blog-kategori"><ion-icon name="ellipse-outline"></ion-icon>Kategori</a></li>
                <li class=""><a href="<?php echo base_url(); ?>admin/blog/blog"><ion-icon name="ellipse-outline"></ion-icon>Blog</a></li>
            </ul>
        </li>

        <li class="<?php echo $cm_modul; ?>">
            <a href="javascript:void(0);" class="has-arrow">
                <div class="parent-icon"><ion-icon name="book-sharp"></ion-icon></div>
                <div class="menu-title">Modul Pembelajaran</div>
            </a>
            <ul>
                <li class=""><a href="<?php echo base_url(); ?>admin/modul/modul"><ion-icon name="ellipse-outline"></ion-icon>Modul</a></li>
                <li class=""><a href="<?php echo base_url(); ?>admin/modul/quiz"><ion-icon name="ellipse-outline"></ion-icon>Quiz</a></li>
                <li class=""><a href="<?php echo base_url(); ?>admin/modul/report"><ion-icon name="ellipse-outline"></ion-icon>Progress User</a></li>
            </ul>
        </li>

        <li class="<?php echo $cm_webinar; ?>">
            <a href="<?php echo base_url(); ?>admin/webinar/webinar">
                <div class="parent-icon"><ion-icon name="videocam-sharp"></ion-icon></div>
                <div class="menu-title">Data Webinar</div>
            </a>
        </li>


        <li class="menu-label <?php echo $cm_master; ?>">MASTER</li>

        <li class="<?php echo $cm_master; ?>">
            <a href="javascript:void(0);" class="has-arrow">
                <div class="parent-icon"><ion-icon name="server-sharp"></ion-icon></div>
                <div class="menu-title">Data Master</div>
            </a>
            <ul>
                <li class=""><a href="<?php echo base_url(); ?>admin/master/pendidikan"><ion-icon name="ellipse-outline"></ion-icon>Tingkat Pendidikan</a></li>
                <li class=""><a href="<?php echo base_url(); ?>admin/master/dapatkan_informasi"><ion-icon name="ellipse-outline"></ion-icon>Dapatkan Informasi</a></li>
                <li class=""><a href="<?php echo base_url(); ?>admin/master/sector"><ion-icon name="ellipse-outline"></ion-icon>Sector</a></li>
            </ul>
        </li>


        <li class="menu-label <?php echo $labelm_setting; ?>">PENGATURAN</li>

        <li class="<?php echo $cm_content_home; ?>">
            <a href="javascript:void(0);" class="has-arrow">
                <div class="parent-icon"><ion-icon name="laptop-sharp"></ion-icon></div>
                <div class="menu-title">Content Home</div>
            </a>
            <ul>
                <li class=""><a href="<?php echo base_url(); ?>admin/content/banner_popup"><ion-icon name="ellipse-outline"></ion-icon>Banner Popup</a></li>
                <li class=""><a href="<?php echo base_url(); ?>admin/content/home/intro"><ion-icon name="ellipse-outline"></ion-icon>Intro header</a></li>
                <li class=""><a href="<?php echo base_url(); ?>admin/content/home/agenda"><ion-icon name="ellipse-outline"></ion-icon>Agenda</a></li>
                <li class=""><a href="<?php echo base_url(); ?>admin/content/home/partner"><ion-icon name="ellipse-outline"></ion-icon>Partner</a></li>
                <li class=""><a href="<?php echo base_url(); ?>admin/content/home/rangkaian_event"><ion-icon name="ellipse-outline"></ion-icon>Rangkaian Event</a></li>
                <li class=""><a href="<?php echo base_url(); ?>admin/content/home/testimoni"><ion-icon name="ellipse-outline"></ion-icon>Testimoni</a></li>
                <li class=""><a href="<?php echo base_url(); ?>admin/content/home/video"><ion-icon name="ellipse-outline"></ion-icon>Video Info</a></li>
            </ul>
        </li>

        <li class="<?php echo $cm_content_program; ?>">
            <a href="javascript:void(0);" class="has-arrow">
                <div class="parent-icon"><ion-icon name="laptop-sharp"></ion-icon></div>
                <div class="menu-title">Content Program</div>
            </a>
            <ul>
                <li class=""><a href="<?php echo base_url(); ?>admin/content/program/intro"><ion-icon name="ellipse-outline"></ion-icon>Intro header</a></li>
                <li class=""><a href="<?php echo base_url(); ?>admin/content/program/topik"><ion-icon name="ellipse-outline"></ion-icon>Topik</a></li>
            </ul>
        </li>



        <li class="<?php echo $cm_content_page; ?>">
            <a href="javascript:void(0);" class="has-arrow">
                <div class="parent-icon"><ion-icon name="browsers-sharp"></ion-icon></div>
                <div class="menu-title">Content Page</div>
            </a>
            <ul>
                <li class=""><a href="<?php echo base_url(); ?>admin/content/faq"><ion-icon name="ellipse-outline"></ion-icon>FAQ</a></li>
                <li class=""><a href="<?php echo base_url(); ?>admin/content/privacy-policy"><ion-icon name="ellipse-outline"></ion-icon>Privacy Policy</a></li>
                <li class=""><a href="<?php echo base_url(); ?>admin/content/terms-condition"><ion-icon name="ellipse-outline"></ion-icon>Terms Condition</a></li>
            </ul>
        </li>

        <li class="<?php echo $groupm_setting; ?>">
            <a href="javascript:void(0);" class="has-arrow">
                <div class="parent-icon"><ion-icon name="settings-sharp"></ion-icon></div>
                <div class="menu-title">Setting</div>
            </a>
            <ul>
                <li class="<?php echo $cm_website; ?>"><a href="<?php echo base_url(); ?>admin/setting/website"><ion-icon name="ellipse-outline"></ion-icon>Website Profile</a></li>
                <li class="<?php echo $cm_akun_email; ?>"><a href="<?php echo base_url(); ?>admin/setting/email-akun"><ion-icon name="ellipse-outline"></ion-icon>Email Akun</a></li>
            </ul>
        </li>

        <li class="<?php echo $groupm_akun; ?>">
            <a href="javascript:void(0);" class="has-arrow">
                <div class="parent-icon"><ion-icon name="person-add-sharp"></ion-icon></div>
                <div class="menu-title">Akun</div>
            </a>
            <ul>
                <li class="<?php echo $cm_operator; ?>"> <a href="<?php echo base_url(); ?>admin/operator"><ion-icon name="ellipse-outline"></ion-icon>Data Operator</a></li>
                <li class="<?php echo $cm_user_role; ?>"> <a href="<?php echo base_url(); ?>admin/privilage/user_role"><ion-icon name="ellipse-outline"></ion-icon>User Role</a></li>
            </ul>
        </li>
    </ul>
</aside>

<!-- top header -->
<header class="top-header">
    <nav class="navbar navbar-expand gap-3">
        <div class="mobile-menu-button"><ion-icon name="menu-sharp"></ion-icon></div>

        <div class="top-navbar-right ms-auto">
            <ul class="navbar-nav align-items-center">

                <li class="nav-item">
                    <a class="nav-link dark-mode-icon" href="javascript:void(0);">
                        <div class="mode-icon"><ion-icon name="moon-sharp"></ion-icon> </div>
                    </a>
                </li>
                <li class="nav-item dropdown dropdown-user-setting">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="user-setting">
                            <p class="mb-0"><?php echo strtoupper(initial_value($admin['nama_admin'])); ?></p>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex flex-row align-items-center gap-2">
                                    <img src="<?php echo $admin_img; ?>" alt="" class="rounded-circle" width="54" height="54">
                                    <div class="">
                                        <h6 class="mb-0 dropdown-user-name"><?php echo $nama_admin; ?></h6>
                                        <small class="mb-0 dropdown-user-designation text-secondary"><?php echo $role; ?></small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo base_url(); ?>admin/edit_profile">
                                <div class="d-flex align-items-center">
                                    <div class=""><ion-icon name="person-outline"></ion-icon></div>
                                    <div class="ms-3"><span>Profile</span></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo base_url(); ?>logout-admin">
                                <div class="d-flex align-items-center">
                                    <div class=""><ion-icon name="log-out-outline"></ion-icon></div>
                                    <div class="ms-3"><span>Logout</span></div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>