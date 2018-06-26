<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\components;
use Yii;
/**
 * Description of AppHelper
 *
 * @author wisaruthk
 */
class AppHelper {
    //put your code here
    public static function defaultFormatter(){
        $formatter = Yii::$app->formatter;
        $formatter->nullDisplay="-";
        $formatter->booleanFormat = ['<label class="label label-warning">No</label>', '<label class="label label-success">Yes</label>'];
        return $formatter;
    }
}
