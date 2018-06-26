<?php

/**
 * @copyright Copyright &copy; Wisaruth K, 2014 - 2016
 * @version 2.0.9
 */

namespace app\assets;

use yii\web\AssetBundle;


class AngularNgTableAsset extends AssetBundle
{   
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js/ng-table/ng-table.css',
    ];
    public $js = [
        'js/ng-table/ng-table.js',
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'app\assets\AngularAsset'
    ];
}
