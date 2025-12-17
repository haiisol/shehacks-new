<?php
namespace App\Models;

use CodeIgniter\Model;

class FormatTimeModel extends Model
{

    function get_date($tanggal)
    {
        $date = date('d', strtotime($tanggal));
        $year = date('Y', strtotime($tanggal));
 		
 		$get_mount = $this->get_month($tanggal);

	    $result = $date.' '.$get_mount.' '.$year;
            
        return $result;
    }

    function tanggal_transaction($tanggal, $bahasa)
    {

       $hari    = $this->get_day($tanggal, $bahasa);
       $bulan   = $this->get_month($tanggal, $bahasa);
       $date    = date('d', strtotime($tanggal));
       $year    = date('Y', strtotime($tanggal));

       $result = $hari.', '.$date.' '.$bulan.' '.$year;

       return $result;
    }

    function get_day($tanggal)
    {
        $day = date('D', strtotime($tanggal));

        $dayList = array(
        	'Sun' => 'Minggu',
        	'Mon' => 'Senin',
        	'Tue' => 'Selasa',
        	'Wed' => 'Rabu',
        	'Thu' => 'Kamis',
        	'Fri' => 'Jumat',
        	'Sat' => 'Sabtu'
        );

        return $dayList[$day];
    }

    function get_month($tanggal)
    {
        $mont = date('m', strtotime($tanggal));

        $montList = array(
        	'01' => 'Januari',
        	'02' => 'Februari',
        	'03' => 'Maret',
        	'04' => 'April',
        	'05' => 'Mei',
        	'06' => 'Juni',
        	'07' => 'Juli',
        	'08' => 'Agustus',
        	'09' => 'September',
        	'10' => 'Oktober',
        	'11' => 'November',
        	'12' => 'Desember',
        );

        return $montList[$mont];
    }
}