<?php

use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResAddress */
/* @var $form yii\widgets\ActiveForm */
$props = [
    'th'=>[
        'district'=>"อ.",
        'province'=>'จ.',
        'tel'=>'โทร',
        'fax'=>'แฟกซ์'
    ],
    'en'=>[
        'district'=>"",
        'province'=>'',
        'tel'=>'Tel',
        'fax'=>'Fax'
    ]
];

$p = $props[$locale]; // th or en

?>
<div class="contact">
    <div><?=$model->first_name?> <?=$model->last_name?></div>
    <div><?=$model->company_name?></div>
    <div><?=$model['address1']?></div>
    <div><?=$model['address2']?></div>
    <div>
        <?=($model->district)?$p['district'].$model->districtDisplay.', ':""?>
        <?=($model->province)?$p['province'].$model->provinceDisplay.", ":""?>
        <?=($model->postal_code)?$model->postal_code:""?>
    </div>
    <div><?=$p['tel']?>: <?=$model->phone?>,<?=$model->mobile?></div>
    <div><?=$p['fax']?>: <?=$model->fax?></div>
</div>

