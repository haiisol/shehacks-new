<?php
ini_set('date.timezone', 'Asia/Jakarta');

use Config\Services;
use Config\Database;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function app_url()
{
    if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        $ssl = 'https';
    } else {
        $ssl = 'http';
    }

    $app_url = ($ssl)
        . "://" . $_SERVER['HTTP_HOST']
        . (dirname($_SERVER["SCRIPT_NAME"]) == DIRECTORY_SEPARATOR ? "" : "/")
        . trim(str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"])), "/");

    return $app_url;
}


function encrypt_url($string)
{
    $output = false;
    $security = parse_ini_file("security.ini");
    $secret_key = $security["encryption_key"];
    $secret_iv = $security["iv"];
    $encrypt_method = $security["encryption_mechanism"];

    $key = hash("sha256", $secret_key);
    $iv = substr(hash("sha256", $secret_iv), 0, 16); // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
    $result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
    $output = base64_encode($result);

    return $output;
}

function decrypt_url($string)
{
    $output = false;
    $security = parse_ini_file("security.ini");
    $secret_key = $security["encryption_key"];
    $secret_iv = $security["iv"];
    $encrypt_method = $security["encryption_mechanism"];

    $key = hash("sha256", $secret_key);
    $iv = substr(hash("sha256", $secret_iv), 0, 16); // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
    $output = openssl_decrypt(base64_decode($string ?? ''), $encrypt_method, $key, 0, $iv);

    return $output;
}

function json_response($data = [], $status_code = 200)
{
    $security = Services::security();
    $data['csrf_name'] = $security->getTokenName();
    $data['csrf_hash'] = $security->getHash();

    return Services::response()
        ->setStatusCode($status_code)
        ->setContentType('application/json')
        ->setJSON($data);
}

function generateRandomString()
{
    $length = 4;
    $characters = '0123456789';
    $charactersLength = strlen($characters);

    $result = '';

    for ($i = 0; $i < $length; $i++) {
        $result .= $characters[rand(0, $charactersLength - 1)];
    }

    return $result;
}

function period()
{
    return range(2020, 2025);
}

function default_image()
{
    $file = base_url() . 'assets/backoffice/images/no-image.png';

    return $file;
}

function url_image($nama_file, $folder_image)
{
    $url_api = config('Common')->url_api_file ?? '';

    if ($nama_file) {
        return $url_api . $folder_image . '/' . $nama_file;
    }

    return default_image();
}

function url_image_thumbnail($nama_file, $folder_image)
{
    $url_api = config('Common')->url_api_file ?? '';

    if ($nama_file) {
        if (file_exists(FCPATH . 'file_media/' . $folder_image . '/Thumbnail-S-' . $nama_file)) {
            return $url_api . $folder_image . '/Thumbnail-S-' . $nama_file;
        }
    }

    return '-';
}

function before_load()
{
    $file = base_url() . 'assets/front/img/pixel.gif';

    return $file;
}

function url_startup_detail($nama_startup, $id)
{
    $result = base_url() . 'startup/' . url_title(strtolower($nama_startup)) . '/' . encrypt_url($id);

    return $result;
}

function url_blog_detail($nama_blog, $id)
{
    $result = base_url() . 'artikel/' . url_title(strtolower($nama_blog)) . '/' . encrypt_url($id);

    return $result;
}

function url_startups_detail($name, $id)
{
    $result = base_url() . 'startups/' . url_title(strtolower($name)) . '/' . encrypt_url($id);

    return $result;
}

function url_blog_tags($nama_tags, $id_tags)
{
    $result = base_url() . 'artikel-tags/' . url_title(strtolower($nama_tags)) . '/' . encrypt_url($id_tags);

    return $result;
}

function url_blog_kategori($nama_kategori, $id_kategori)
{
    $result = base_url() . 'artikel-kategori/' . url_title(strtolower($nama_kategori)) . '/' . encrypt_url($id_kategori);

    return $result;
}

function url_modul_detail($modul, $id_modul)
{
    $result = base_url() . 'category/' . url_title(strtolower($modul)) . '/' . encrypt_url($id_modul);

    return $result;
}

function url_modul_edukasi($modul, $id_modul)
{
    $result = base_url() . 'learn/' . strip_tags(url_title(strtolower($modul))) . '/' . encrypt_url($id_modul);

    return $result;
}

function key_auth()
{
    return decrypt_url(session()->get('key_auth'));
}

