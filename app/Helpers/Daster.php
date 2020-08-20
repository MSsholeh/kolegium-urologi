<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Arr;

class Daster
{
    public static function breadcrumb ($data = [])
    {
        $html = '<div class="kt-subheader__breadcrumbs">
		    	<a href="'.url('/').'" class="kt-subheader__breadcrumbs-home shoot">
		    		<i class="flaticon2-shelter"></i>
		    	</a>';

        if(!is_array($data) || count($data) == 0)
            return $html .= '</div>';

        $last = array_key_last($data);

        foreach ($data as $key => $value) {
            $html .= '<span class="kt-subheader__breadcrumbs-separator"></span>';
            if($value && !is_int($key)) {
                $html .= '<a href="'.$value.'" class="kt-subheader__breadcrumbs-link shoot '.($key === $last ? 'kt-subheader__breadcrumbs-link--active' : '').'">
                '.__($key).' </a>';
            } else {
                $html .= '<span class="kt-subheader__breadcrumbs-link '.($key === $last ? 'kt-subheader__breadcrumbs-link--active' : '').'">
                '.__($value).' </span>';
            }
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Convert tanggal keformat bahasa Indonesia
     *
     * @param tanggal date
     * @param format  1 = 31 Desember 2017
     *                2 = Minggu, 31 Desember 2017
     *                3 = 31-12-2017
     * @return string
     */
    public static function tanggal($tanggal, $format = 1, $jam = false)
    {
        $dt = @date_parse($tanggal);
        if(!$dt['year'] || !$dt['month'] || !$dt['day']) return $tanggal;

        if ( $format == 1 ) {

            # 31 Desember 2017
            $hari   = sprintf('%02d', $dt['day']);
            $bulan  = $dt['month'];
            $tahun  = $dt['year'];
            $detail = sprintf('%02d', $dt['hour']).':'.sprintf('%02d', $dt['minute']).':'.sprintf('%02d', $dt['second']);

            return $hari.' '.Daster::bulan($bulan).' '.$tahun. ($jam ? ' '.$detail : '');

        } else if ( $format == 2 ) {

            # Minggu, 31 Desember 2017
            $hari   = sprintf('%02d', $dt['day']);
            $bulan  = $dt['month'];
            $tahun  = $dt['year'];
            $nama_hari  = Carbon::parse($tanggal)->dayOfWeek;
            $detail = sprintf('%02d', $dt['hour']).':'.sprintf('%02d', $dt['minute']).':'.sprintf('%02d', $dt['second']);

            return Daster::hari($nama_hari).', '.$hari.' '.Daster::bulan($bulan).' '.$tahun. ($jam ? ' '.$detail : '');

        } else if ( $format == 3 ) {

            # 31-12-2017
            $hari       = date('d', strtotime($tanggal));
            $bulan      = date('m', strtotime($tanggal));
            $tahun      = date('Y', strtotime($tanggal));
            $detail     = sprintf('%02d', $dt['hour']).':'.sprintf('%02d', $dt['minute']).':'.sprintf('%02d', $dt['second']);

            return $hari.'-'.$bulan.'-'.$tahun. ($jam ? ' '.$detail : '');

        } else if ( $format == 4 ) {

            # 31 Des 2017
            $hari   = sprintf('%02d', $dt['day']);
            $bulan  = $dt['month'];
            $tahun  = $dt['year'];
            $detail = sprintf('%02d', $dt['hour']).':'.sprintf('%02d', $dt['minute']).':'.sprintf('%02d', $dt['second']);

            return $hari.' '.substr(Daster::bulan($bulan),0,3).' '.$tahun. ($jam ? ' '.$detail : '');

        }
    }

    /**
     * Convert angka bulan dalam bahasa Indonesia
     *
     * @return string
     */
    public static function bulan($bulan)
    {
        $aBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

        if(@$aBulan[$bulan-1]){
            return $aBulan[$bulan-1];
        }

        return '';
    }

    /**
     * Convert angka kehari dalam bahasa Indonesia
     * Berawal dari Minggu
     *
     * @return string
     */
    public static function hari($hari)
    {
        $aHari = ['Minggu', 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

        if(@$aHari[$hari]){
            return $aHari[$hari];
        }

        return '';
    }

    /**
     * Convert jam dengan hasil satuan waktu dalam
     * bahasa Indonesia
     *
     * @return string
     * 1 hari 3 jam 5 menit 6 detik
     */
    public static function jam($value)
    {
        if ($value) {
            $exp = explode(':', $value);
            if(!is_array($exp) && count($exp) != 3) {
                return '';
            }

            $hari = floor($exp[0] / 24);
            $hari = ($hari > 0 ? abs($hari). ' hari ' : '');
            $jam = abs($exp[0] % 24). ' jam ';
            $menit = abs($exp[1]). ' menit ';
            $detik = abs($exp[2]). ' detik';

            return $hari.$jam.$menit.$detik;

        } else {
            return '';
        }
    }

    /**
     * Balikin bulan Indoensia kebentuk Angka
     *
     * @param string
     *
     * @return string
     */
    public static function bulanNumber($bulan)
    {
        $aBulan = ['', 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

        $filtered = Arr::where($aBulan, function ($value, $key) use ($bulan) {
            if ($value == $bulan) {
                return true;
            }
        });
        if (array_keys($filtered) != null) {
            return str_pad(array_keys($filtered)[0], 2, '0', STR_PAD_LEFT);
        } else {
            return null;
        }
    }

    /**
     *  Untuk simpan tanggal dari format bulan Indonesia ke format DB
     *  format : 01 Februari 2017 > m d Y : 02-01-2017
     *           Selasa, 01 Februari 2017 > m d Y : 02-01-2017
     *  @return date
     */
    public static function parseTanggal($value)
    {
        $explode = explode(' ', $value);
        if (count($explode) == 3) {
            if (is_numeric($explode[0]) && Daster::bulanNumber($explode[1]) != null) {
                return $explode[2].'-'.Daster::bulanNumber($explode[1]).'-'.$explode[0];
            }
        } elseif (count($explode) == 4) {
            if (is_numeric($explode[1]) && Daster::bulanNumber($explode[2]) != null) {
                return $explode[3].'-'.Daster::bulanNumber($explode[2]).'-'.$explode[1];
            }
        }

        return $value;
    }
}
