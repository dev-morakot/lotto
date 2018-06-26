<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use app\modules\resource\models\ResDocMessage;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DocStateWidget
 *
 * @author wisaruthk
 */
class DocMessageWidget extends Widget {
    //put your code here
    public $model;
    private $ref_model;
    private $html ="";

    public function init(){
        parent::init();
        $this->ref_model = $this->model->tableName();
        
    }
    
    public function run(){
        $msgDataProvider = new ActiveDataProvider([
            'query' => ResDocMessage::find()->where(['ref_id'=>$this->model->id,'ref_model'=>$this->ref_model])
        ]);
        $this->html = GridView::widget([
            'dataProvider' => $msgDataProvider,
            'columns' => [
                //'name:text:เอกสาร',
                [
                    'label'=>'ข้อความ',
                    'attribute'=>'message',
                    'format'=>'html'
                ],
                [
                    'label'=>'วันที่',
                    'attribute'=>'create_date'
                ],
                [
                    'label'=>'ผู้ใช้',
                    'attribute'=>'user_id',
                    'format'=>'html',
                    'value'=>function($model){
                        $user = $model->user;
                        if($user){
                            return '<span>'.$user->firstname.' '.$user->lastname
                                    .'</span><small> ('.$user->email.')</small>';
                        }
                        return '-';
                    }
                ]
            ],
        ]);
//        $items = ResDocMessage::find()->where(['ref_id'=>$this->model->id,'ref_model'=>$this->ref_model])->all();
//        foreach ($items as $item) {
//            $line = "<div class='bs-callout bs-callout-info>";
//            $line = $line."<p>".$item->message."</p>";
//            $line = $line."<p>".$item->create_date."</p>";
//            $line = $line."<p>".$item->user->firstname."</p>";
//            $line = $line."</div>";
//            $this->html = $this->html.$line;
//        }
        return $this->html;
    }
}
