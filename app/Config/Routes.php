<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// ================= Auth =================
$routes->get('login', 'auth\Login::index');
$routes->post('login-verify', 'auth\Login::login_verify');
$routes->get('logout', 'auth\Login::logout');

$routes->get('register', 'auth\Register::index');
$routes->get('register/verifikasi', 'auth\Register::verifikasi');

$routes->get('lupa-password', 'auth\Lupa_password::index');
$routes->post('verifikasi-lupa-password', 'auth\Lupa_password::verifikasi');
$routes->post('form-lupa-password', 'auth\Lupa_password::form_lupa_password');


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
$routes->get('artikel', 'artikel\Artikel::index');
$routes->get('artikel/(:any)/(:any)', 'Artikel\Artikel::detail/$1/$2');

$routes->get('artikel_data/(:any)', 'Artikel\ArtikelData::$1');

$routes->get('artikel-tags/(:any)/(:any)', 'artikel\Artikel::tags/$1/$2');
$routes->get('artikel-kategori/(:any)/(:any)', 'artikel\Artikel::tags/$1/$2');


// ================= Startups =================
$routes->get('startups', 'Startups\Startups::index');
$routes->get('startups/(:any)/(:any)', 'Startups\Startups::detail/$1/$2');
$routes->get('startups_data/(:any)', 'Startups\StartupsData::$1');


// ================= Dashboard =================
$routes->get('dashboard', 'dashboard\Dashboard::index');
$routes->get('dashboard/show_sertifikat', 'dashboard\Dashboard::show_sertifikat');


// ================= Admin / Panel =================
$routes->get('panel', 'admin\auth\Login::index');
$routes->get('panel-dashboard', 'admin\dashboard\Dashboard::index');

$routes->post('panel/auth-verify', 'admin\auth\Login::login_verify');

$routes->get('login-admin', 'admin\auth\Login::index');
$routes->get('logout-admin', 'admin\auth\Login::logout');

// Blog Tulis
$routes->get('admin/blogs/(:any)/(:any)', 'admin\blog\Blog::tulis/$1/$2');

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
$routes->get('page/webinar/fetch_data_webinar', 'Page\Webinar::fetch_data_webinar');


// Analytics
$routes->post('analytic/post_visitors', 'Analytic::post_visitors');
$routes->post('analytic/post_cta_btn', 'Analytic::post_cta_btn');
$routes->post('analytic/post_blog_viewer', 'Analytic::post_blog_viewer');
