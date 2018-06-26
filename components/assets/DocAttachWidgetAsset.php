<?php



namespace app\components\assets;

use yii\web\AssetBundle;


class DocAttachWidgetAsset extends AssetBundle
{   
    public $sourcePath ="@app/components/assets/";
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->css = [
           
            
        ];
        $this->js = [
            'js/doc-attach-widget.js',
        ];
        
        parent::init();
    }
}
