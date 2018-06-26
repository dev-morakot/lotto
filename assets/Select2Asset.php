<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @package yii2-widgets
 * @subpackage yii2-widget-select2
 * @version 2.0.9
 */

namespace app\assets;

use yii\web\AssetBundle;


class Select2Asset extends AssetBundle
{   
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js/select2-4.0.3/dist/css/select2.css',
    ];
    public $js = [
        'js/select2-4.0.3/dist/js/select2.js',
        'js/select2-4.0.3/dist/js/select2.full.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
