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
 * Description of StringHelper
 *
 * @author wisaruthk
 */
class SimpleStringHelper {
    
     public static function toStringWithSeparater($textArray,$separater=", "){
        return implode ($separater, $textArray);
    }
}
