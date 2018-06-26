<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NumberToTextWidget
 *
 * @author morakot
 */
class NumberToTextWidget extends Widget {
    //put your code here
    public $number;
    
    


    public function init(){
        parent::init();
        
    }
    
    public function run(){
        return $this->convertNumberToText($this->number);
    }

    public function convertNumberToText($number) {

			$number_arr = explode(".", $number);
			$number = $number_arr[0];

			$number = str_replace(",", "", $number);
			$arr = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
			$arr_point = array("", "", "สิบ", "ร้อย", "พัน", "หมื่น", "แสน", "ล้าน" , "สิบล้าน" , "ร้อยล้าน");
			$output = "";
			$count_point = strlen($number);

			for($i = 0; $i < strlen($number); $i++) {
				$n = $number[$i];
				$text_number = $arr[$n];

				$position_name = $arr_point[$count_point];

				if($n == 0) {
					$position_name = "";
				}

				// หลัก สิบ
				if($i == strlen($number) - 2) {
					if($n == 2) {
						$position_name = "ยี่สิบ";
						$text_number = "";
					} else if($n == 1) {
						$position_name = "สิบ";
						$text_number = "";
					}
				}

				// หลักสุดท้าย
				if($i == strlen($number) - 1) {
					if($n == 0) {
						$position_name = "";
						$text_number = "";
					} else if($n == 1) {
						$position_name = "เอ็ด";
						$text_number = "";
					}
				}

				$output .= "{$text_number}{$position_name}";
				$count_point--;
			}

			$output .= " บาท";

			// สตางค์
			$satang = "";

			if(count($number_arr) > 1) {
				$satang_number = $number_arr[1];

				$satang1 = substr($satang_number, 0, 1);
				$satang2 = substr($satang_number, 1, 1);

				$satang1_text = $arr[$satang1];
				$satang2_text = $arr[$satang2];

				// หน่วยเรียก
				$satang1_unit = "";

				if ($satang1 == 1) {
					$satang1_text = "สิบ";
				} else if ($satang1 == 2) {
					$satang1_text = "ยี่สิบ";
				} else {
					if ($satang1 > 0) {
						$satang1_unit = "สิบ";
					}
				}


				// หน่วยท้าย
				if ($satang2 == 1) {
					$satang2_text = "เอ็ด";
				}

				if ($satang_number > 0) {
					$satang .= " {$satang1_text}{$satang1_unit}{$satang2_text} สตางค์";
				}				

			}

			$output .= "{$satang}";

      		return $output;
		}
}
