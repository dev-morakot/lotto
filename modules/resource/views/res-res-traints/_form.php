<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResResTraints */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="res-res-traints-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'number_limit')->textInput() ?>

    <?= $form->field($model, 'active')->checkbox() ?>
   
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>