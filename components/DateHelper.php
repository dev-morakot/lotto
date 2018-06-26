<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use Yii;
use \DateTime;

/**
 * Description of DateHelper
 *
 * @author wisaruthk
 */
class DateHelper {

    /**
     * format from d/M/Y to yyyy-MM-dd
     * @param type $input
     * @return type
     */
    public static function toDateStringDB($input) {
        $resultDate = NULL;
        if (empty($input) || $input == NULL) {
            return NULL;
        } else {
            $temp = DateTime::createFromFormat("d/m/Y", $input);
            if ($temp) {
                $temp->setTimezone(new \DateTimeZone("Asia/Bangkok"));
                $resultDate = Yii::$app->formatter->asDate($temp, "yyyy-MM-dd");
            } else {
                $resultDate = NULL;
            }
        }
        return $resultDate;
    }
    
    /**
     * format from d/M/Y H:i:s to yyyy-MM-dd H:mm:ss
     * @param type $input
     * @return type
     */
    public static function toDateTimeStringDB($input,$result_format="yyyy-MM-dd H:mm:ss"){
        $resultDate = NULL;
        if (empty($input) || $input == NULL) {
            return NULL;
        } else {
            $temp = DateTime::createFromFormat("d/m/Y H:i:s", $input);
            if ($temp) {
                $temp->setTimezone(new \DateTimeZone("Asia/Bangkok"));
                $resultDate = Yii::$app->formatter->asDate($temp, $result_format);
            } else {
                $resultDate = NULL;
            }
        }
        return $resultDate;
    }

    /**
     * date with time
     * @return string
     */
    public static function nowStringDB() {
        $dateTime = new DateTime("now");
        $dateTime->setTimezone(new \DateTimeZone("Asia/Bangkok"));
        $strDateTime = $dateTime->format("Y-m-d H:i:s");
        $dtz = Yii::$app->formatter->defaultTimeZone;
        Yii::info("now datetime = " . $strDateTime . " defaultTimeZone=" . $dtz);
        return $strDateTime;
    }
    
    /**
     * date YYYY-M-DD
     * @return string
     */
    public static function dateStringDB(){
        $dateTime = new DateTime("now");
        $dateTime->setTimezone(new \DateTimeZone("Asia/Bangkok"));
        $strDateTime = $dateTime->format("Y-m-d");
        $dtz = Yii::$app->formatter->defaultTimeZone;
        Yii::info("now date = " . $strDateTime . " defaultTimeZone=" . $dtz);
        return $strDateTime;
    }
    
    /**
     * date YYYY-M-DD 23:59:59
     * @return string
     */
    public static function endDateStringDB(){
        return DateHelper::dateStringDB().' 23:59:59';
    }

    /**
     * format from yyyy-MM-dd to d/M/Y
     * @param type $input
     * @return type
     */
    public static function toDateStringDisplay($input) {
        if (empty($input)) {
            return null;
        } else {
            $temp = \DateTime::createFromFormat("Y-m-d", $input);
            if ($temp) {
                $resultDate = Yii::$app->formatter->asDate($temp, 'dd/MM/Y');
                return $resultDate;
            } else {
                return NULL;
            }
        }
    }

    /**
     * format from yyyy-MM-dd to DateTime
     * @param string $input
     * @return DateTime
     */
    public static function toDateObj($input) {
        $temp = \DateTime::createFromFormat("Y-m-d", $input);
        return $temp;
    }
    
    /**
     * $dateObj to Y-m-d
     * @param \DateTime $dateObj
     * @return string
     */
    public static function dateObjToString($dateObj){
        $dateObj->setTimezone(new \DateTimeZone("Asia/Bangkok"));
        $strDateTime = $dateObj->format("Y-m-d");
        return $strDateTime;
    }
    
    /**
     * Y-m-d H:i:s to $dateObj
     * @param \DateTime $dateObj
     * @return string
     */
    public static function dateObjToDateTimeString($dateObj){
        $dateObj->setTimezone(new \DateTimeZone("Asia/Bangkok"));
        $strDateTime = $dateObj->format("Y-m-d H:i:s");
        return $strDateTime;
    }

    public static function DateThai($strDate) {
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strMonthCut = DateHelper::monthRange();

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
    
    private function validateDate($date_str,$format='Y-m-d'){
        $d = \DateTime::createFromFormat($format, $date_str);
        return $d && $d->format($format) == $date_str;
    }

}
