<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

/**
 * Description of newPHPClass
 *
 * @author wisaruthk
 */
class AppAssetManager extends \yii\web\AssetManager {
    public $buildVersion;
    
    public function getAssetUrl($bundle, $asset) {
        $result = parent::getAssetUrl($bundle, $asset);
        if(!empty($this->buildVersion)){
            return $result."?b=".$this->buildVersion;
        } else {
            return $result;
        }
    }
}
