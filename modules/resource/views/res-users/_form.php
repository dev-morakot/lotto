<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResUsers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="res-users-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'discount')->textInput(['maxlength' => true])->label('ส่วนลด %') ?>
    
   
  
    <?= $form->field($model,'firstname')->textInput(['maxlength'=>true])->label('ชื่อ') ?>
    
    <?= $form->field($model,'lastname')->textInput(['maxlength'=>true])->label('นามสกุล') ?>

    <?= $form->field($model, 'active')->checkbox() ?>

  


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
