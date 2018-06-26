<?php

use yii\helpers\Html;
use app\modules\resource\models\ResCurrency;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use app\modules\account\models\AccountAccount;
use app\modules\account\models\AccountJournal;
use app\modules\resource\models\ResAddress;
use app\modules\admin\assets\SettingsFormAsset;
use app\modules\stock\models\StockPickingType;
/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Log */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
SettingsFormAsset::register($this);
$currencyOptions = ArrayHelper::map(ResCurrency::find()->all(), 'id', 'name');
$accountOptions = ArrayHelper::map(AccountAccount::find()->where(['<>','type','view'])->orderBy('code')->all(),'id','displayName');
$journalOptions = ArrayHelper::map(AccountJournal::find()->all(),'id','displayName');
$addressOptions = ArrayHelper::map(ResAddress::find()->where(['setting_id'=>$model->id])->all(),'id','address1');
$pickingTypeOptions = ArrayHelper::map(StockPickingType::find()->all(),'id','name','warehouse.name');
?>
<div class="log-form well">
    <input type="hidden" id="myId" value="<?=$model->id?>"></input>
    <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>
    <h4>Company:</h4>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'name')->textInput() ?>
            
            <?= $form->field($model, 'name_en')->textInput() ?>

            <?= $form->field($model, 'legal_name')->textInput(['maxlength' => true]) ?>

            <div class="form-group field-settings-imagefile">
                <label class="control-label col-sm-3" for="settings-imagefile">Logo</label>
                <div class="col-sm-6">
                    <?= Html::img(Yii::getAlias('@web/img_com/' . $model->logo), ['width' => '120']) ?>

                </div>
            </div>

            <?= $form->field($model, 'imageFile')->fileInput()->label("Upload Logo") ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'currency_id')->dropDownList($currencyOptions) ?>

            <?= $form->field($model, 'tax_no')->textInput() ?>
        </div>
    </div>
    <hr>
    <h4>Accounting:</h4>
    <?= $form->field($model, 'account_receivable_id')->dropDownList($accountOptions,['prompt'=>"-"]) ?>
    <?= $form->field($model, 'tax_receivable_id')->dropDownList($accountOptions,['prompt'=>"-"]) ?>
    <?= $form->field($model, 'account_payable_id')->dropDownList($accountOptions,['prompt'=>"-"]) ?>
    <?= $form->field($model, 'tax_payable_id')->dropDownList($accountOptions,['prompt'=>"-"]) ?>
    <h4>Accounting Journals:</h4>
    <?= $form->field($model, 'sale_journal_id')->dropDownList($journalOptions,['prompt'=>"-"]) ?>
    <?= $form->field($model, 'purchase_journal_id')->dropDownList($journalOptions,['prompt'=>"-"]) ?>
    <?= $form->field($model, 'pay_in_journal_id')->dropDownList($journalOptions,['prompt'=>"-"]) ?>
    <?= $form->field($model, 'pay_out_journal_id')->dropDownList($journalOptions,['prompt'=>"-"]) ?>
    <?= $form->field($model, 'general_journal_id')->dropDownList($journalOptions,['prompt'=>"-"]) ?>
    <hr>
    <h4>Address:</h4>
    <?= $form->field($model, 'default_address_id')->dropDownList($addressOptions,['prompt'=>"-"]) ?>
    <h4>Sale/Purchase:</h4>
    <?= $form->field($model, 'purchase_picking_type_id')->dropDownList($pickingTypeOptions,['prompt'=>"-"]) ?>
    <?= $form->field($model, 'sale_picking_type_id')->dropDownList($pickingTypeOptions,['prompt'=>"-"]) ?>
    <h4>Inventory:</h4>
    <?= $form->field($model,'stock_cost_mode')->dropDownList(ArrayHelper::map(\app\models\Settings::stockCostModes(), 'id', 'name'),['prompt'=>'-'])?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <hr/>
    
</div>
<div ng-hide="myId==''" ng-app="SettingsApp">
        <h4>Addresses</h4>
        <?=$this->render("@app/modules/resource/views/res-partner/_address_form")?>
    </div>