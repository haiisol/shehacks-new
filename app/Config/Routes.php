<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// ================= Auth =================
$routes->get('login', 'Auth\Login::index');
$routes->get('login-verify', 'Auth\Login::login_verify');
$routes->get('logout', 'Auth\Login::logout');

$routes->get('register', 'Auth\Register::index');
$routes->get('register/verifikasi', 'Auth\Register::verifikasi');

$routes->get('lupa-password', 'Auth\LupaPassword::index');
$routes->post('verifikasi-lupa-password', 'Auth\LupaPassword::verifikasi');
$routes->post('form-lupa-password', 'Auth\LupaPassword::form_lupa_password');


// ================= Pages =================
$routes->get('contact', 'Page\Contact::index');
$routes->get('program', 'Page\Program::index');
$routes->get('gallery', 'Page\Gallery::index');
$routes->get('faq', 'Page\Faq::index');
$routes->get('privacy-policy', 'Page\PrivacyPolicy::index');
$routes->get('webinar', 'Page\Webinar::index');
$routes->get('terms-of-service', 'Page\TermsOfService::index');
$routes->get('impact-report', 'Page\ImpactReport::index');

$routes->get('coming-soon', 'Home::coming_soon');
$routes->get('preview', 'Home::preview');
$routes->get('preview-voting', 'Home::preview_voting');


// ================= Artikel =================
$routes->get('artikel', 'Artikel\Artikel::index');
$routes->get('artikel/(:any)/(:any)', 'Artikel\Artikel::detail/$1/$2');

$routes->get('artikel_data/(:any)', 'Artikel\ArtikelData::$1');

$routes->get('artikel-tags/(:any)/(:any)', 'Artikel\Artikel::tags/$1/$2');
$routes->get('artikel-kategori/(:any)/(:any)', 'Artikel\Artikel::tags/$1/$2');


// ================= Startups =================
$routes->get('startups', 'Startups\Startups::index');
$routes->get('startups/(:any)/(:any)', 'Startups\Startups::detail/$1/$2');
$routes->get('startups_data/(:any)', 'Startups\StartupsData::$1');


// ================= Dashboard =================
$routes->group('dashboard', ['filter' => 'frontauth'], function ($routes) {
    $routes->get('/', 'Dashboard\Dashboard::index');
    $routes->get('show_sertifikat', 'Dashboard\Dashboard::show_sertifikat');
});


// ================= Admin / Panel =================
$routes->get('panel', 'Admin\Auth\Login::index');

$routes->get('panel/auth-verify', 'Admin\Auth\Login::login_verify');
$routes->post('admin/auth/login/post_login', 'Admin\Auth\Login::post_login');
$routes->post('admin/auth/login/post_login_verify', 'Admin\Auth\Login::post_login_verify');

$routes->get('login-admin', 'Admin\Auth\Login::index');
$routes->get('logout-admin', 'Admin\Auth\Login::logout');

