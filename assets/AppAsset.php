<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;
use Yii;
/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        //'js/DataTables-1.10.12/media/css/jquery.dataTables.css',
    ];
    public $js = [
        //'js/DataTables-1.10.12/media/js/jquery.dataTables.js',      
        'js/jquery.serialize-object/jquery.serialize-object.js',
        'js/masterdata-utils.js',
        'js/numeral-js/numeral.js',
        'js/numeral-js/languages/th.js',
        'js/bootbox/bootbox.min.js',
        'js/moment/moment.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    
    public function init() {
        parent::init();
        if(@Yii::$app->params['nav_theme']){
            $this->css[] = Yii::$app->params['nav_theme'];
        }
    }
}
