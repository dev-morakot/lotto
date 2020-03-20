<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @package yii2-widgets
 * @subpackage yii2-widget-select2
 * @version 2.0.9
 */

namespace app\modules\jan\assets;

use yii\web\AssetBundle;


class JanAsset extends AssetBundle
{   
    public $sourcePath ="@app/modules/jan/assets/";
    
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
            'js/jan-app.js',
            'js/FileSaver.js'
        ];
        
        parent::init();
    }
}