$routes->group('admin', ['filter' => 'adminauth'], function ($routes) {
    $routes->get('dashboard/dashboard/data_info', 'Admin\Dashboard\Dashboard::data_info');
    $routes->get('dashboard/dashboard/load_data_kategori', 'Admin\Dashboard\Dashboard::load_data_kategori');
    $routes->get('dashboard/dashboard/load_data_channel', 'Admin\Dashboard\Dashboard::load_data_channel');
    $routes->get('dashboard/dashboard/load_data_tingkat_pendidikan', 'Admin\Dashboard\Dashboard::load_data_tingkat_pendidikan');
    $routes->get('dashboard/dashboard/load_data_tingkat_pendidikan', 'Admin\Dashboard\Dashboard::load_data_tingkat_pendidikan');
    $routes->get('dashboard/dashboard/load_data_provinsi', 'Admin\Dashboard\Dashboard::load_data_provinsi');
    $routes->get('dashboard/dashboard/load_data_dapat_informasi', 'Admin\Dashboard\Dashboard::load_data_dapat_informasi');
  
    $routes->get('webinar/webinar', 'Admin\Webinar\Webinar::index');
    $routes->get('webinar/webinar/datatables', 'Admin\Webinar\Webinar::datatables');
    
    $routes->post('webinar/webinar/cek_value', 'Admin\Webinar\Webinar::cek_value');
    $routes->post('webinar/webinar/get_data', 'Admin\Webinar\Webinar::get_data');
    $routes->post('webinar/webinar/detail_data', 'Admin\Webinar\Webinar::detail_data');
    $routes->post('webinar/webinar/add_data', 'Admin\Webinar\Webinar::add_data');
    $routes->post('webinar/webinar/edit_data', 'Admin\Webinar\Webinar::edit_data');
    $routes->post('webinar/webinar/delete_data', 'Admin\Webinar\Webinar::delete_data');

    $routes->get('voting/voting', 'Admin\Voting\Voting::index');
    $routes->get('voting/voting/get_data', 'Admin\Voting\Voting::get_data');
    $routes->get('voting/voting/datatables', 'Admin\Voting\Voting::datatables');

    $routes->get('voting/hasil', 'Admin\Voting\Hasil::index');
    $routes->get('voting/hasil/datatables', 'Admin\Voting\Hasil::datatables');
    $routes->get('voting/hasil/export', 'Admin\Voting\Hasil::export');
    $routes->get('voting/hasil/export_detail/(:any)', 'Admin\Voting\Hasil::export_detail/$1');
    $routes->get('voting/hasil/datatables_detail/(:any)', 'Admin\Voting\Hasil::datatables_detail/$1');
    $routes->get('voting/hasil/detail/(:any)', 'Admin\Voting\Hasil::detail/$1');

    $routes->post('voting/voting/detail_data', 'Admin\Voting\Voting::detail_data');
    $routes->post('voting/voting/export', 'Admin\Voting\Voting::export');
    $routes->post('voting/voting/add_data', 'Admin\Voting\Voting::add_data');
    $routes->post('voting/voting/edit_data', 'Admin\Voting\Voting::edit_data');
    $routes->post('voting/voting/delete_data', 'Admin\Voting\Voting::delete_data');

    $routes->get('edit_profile', 'Admin\EditProfile::index');
    $routes->get('edit_profile/get_data', 'Admin\EditProfile::get_data');
    $routes->post('edit_profile/edit_data', 'Admin\EditProfile::edit_data');

    $routes->get('setting/website', 'Admin\Setting\Website::index');
    $routes->get('setting/website/get_data', 'Admin\Setting\Website::get_data');
    $routes->post('setting/website/edit_data', 'Admin\Setting\Website::edit_data');

    $routes->get('setting/email-akun', 'Admin\Setting\EmailAkun::index');
    $routes->get('setting/email-akun/get_data', 'Admin\Setting\EmailAkun::get_data');
    $routes->post('setting/email-akun/edit_data', 'Admin\Setting\EmailAkun::edit_data');
    $routes->post('setting/email-akun/testing_kirim', 'Admin\Setting\EmailAkun::testing_kirim');
    
    $routes->post('dashboard/dashboard/get_address', 'Admin\Dashboard\Dashboard::get_address');
     
});

$routes->group('', ['filter' => 'adminauth'], function ($routes) {
    $routes->get('panel-dashboard', 'Admin\Dashboard\Dashboard::index', ['filter' => 'access:dashboard']);
});

// Blog Tulis
$routes->get('admin/blogs/(:any)/(:any)', 'Admin\Blog\Blog::tulis/$1/$2');

// Modul
$routes->group('', ['filter' => 'frontauth'], function ($routes) {
    $routes->get('category/(:any)/(:any)', 'Modul\Modul::detail_modul/$1/$2');
    $routes->get('learn/(:any)/(:any)', 'Modul\ModulMaster::pelajari/$1/$2');
    $routes->get('sertifikat/(:any)/(:any)', 'Modul\ModulMaster::show_sertifikat/$1/$2');
    $routes->post('modul/modul_master/fetch_data_video', 'Modul\ModulMaster::fetch_data_video');
    $routes->post('modul/modul_master/fetch_data_quiz', 'Modul\ModulMaster::fetch_data_quiz');
    $routes->post('modul/modul_master/cek_trigger_menu', 'Modul\ModulMaster::cek_trigger_menu');
    $routes->post('modul/modul_master/check_learn_progress', 'Modul\ModulMaster::check_learn_progress');
    $routes->post('modul/modul_master/cek_data_modul', 'Modul\ModulMaster::cek_data_modul');
    $routes->post('modul/modul_master/submit_quiz', 'Modul\ModulMaster::submit_quiz');
});

