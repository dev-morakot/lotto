<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @package yii2-widgets
 * @subpackage yii2-widget-select2
 * @version 2.0.9
 */

namespace app\assets;

use yii\web\AssetBundle;


class jQueryMobileAsset extends AssetBundle
{   
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.css',
    ];
    public $js = [
        'js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
