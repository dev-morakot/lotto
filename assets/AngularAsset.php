<?php

/**
 * @copyright Copyright &copy; Wisaruth K, 2014 - 2016
 * @version 2.0.9
 */

namespace app\assets;

use yii\web\AssetBundle;


class AngularAsset extends AssetBundle
{   
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js/ui-select/select.min.css',
    ];
    public $js = [
        'js/angular-1.5.8/angular.min.js',
        'js/angular-extras/angular-locale_th.js',
        'js/angular-extras/angular-locale_th-th.js',
        'js/ui-select/select.min.js',
        'js/angular-extras/angular-sanitize.js',
        'js/angular-extras/angular-animate.min.js',
        'js/angular-extras/angular-route.js',
        'js/angular-extras/angular-touch.js',
        'js/angular-bootstrap-show-errors/showErrors.min.js',
        'js/angular-3party/checklist-model.js',
        'js/angular-3party/ui-bootstrap-tpls-2.2.0.min.js',
        'js/angular-extras/angular-resource.min.js',
        'js/bic-angular-common.js',
        'js/bic-angular-module.js',
        'js/bic-angular-common-stock.js',
        'js/tableExport.js',
        'js/jquery.base64.js',
        'js/sprintf.js',
        'js/jspdf.js',
        'js/base64.js',
        'js/html2canvas.min.js',
        'js/pdfmake.min.js',
        'js/vfs_fonts.js'
       
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
