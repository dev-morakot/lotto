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
 * Description of NumberToWordsThaiWidget
 *
 * @author wisaruthk
 */
class NumberToWordsThaiWidget extends Widget {

    //put your code here
    public $number;

    public function init() {
        parent::init();
    }

    public function run() {
        //return $this->num2wordsThai($this->number);
        
        return $this->toBaht($this->number);
    }
    
    public function toWords($number){
        if(!preg_match( '/^([0-9]+)(\.[0-9]{0,4}){0,1}$/' , $number=str_replace(',', '', $number), $m ))
        return 'This is not currency format';
        $m[2]=count($m)==3? intval(('0'.$m[2])*100 + 0.5) : 0;
        $st = $this->cv( $m[2]);
        return $this->cv( $m[1]) . ($st>''? 'จุด'.$st : ''); 
    }
    
    public function toBaht($number ){
        if(!preg_match( '/^([0-9]+)(\.[0-9]{0,4}){0,1}$/' , $number=str_replace(',', '', $number), $m ))
        return 'This is not currency format';
        $m[2]=count($m)==3? intval(('0'.$m[2])*100 + 0.5) : 0;
        $mt = $this->cv( $m[1]);
        $st = $this->cv( $m[2]);
        return $mt . 'บาท' . $st . ($st>''? 'สตางค์' : ''); 
    }
    
    private function cv( $num ){
        $th_num = array('', array('หนึ่ง', 'เอ็ด'), array('สอง', 'ยี่'),'สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ'); 
        $th_digit = array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน'); 
        $ln=strlen($num);
        $t='';
        for($i=$ln; $i>0;$i--){
                $x=$i-1;
                $n = substr($num, $ln-$i,1);
                $digit=$x % 6; 
                if($n!=0){ 
                        if( $n==1 ){ $t .= $digit==1? '' : $th_num[1][$digit==0? ($t? 1 : 0) : 0]; }
                        elseif( $n==2 ){  $t .= $th_num[2][$digit==1? 1 : 0]; } 
                        else{ $t.= $th_num[$n]; } 
                        $t.= $th_digit[($digit==0 && $x>0 ? 6 : $digit )]; 
                }else{
                        $t .= $th_digit[ $digit==0 && $x>0 ? 6 : 0 ]; 
                }
        }
        return $t; 
    }
}

class hk_baht{
	public $result;
	public function __construct( $num ){
		$this->result=$this->toBaht( $num , true );
	}
	public function toBaht($number ){
		if(!preg_match( '/^([0-9]+)(\.[0-9]{0,4}){0,1}$/' , $number=str_replace(',', '', $number), $m ))
		return 'This is not currency format';
		$m[2]=count($m)==3? intval(('0'.$m[2])*100 + 0.5) : 0;
		$st = $this->cv( $m[2]);
		return $this->cv( $m[1]) . 'บาท' . $st . ($st>''? 'สตางค์' : ''); 
	}
	private function cv( $num ){
		$th_num = array('', array('หนึ่ง', 'เอ็ด'), array('สอง', 'ยี่'),'สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ'); 
		$th_digit = array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน'); 
		$ln=strlen($num);
		$t='';
		for($i=$ln; $i>0;$i--){
			$x=$i-1;
			$n = substr($num, $ln-$i,1);
			$digit=$x % 6; 
			if($n!=0){ 
				if( $n==1 ){ $t .= $digit==1? '' : $th_num[1][$digit==0? ($t? 1 : 0) : 0]; }
				elseif( $n==2 ){  $t .= $th_num[2][$digit==1? 1 : 0]; } 
				else{ $t.= $th_num[$n]; } 
				$t.= $th_digit[($digit==0 && $x>0 ? 6 : $digit )]; 
			}else{
				$t .= $th_digit[ $digit==0 && $x>0 ? 6 : 0 ]; 
			}
		}
		return $t; 
	}
}
