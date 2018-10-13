<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\resource\models\ResRestraints;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResResTraints */
/* @var $form yii\widgets\ActiveForm */
$array = ResRestraints::TraintsType();
$type =  ArrayHelper::map($array,'id','name');

?>

<div class="res-res-traints-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'number_limit')->textInput() ?>
    <?= $form->field($model, 'type')->dropDownList($type,['prompt'=>'']); ?>

    <?= $form->field($model, 'active')->checkbox() ?>
   
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>