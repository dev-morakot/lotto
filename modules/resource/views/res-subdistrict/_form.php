<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\resource\models\ResProvince;
use app\modules\resource\models\ResDistrict;

/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResDistrict */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="res-district-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model,'district_id')->dropDownList(ArrayHelper::map(ResDistrict::find()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'province_id')->dropDownList(ArrayHelper::map(ResProvince::find()->all(), 'id', 'name')) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
