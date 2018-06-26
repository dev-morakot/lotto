<?php

use yii\grid\GridView;
use yii\helpers\Url;
use app\components\assets\DocAttachWidgetAsset;
use yii\helpers\Html;
use yii\widgets\Pjax;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * $model
 * $allow_edit
 */
DocAttachWidgetAsset::register($this);
?>
<div id="attach-widget">
<?php Pjax::begin(['id' => 'doc_attach_widget_pjax']); ?>

<div class="panel panel-primary" id="doc-attach-widget-panel" style="display:none;">
    <div class="panel-heading">แนบไฟล์</div>
    <div class="panel-body">
        <form id="doc-attach-widget-form" 
              enctype="multipart/form-data"
              method="post"
              name="fileinfo"
              class="form-horizontal"
              >
            <input type="hidden" name="ResAttach[related_model]" value="<?= $model::tableName() ?>"/>
            <input type="hidden" name="ResAttach[related_id]" value="<?= $model->id ?>"/>

            <div class="form-group">
                <label class="col-sm-2">คำอธิบาย</label>
                <div class="col-sm-3">
                    <textarea name="ResAttach[description]" class="form-control"></textarea>
                </div>

            </div>
            <div class="form-group">
                <label class="col-sm-2">แนบไฟล์</label>
                <div class="col-sm-3">
                    <input type="file" class="form-control" name="ResAttach[uploadFile]"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-3">
                    <button id="uploadBtn" 
                            class="btn btn-sm btn-warning">บันทึกแนบไฟล์</button>
                </div>
            </div>
        </form>
    </div>
</div>

<p class="pull-right">
    <button 
        class="btn btn-primary btn-sm" 
        id="doc-attach-widget-toggle">เพิ่มไฟล์แนบ (แบบปกติ)</button>
    <?=
    Html::a('เพิ่มไฟล์แนบ (แบบฟอร์ม)', Url::to(['/resource/res-attach/create-for-model', 'related_model' => $model::tableName(), 'related_id' => $model->id]), ['target' => '_blank', 'class' => 'btn btn-sm btn-primary', 'data-pjax' => 0])
    ?>
</p>
<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        //'name:text:เอกสาร',
            [
            'label' => 'เอกสาร',
            'attribute' => 'name',
            'format' => 'html'
        ],
            [
            'label' => 'รายละเอียด',
            'attribute' => 'description',
        ],
            [
            'label' => 'วันที่',
            'attribute' => 'date',
                'format'=>'datetime'
        ],
            [
            'label' => 'ผู้สร้าง',
            'attribute' => 'createUser.firstname'
        ],
        [
            'class'=>'yii\grid\ActionColumn',
            'header'=>'จัดการ',
            'contentOptions'=>['width'=>'120px'],
            //'buttonOptions'=>['class'=>'btn btn-default btn-sm'],
            'template'=>'<div class="btn-group btn-group-sm text-center" role="group"> {download} {update} {delete} </div>',
            'buttons'=>[
                'download' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-save"></span>', Url::to(['/resource/res-attach/download', 'id' => $model->id]), ['class' => 'btn btn-sm btn-primary', 'target' => '_blank', 'data-pjax' => 0]);
                },
                'update'=>function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to(['/resource/res-attach/view', 'id' => $model->id]), ['class' => 'btn btn-sm btn-default','target'=>'_blank','data-pjax'=>0]);
                },
                'delete'=>function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-remove"></span>', Url::to(['/resource/res-attach/delete-ajax', 'id' => $model->id]), ['class' => 'btn btn-sm btn-warning res-attach-delete']);
                }
            ],
            'visibleButtons'=>[
                'delete'=> function($model,$key,$index) use ($allow_edit) {
                    return $model->state != 'done' && $allow_edit;
                },
                'update'=>function($model,$key,$index) use ($allow_edit) {
                    return $allow_edit;
                }
            ]
        ]            
    ],
]);
?>
<?php Pjax::end() ?>
</div>
