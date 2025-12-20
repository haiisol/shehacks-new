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
$routes->get('contact', 'page\Contact::index');
$routes->get('program', 'page\Program::index');
$routes->get('gallery', 'page\Gallery::index');
$routes->get('faq', 'page\Faq::index');
$routes->get('privacy-policy', 'page\Privacy_policy::index');
$routes->get('webinar', 'page\Webinar::index');
$routes->get('terms-of-service', 'page\Terms_of_service::index');
$routes->get('impact-report', 'page\Impact_report::index');

$routes->get('coming-soon', 'Home::coming_soon');
$routes->get('preview', 'Home::preview');
$routes->get('preview-voting', 'Home::preview_voting');


// ================= Artikel =================
$routes->get('artikel', 'artikel\Artikel::index');
$routes->get('artikel/(:any)/(:any)', 'artikel\Artikel::detail/$1/$2');

$routes->get('artikel_data/(:any)', 'artikel\Artikel_data::index/$1');

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
$routes->get('category/(:any)/(:any)', 'modul\Modul::detail_modul/$1/$2');
$routes->get('learn/(:any)/(:any)', 'modul\Modul_master::pelajari/$1/$2');
$routes->get('sertifikat/(:any)/(:any)', 'modul\Modul_master::show_sertifikat/$1/$2');

// AJAX
$routes->get('fetch/fetch_data/fetch_data_intro', 'Fetch\FetchData::fetch_data_intro');
$routes->get('fetch/fetch_data/fetch_data_banner_popup', 'Fetch\FetchData::fetch_data_banner_popup');
$routes->get('fetch/fetch_data/fetch_data_schedule', 'Fetch\FetchData::fetch_data_schedule');
$routes->get('fetch/fetch_data/fetch_data_artikel_alumni', 'Fetch\FetchData::fetch_data_artikel_alumni');
$routes->get('fetch/fetch_data/fetch_data_voting', 'Fetch\FetchData::fetch_data_voting');
$routes->get('fetch/fetch_data/fetch_data_partner', 'Fetch\FetchData::fetch_data_partner');


// Analytics
$routes->post('analytic/post_visitors', 'Analytic::post_visitors');
