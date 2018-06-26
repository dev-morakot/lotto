<?php
use yii\helpers\Url;
use app\modules\resource\models\ResProvince;
use app\modules\resource\models\ResDistrict;
use yii\helpers\ArrayHelper;
$province_filter = ArrayHelper::map(ResProvince::find()->all(), 'id', 'name');
$district_filter = ArrayHelper::map(ResDistrict::find()->all(), 'id', 'name');
return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'name',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'district_id',
        'value' => 'district.name',
        'filter' => $district_filter
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'province_id',
        'value'=>'province.name',
        'filter'=>$province_filter
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],

];   