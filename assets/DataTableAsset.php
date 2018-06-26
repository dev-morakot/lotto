<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @package yii2-widgets
 * @subpackage yii2-widget-select2
 * @version 2.0.9
 */

namespace app\assets;

use yii\web\AssetBundle;


class DataTableAsset extends AssetBundle
{   
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js/DataTables-1.10.12/media/css/jquery.dataTables.css',
    ];
    public $js = [
        'js/DataTables-1.10.12/media/js/jquery.dataTables.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