// --------------------------- time ago ---------------------------
function time_ago($value, $full = false)
{
    $today = time();
    $createdday = strtotime($value);
    $datediff = abs($today - $createdday);
    $difftext = '';
    $years = floor($datediff / (365 * 60 * 60 * 24));
    $months = floor(($datediff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($datediff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
    $hours = floor($datediff / 3600);
    $minutes = floor($datediff / 60);
    $seconds = floor($datediff);
    //year checker
    if ($difftext == '') {
        if ($years > 1)
            $difftext = $years . ' tahun yang lalu';
        elseif ($years == 1)
            $difftext = $years . ' tahun yang lalu';
    }
    //month checker
    if ($difftext == '') {
        if ($months > 1)
            $difftext = $months . ' bulan yang lalu';
        elseif ($months == 1)
            $difftext = $months . ' bulan yang lalu';
    }
    //month checker
    if ($difftext == '') {
        if ($days > 1)
            $difftext = $days . ' hari yang lalu';
        elseif ($days == 1)
            $difftext = $days . ' hari yang lalu';
    }
    //hour checker
    if ($difftext == '') {
        if ($hours > 1)
            $difftext = $hours . ' jam yang lalu';
        elseif ($hours == 1)
            $difftext = $hours . ' jam yang lalu';
    }
    //minutes checker
    if ($difftext == '') {
        if ($minutes > 1)
            $difftext = $minutes . ' menit yang lalu';
        elseif ($minutes == 1)
            $difftext = $minutes . ' menit yang lalu';
    }
    //seconds checker
    if ($difftext == '') {
        if ($seconds > 1)
            $difftext = $seconds . ' detik yang lalu';
        elseif ($seconds == 1)
            $difftext = $seconds . ' detik yang lalu';
    }
    return $difftext;
}

function time_ago_from_3($value)
{
    $date_now = date_create('now', timezone_open('Asia/Jakarta'))->format('Y-m-d');
    $date_ago = date('Y-m-d', strtotime('-3 days', strtotime($date_now)));

    if (date('Y-m-d', strtotime($value)) > $date_ago) {
        $result = time_ago($value);
    } else {
        $result = date('d M Y H:i', strtotime($value));
    }

    return $result;
}
// --------------------------- end time ago ---------------------------


// --------------------------- date handle ---------------------------
function conv_month($value)
{
    switch ($value) {
        case 1:
            return 'Januari';
            break;
        case 2:
            return 'Februari';
            break;
        case 3:
            return 'Maret';
            break;
        case 4:
            return 'April';
            break;
        case 5:
            return 'Mei';
            break;
        case 6:
            return 'Juni';
            break;
        case 7:
            return 'Juli';
            break;
        case 8:
            return 'Agustus';
            break;
        case 9:
            return 'September';
            break;
        case 10:
            return 'Oktober';
            break;
        case 11:
            return 'November';
            break;
        case 12:
            return 'Desember';
            break;
    }
}

function conv_month_medium($value)
{
    switch ($value) {
        case 1:
            return 'Jan';
            break;
        case 2:
            return 'Feb';
            break;
        case 3:
            return 'Mar';
            break;
        case 4:
            return 'Apr';
            break;
        case 5:
            return 'Mei';
            break;
        case 6:
            return 'Jun';
            break;
        case 7:
            return 'Jul';
            break;
        case 8:
            return 'Ags';
            break;
        case 9:
            return 'Sep';
            break;
        case 10:
            return 'Okt';
            break;
        case 11:
            return 'Nov';
            break;
        case 12:
            return 'Des';
            break;
    }
}

function conv_days($value)
{
    $result = '';

    if ($value == 'Sunday') {
        $result = 'Minggu';
    } else if ($value == 'Monday') {
        $result = 'Senin';
    } else if ($value == 'Tuesday') {
        $result = 'Selasa';
    } else if ($value == 'Wednesday') {
        $result = 'Rabu';
    } else if ($value == 'Thursday') {
        $result = 'Kamis';
    } else if ($value == 'Friday') {
        $result = 'Jumat';
    } else if ($value == 'Saturday') {
        $result = 'Sabtu';
    }

    return $result;
}

function date_ind($value, $param = false, $separator = false)
{
    // date_ind('2017-08-5', 'full', '-');

    $formatted = gmdate($value, time() + 60 * 60 * 8);
    $split = explode('-', $formatted);
    $year = $split[0];
    $date = $split[2];

    if ($separator) {
        $separator = $separator;
    } else {
        $separator = ' ';
    }

    if ($param) {
        if ($param == 'long') {
            $month = conv_month($split[1]);
            $result = $date . $separator . $month . $separator . $year;
        } else if ($param == 'short') {
            $month = conv_month_medium($split[1]);
            $result = $date . $separator . $month . $separator . $year;
        } else if ($param == 'full') {
            $month = $split[1];
            $nama_hari = conv_days(date("l", mktime(0, 0, 0, $month, $date, $year)));
            $result = $nama_hari . ', ' . $date . $separator . conv_month($month) . $separator . $year;
        }
    } else {
        $month = conv_month($split[1]);
        $result = $date . $separator . $month . $separator . $year;
    }

    return $result;
}

function datetime_ind($value, $param = false, $separator = false)
{
    // date_ind('2017-08-5', 'full', '-');
    $val_date = date('Y-m-d', strtotime($value));
    $val_time = date('H:i', strtotime($value));

    $formatted = gmdate($val_date, time() + 60 * 60 * 8);
    $split = explode('-', $formatted);
    $year = $split[0];
    $date = $split[2];

    if ($separator) {
        $separator = $separator;
    } else {
        $separator = ' ';
    }

    if ($param) {
        if ($param == 'long') {
            $month = conv_month($split[1]);
            $result = $date . $separator . $month . $separator . $year . ' ' . $val_time;
        } else if ($param == 'short') {
            $month = conv_month_medium($split[1]);
            $result = $date . $separator . $month . $separator . $year . ' ' . $val_time;
        } else if ($param == 'short_day') {
            $month = conv_month_medium($split[1]);
            $nama_hari = conv_days(date("l", mktime(0, 0, 0, $split[1], $date, $year)));
            $result = $nama_hari . ', ' . $date . $separator . $month . $separator . $year . ' ' . $val_time;
        } else if ($param == 'full') {
            $month = $split[1];
            $nama_hari = conv_days(date("l", mktime(0, 0, 0, $month, $date, $year)));
            $result = $nama_hari . ', ' . $date . $separator . conv_month($month) . $separator . $year . ' ' . $val_time;
        }
    } else {
        $month = conv_month($split[1]);
        $result = $date . $separator . $month . $separator . $year . ' ' . $val_time;
    }

    return $result;
}
// --------------------------- end date handle ---------------------------

function generateRandomColorHex()
{
    $red = mt_rand(0, 255);
    $green = mt_rand(0, 255);
    $blue = mt_rand(0, 255);

    $colorHex = sprintf("#%02x%02x%02x", $red, $green, $blue);

    return $colorHex;
}

function sanitize_input($str)
{
    if ($str === null) {
        $str = '';
    }

    $str = strip_tags($str); // Menghapus tag HTML/JS
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8'); // Konversi simbol HTML agar tidak dieksekusi
}

function get_umur($tanggal)
{
    $birthDate = new \DateTime(date('Y-m-d', strtotime($tanggal)));
    $today = new \DateTime("today");
    return ($birthDate > $today) ? 0 : $today->diff($birthDate)->y;
}

function sanitize_input_textEditor($str)
{
    // Tag yang diizinkan
    $allowed_tags = '<p><br><b><strong><i><em><u><ul><ol><li><a><img><span><div><h1><h2><h3><blockquote>';

    // Hapus semua tag selain yang diizinkan
    $sanitized = strip_tags($str, $allowed_tags);

    // Hapus atribut berbahaya seperti on* (onclick, onload, dll)
    $sanitized = preg_replace('/\s*on\w+="[^"]*"/i', '', $sanitized);   // onEvent=""
    $sanitized = preg_replace("/\s*on\w+='[^']*'/i", '', $sanitized);   // onEvent=''

    // Hapus javascript: dalam atribut href atau src
    $sanitized = preg_replace('/(href|src)\s*=\s*["\']?\s*javascript:[^"\']*["\']?/i', '', $sanitized);

    // Hapus style untuk menghindari CSS-based trick (opsional)
    $sanitized = preg_replace('/\s*style\s*=\s*"[^"]*"/i', '', $sanitized);
    $sanitized = preg_replace("/\s*style\s*=\s*'[^']*'/i", '', $sanitized);

    return $sanitized;
}

function _clear_session()
{
    session()->remove([
        'nama',
        'telp',
        'email',
        'password',
        'alamat',
        'jenis_kelamin',
        'tanggal_lahir',
        'pendidikan',
        'kategori_user',
        'dapat_informasi',
    ]);
}

function fa_handle($id_user, $uri_string = "")
{
    $db = Database::connect();
    $generate_code = rand(100000, 999999);

    $data2fa = [
        'id_user' => $id_user,
        'code' => $generate_code,
        'access_policy' => 'FE',
        'code_encrypt' => encrypt_url($generate_code),
        'date_create' => date('Y-m-d H:i:s'),
        'date_expired' => date('Y-m-d H:i:s', strtotime('+10 minutes'))
    ];

    $builder = $db->table('tb_user_2fa');

    if (!$builder->insert($data2fa)) {
        return false;
    }

    session()->set('2fa_id_user', encrypt_url($id_user));

    if ($uri_string) {
        session()->set('uri_string', $uri_string);
    }

    return [
        'id_user' => $id_user,
        'code' => $generate_code
    ];
}

function send_email($data)
{
    $db = Database::connect();
    $getKonf = $db->table('tb_admin_konf_email')
        ->where('id', 1)
        ->get()
        ->getRowArray();

    $mail = new PHPMailer(true);

    $email = $data['email'];
    $subject = $data['subject'];
    $message = $data['message'];
    $image = $data['image'] ?? '';
    $cid = $data['cid'] ?? 'logo_email';

    try {
        $mail->isSMTP();
        $mail->Host = $getKonf['host'];
        $mail->SMTPAuth = (bool) $getKonf['smtpauth'];
        $mail->Username = $getKonf['email'];
        $mail->Password = $getKonf['password'];
        $mail->SMTPSecure = $getKonf['smtpsecure'];
        $mail->Port = $getKonf['port'];

        $mail->setFrom($getKonf['email'], $getKonf['setfrom']);
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = $subject;

        if ($image) {
            $mail->AddEmbeddedImage($image, $cid);
        }

        $mail->MsgHTML(stripslashes($message));
        $mail->send();

    } catch (Exception $e) {
        log_message('error', 'Email failed: ' . $mail->ErrorInfo);
    }
}