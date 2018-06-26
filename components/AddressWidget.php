<?php

namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use yii;
use app\modules\resource\models\ResAddress;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddressWidget
 *
 * @author wisaruthk
 */
class AddressWidget extends Widget {

    //put your code here
    
    public $address_id = null;
    public $locale = 'th'; //th, en
    public $fields = [];
    public $bootstrap_enable = false;
    
    private $model;

    public function init() {
        parent::init();
        
        $this->model = ResAddress::findOne(['id'=>$this->address_id]);

    }

    public function run() {
        return $this->render('address_widget',[
            'model'=>$this->model,
            'fields'=>$this->fields,
            'locale'=>$this->locale
        ]);
    }

}
