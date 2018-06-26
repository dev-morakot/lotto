<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\modules\resource\models\ResProvince;
use yii\bootstrap\Html;

$province_filter = ArrayHelper::map(ResProvince::find()->all(), 'id', 'name');

$formatter = Yii::$app->formatter;
//$formatter->booleanFormat = ['<span class="glyphicon glyphicon-remove"></span> no', '<span class="glyphicon glyphicon-ok"></span> Yes'];

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'code',
        'label'=>'รหัสคู่ค้า',
        'headerOptions'=>['style'=>'min-width:120px']
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'name',
        'format'=>'raw',
        'value'=>function($model){
            return Html::a($model->name,Url::to(['view','id'=>$model->id]),['data-pjax'=>0]);
        }
    ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'display_name',
//    ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'comment',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'company',
        'value'=>'company.name',
        'label'=>'บริษัท'
    ],
            'tax_no:text:Tax No.',
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'address1',
        'value'=>'defaultAddress.address1'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'address2',
        'value'=>'defaultAddress.address2'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'district_id',
        'value'=>'defaultAddress.district.name',
        'label'=>'เขต/อำเภอ'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'province_id',
        'value'=>'defaultAddress.province.name',
        'label'=>'จังหวัด',
        'filter'=>$province_filter
        
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'active',
        'label'=>'Active',
        'format'=>'boolean',
        'filter'=>[true=>'Yes',false=>'No']
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'customer',
        'label'=>'Cust',
        'format'=>'boolean',
        'filter'=>[true=>'Yes',false=>'No']
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'supplier',
        'label'=>'Supp',
        'format'=>'boolean',
        'filter'=>[true=>'Yes',false=>'No']
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'parent_id',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'supplier',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'customer',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'email',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'is_company',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'employee',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'active',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'vat',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'phone',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'mobile',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'type',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'function',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
         'template'=>'<div class="btn-group btn-group-sm text-center" role="group"> {update} {delete} </div>'
    ],

];   