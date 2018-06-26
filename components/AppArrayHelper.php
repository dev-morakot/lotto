<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

/**
 * Description of AppArrayHelper
 *
 * @author wisaruthk
 */
class AppArrayHelper extends \yii\helpers\ArrayHelper {
    
    /**
     * $d = [
         ["id" => "1","order_id" => "2"],
         ["id" => "1","order_id" => "2"],
         ["id" => "1","order_id" => "2"],
       ]
     * 
     * to 
     * 
     * $out = [
         ["id" => "1","order_id" => "2"],
       ]
     * 
     * 
     * @param type $arr
     * @return type $arr
     */
    public static function array_object_unique($arr){
        return array_intersect_key($arr, array_unique(array_map('serialize', $arr)));
    }
}
