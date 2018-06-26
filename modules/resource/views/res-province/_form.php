<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\resource\models\ResCountry;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResProvince */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="res-province-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'country_id')->dropDownList(ArrayHelper::map(ResCountry::find()->all(), 'id', 'name')) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
