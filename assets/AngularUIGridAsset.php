<?php

/**
 * @copyright Copyright &copy; Wisaruth K, 2014 - 2016
 * @version 2.0.9
 */

namespace app\assets;

use yii\web\AssetBundle;


class AngularUIGridAsset extends AssetBundle
{   
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js/angular-ui-grid/ui-grid.css',
    ];
    public $js = [
        'js/angular-ui-grid/ui-grid.js',
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'app\assets\AngularAsset'
    ];
}