// AJAX
$routes->get('fetch/fetch_data/fetch_data_intro', 'Fetch\FetchData::fetch_data_intro');
$routes->get('fetch/fetch_data/fetch_data_banner_popup', 'Fetch\FetchData::fetch_data_banner_popup');
$routes->get('fetch/fetch_data/fetch_data_schedule', 'Fetch\FetchData::fetch_data_schedule');
$routes->get('fetch/fetch_data/fetch_data_artikel_alumni', 'Fetch\FetchData::fetch_data_artikel_alumni');
$routes->get('fetch/fetch_data/fetch_data_voting', 'Fetch\FetchData::fetch_data_voting');
$routes->get('fetch/fetch_data/fetch_data_partner', 'Fetch\FetchData::fetch_data_partner');
$routes->get('fetch/fetch_data/fetch_data_faq', 'Fetch\FetchData::fetch_data_faq');

$routes->get('page/terms_of_service/fetch_data', 'Page\TermsOfService::fetch_data');
$routes->get('page/privacy_policy/fetch_data', 'Page\PrivacyPolicy::fetch_data');
$routes->get('page/webinar/fetch_data_webinar', 'Page\Webinar::fetch_data_webinar');
$routes->get('startups_data/fetch_data_detail', 'Page\StartupsData::fetch_data_detail');

$routes->get('dashboard/dashboard/get_page', 'Dashboard\Dashboard::get_page');
$routes->get('dashboard/dashboard/fetch_data_dashboard', 'Dashboard\Dashboard::fetch_data_dashboard');
$routes->get('dashboard/dashboard/fetch_data_modul', 'Dashboard\Dashboard::fetch_data_modul');
$routes->get('dashboard/dashboard/fetch_data_profile', 'Dashboard\Dashboard::fetch_data_profile');
$routes->get('dashboard/dashboard/cek_file_pitchdeck', 'Dashboard\Dashboard::cek_file_pitchdeck');
$routes->get('dashboard/dashboard/cek_file_pengajuan_kegiatan', 'Dashboard\Dashboard::cek_file_pengajuan_kegiatan');
$routes->get('dashboard/dashboard/get_modal_event', 'Dashboard\Dashboard::get_modal_event');
$routes->get('dashboard/dashboard/generate_channel', 'Dashboard\Dashboard::generate_channel');
$routes->get('dashboard/dashboard/close_modal_event', 'Dashboard\Dashboard::close_modal_event');

$routes->get('home/get_address', 'Home::get_address');

$routes->post('auth/login/post_login', 'Auth\Login::post_login');
$routes->post('auth/login/post_login_verify', 'Auth\Login::post_login_verify');
$routes->post('auth/login/cek_password_lama', 'Auth\Login::cek_password_lama');
$routes->post('auth/register/post_register_profile', 'Auth\Register::post_register_profile');
$routes->post('auth/register/post_register_personal', 'Auth\Register::post_register_personal');
$routes->post('auth/register/post_register_startup', 'Auth\Register::post_register_startup');

$routes->post('dashboard/dashboard/post_update_profile', 'Dashboard\Dashboard::post_update_profile');
$routes->post('dashboard/dashboard/post_update_pilot_project', 'Dashboard\Dashboard::post_update_pilot_project');
$routes->post('dashboard/dashboard/post_change_password', 'Dashboard\Dashboard::post_change_password');

// Analytics
$routes->post('analytic/post_visitors', 'Analytic::post_visitors');
$routes->post('analytic/post_cta_btn', 'Analytic::post_cta_btn');
$routes->post('analytic/post_blog_viewer', 'Analytic::post_blog_viewer');
$routes->post('analytic/post_startups_viewer', 'Analytic::post_startups_viewer');

// Clear Cache
$routes->get('clear-cache', 'CacheController::clear');