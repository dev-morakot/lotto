<?php 

namespace app\components;

use Yii;
use \DateTime;

class DateDiff {


    /**
     * format from จำนวน วัน เกิดกำหนด / วัน
     * @param type $strDate1, $strDate2
     * @return type
     */

    public static function toDateDiff($strDate1, $strDate2) {
        $resultDate = NULL;
        if((empty($strDate1) || $strDate1 == NULL) && $strDate2 == NULL) {
            return NULL;
        } else {

            if($strDate2 >= $strDate1) { // เกินกำหนด
                $resultDate = (strtotime($strDate2) - strtotime($strDate1)) / ( 60 * 60 * 24);
            }

            if($strDate2 <= $strDate1) { // ยังไม่เกินกำหนด
                $resultDate = NULL;
            } 
             
        }

        return $resultDate;
    }


    /**
     * format from นับ เวลา 00:00 , 19:00
     * @param type $strTime1, $strTime2
     * @return type
     */

    public static function toTimeDiff($strTime1, $strTime2) {
        $resultDate = NULL;
        if((empty($strTime1) || $strTime1 == NULL) && $strTime2 == NULL) {
            return NULL;
        } else {

            $tempDate = (strtotime($strTime2) - strtotime($strTime1)) / ( 60 * 60);
            $resultDate = $tempDate;
        }
        return $resultDate;
    }

}
?>