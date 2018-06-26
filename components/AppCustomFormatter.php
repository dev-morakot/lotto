<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;
use yii\helpers\Html;

/**
 * Description of AppCustomFormatter
 *
 * @author wisaruthk
 */
class AppCustomFormatter extends \yii\i18n\Formatter {
    public function asStyleDecimal($value, $decimals = null, $options = array(), $textOptions = array()) {
        $text = parent::asDecimal($value, $decimals, $options, $textOptions);
        $color = "";
        if($value == 0){
            $color = "#808080"; // grey
        }
        if($value <0){
            $color = "#DC143C"; //crimson red
        }
        return Html::tag('span',$text,['style'=>'color:'.$color]);
    }
}
