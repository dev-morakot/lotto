<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

/**
 * Description of Util
 *
 * @author morakot
 */
class Util {
    
    public static function thaiToMySQLDate($date) {
        if (!empty($date)) {
            $arr = explode("/", $date);
            
            if (count($arr) > 0) {
            		if (!empty($arr[2])) {
		            $y = $arr[2];
		            $m = $arr[1];
		            $d = $arr[0];
		
		            return "{$y}-{$m}-{$d}";
	            }
            }
        }
    }
    
    public static function mysqlToThaiDate($date) {
        if ($date == '0000-00-00') {
            return '-';
        }
        if ($date == '0000-00-00 00:00:00') {
            return '-';
        }
        
        if (!empty($date)) {
            $arr = explode(" ", $date);
            $arr2 = explode("-", $arr[0]);
            
            $y = $arr2[0];
            $m = $arr2[1];
            $d = $arr2[2];
            
            return "$d/$m/$y";
        }
    }
    
    /**
     * Input yyyy-mm-dd
     * @param type $strDate
     * @return type
     */
    public static function DateThai($strDate) {
        if($strDate==null){
            return "-";
        }
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strMonthCut = Util::monthRange();
        $strMonthThai = $strMonthCut[$strMonth];

        return "$strDay $strMonthThai $strYear";
    }

    public static function DateShort($strDate) {
        if($strDate==null){
            return "-";
        }
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strMonthCut = Util::monthShort();
        $strMonthThai = $strMonthCut[$strMonth];

        return "$strDay $strMonthThai $strYear";
    }

    public static function monthRange() {
        $monthRange = array(
            '1' => 'มกราคม',
            '2' => 'กุมภาพันธ์',
            '3' => 'มีนาคม',
            '4' => 'เมษายน',
            '5' => 'พฤษภาคม',
            '6' => 'มิถุนายน',
            '7' => 'กรกฏาคม',
            '8' => 'สิงหาคม',
            '9' => 'กันยายน',
            '10' => 'ตุลาคม',
            '11' => 'พฤศจิกายน',
            '12' => 'ธันวาคม',
        );

        return $monthRange;
    }

    public static function monthShort() {
        $monthShort = array(
            '1' => 'ม.ค.',
            '2' => 'ก.พ.',
            '3' => 'มี.ค.',
            '4' => 'เม.ย.',
            '5' => 'พ.ค.',
            '6' => 'มิ.ย.',
            '7' => 'ก.ค.',
            '8' => 'ส.ค.',
            '9' => 'ก.ย.',
            '10' => 'ต.ค.',
            '11' => 'พ.ย.',
            '12' => 'ธ.ค.',
        );
        return $monthShort;
    }
}
