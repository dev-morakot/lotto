<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\AccountTaxCode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-tax-code-form">

<?php $form = ActiveForm::begin(); ?>
<p>** กำหนดราคาหวย ที่รับซื้อ</p>

<?= $form->field($model, 'three_top')->textInput() ?>

<?= $form->field($model, 'three_below')->textInput() ?>
<?= $form->field($model, 'three_otd')->textInput() ?>
<?= $form->field($model, 'two_top')->textInput() ?>
<?= $form->field($model, 'two_below')->textInput() ?>


<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

</div>