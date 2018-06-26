<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResAttach */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="res-attach-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6])->label('รายละเอียด') ?>

    <?php
        if($model->state!='done'){
            echo $form->field($model, 'uploadFile')->fileInput()->label("เลือกไฟล์");
        }
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Upload' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
