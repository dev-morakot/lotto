<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @package yii2-widgets
 * @subpackage yii2-widget-select2
 * @version 2.0.9
 */

namespace app\modules\resource\assets;

use yii\web\AssetBundle;


class ResDocReportAllAsset extends AssetBundle
{   
    public $sourcePath ="@app/modules/resource/assets/";
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'app\assets\AngularAsset'
    ];
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->css = [
            
        ];
        $this->js = [            
            'js/res_doc_report_all-app.js',
            'js/FileSaver.js'
        ];
        
        parent::init();
    }
}
