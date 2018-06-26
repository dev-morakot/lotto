<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use app\modules\resource\models\ResDocMessage;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use app\modules\resource\models\ResAttach;
use yii\helpers\Url;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Widget ช่วยเหลือในการแสดงไฟล์แนบ
 *
 * @author wisaruthk
 */
class DocAttachWidget extends Widget {
    //put your code here
    public $model;
    public $allow_edit = true;
    private $ref_model;
    private $html ="";

    public function init(){
        parent::init();
        $this->ref_model = $this->model->tableName();
                
    }
    
    public function run(){
        $msgDataProvider = new ActiveDataProvider([
            'query' => ResAttach::find()->where(['related_id'=>$this->model->id,'related_model'=>$this->ref_model])
        ]);

        return $this->render('doc_attach_widget',[
            'dataProvider'=>$msgDataProvider,
            'model'=>$this->model,
            'allow_edit'=>$this->allow_edit]);
    }
}
