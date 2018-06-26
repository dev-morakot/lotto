<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use app\modules\resource\models\ResPartner;
use yii\web\JsExpression;
use yii\web\View;
use yii\helpers\ArrayHelper;
use app\modules\resource\models\ResCountry;
use app\modules\resource\models\ResProvince;
use app\modules\resource\models\ResDistrict;
use app\modules\resource\models\ResTumbon;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use app\modules\resource\assets\PartnerFormAsset;
use app\modules\resource\models\ResAddress;
use app\modules\sale\models\SaleArea;
use app\modules\account\models\AccountAccount;

/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResPartner */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
    PartnerFormAsset::register($this);
    ?>
    <?php $this->registerJs('

            //do nothing
           console.log("enter update");

           //call initial on PartnerFormAsset
           //initial();

    ',View::POS_READY);
    $this->registerCss('
        .form-main {
            color:#31708f;
            background-color:#d9edf7;
            border-color:#bce8f1;
            padding:5px;
        }
        .form-sub {
            background-color:#d9edf7;
            padding:5px;
        }
            ');

    //$addresses = ArrayHelper::map($model->addresses, 'id', 'address1');
    $area = ArrayHelper::map(SaleArea::find()->all(), 'id', 'name');
    $accounts = ArrayHelper::map(AccountAccount::find()->where(['<>','type','view'])->all(), 'id', 'displayName');
    ?>

<div class="res-partner-form">
    <input id="myId" type="hidden" value="<?=$model->id?>"></input>
    
    <?php $form = ActiveForm::begin([
        'id'=>'res-partner-form',
//        'enableAjaxValidation'      => true,
//        'enableClientValidation'    => false,
//        'validateOnChange'          => false,
//        'validateOnSubmit'          => true,
//        'validateOnBlur'            => false,
        'options'=>[
            'class'=>'form-main',
            'novalidate'=>'',
            //'enctype'=>'multipart/form-data'
        ],
        'fieldConfig'=>['options'=>[
            'class'=>'form-group form-group-sm'
        ]]
    ]); ?>

    
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true,'required'=>true,'class'=>'bic-required-field']) ?>
            <?= $form->field($model, 'code')->textInput()->label('รหัสคู่ค้า')?>
            <?php
                // $form->field($model, 'display_name')->textInput(['maxlength' => true])
            ?>
            <?= $form->field($model, 'function')->textInput(['maxlength' => true])->label("ตำแหน่ง") ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'active')->checkbox(['label'=>'Active']) ?>
            <?php // $form->field($model, 'employee')->checkbox() ?>
            
            <?= $form->field($model, 'supplier')->checkbox(['label'=>'เป็นผู้ขาย']) ?>

            <?= $form->field($model, 'customer')->checkbox(['label'=>'เป็นผู้ซื้อ']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true])->label("Email") ?>
            <?= $form->field($model, 'fax')->textInput(['maxlength'=>true])->label('Fax')?>
            <?= $form->field($model, 'contact_person')->textInput(['maxlength'=>true])->label("Contact Person")?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'sale_area_id')->dropDownList($area,['prompt'=>'-ไม่ระบุ-'])->label('เขตการขาย')?>
        </div>
    </div>
    <?= $form->field($model, 'is_company')->checkbox(['label'=>'เป็นบริษัท'])
                    ->hint('เลือกเมื่อคู้ค้าเป็นบริษัท') ?>
    <?php
    // The controller action that will render the list
    $url = \yii\helpers\Url::to(['partner-list']);

    // Get the initial city description
    $parent_name = empty($model->parent_id) ? '' : ResPartner::findOne($model->parent_id)->name;

    $formatJs = '
        var formatRepoSelection = function(partner){
            return partner.text;
        }
    ';

    $this->registerJs($formatJs,View::POS_HEAD);

    echo $form->field($model, 'parent_id')->widget(Select2::classname(), [
        'initValueText' => $parent_name, // set the initial display text
        'options' => ['placeholder' => 'Search for a company ...'],
        'pluginOptions' => [
            //'dropdownParent' => new JsExpression("$('#partner-modal')"),
            'allowClear' => true,
            //'minimumInputLength' => 1,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => $url,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            //'templateResult' => new JsExpression('function(partner) { return partner.name; }'),
            'templateSelection' => new JsExpression('formatRepoSelection'),
        ]
    ])->label('สังกัดบริษัท')
            ->hint('กรณีที่คู่ค้ามีการกำหนดค่าสังกัดบริษัท ข้อมูลที่อยู่ในเอกสารจะใช้ที่อยู่ของบริษัท');
    ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'tax_no')->textInput(['maxlength' => true])->label('เลขประจำตัวผู้เสียภาษี') ?>
            <?= $form->field($model,'account_receivable_id')->dropDownList($accounts,['prompt'=>""])->label('บัญชีลูกหนี้ A/R')?>
            <?= $form->field($model,'account_payable_id')->dropDownList($accounts,['prompt'=>""])->label('บัญชีเจ้าหนี้ A/P')?>
            
        </div>
        <div class="col-sm-6">
            <?php
            $url = \yii\helpers\Url::to(['partner-address-list','partner_id'=>$model->id]);
            $defaultAddress = ResAddress::findOne($model->default_address_id);
            $defaultAddressName = empty($defaultAddress) ? '' : $defaultAddress->displayName;
            echo $form->field($model,'default_address_id')->widget(Select2::className(),[
                'initValueText' => $defaultAddressName, // set the initial display text
                'options' => ['placeholder' => 'เลือกที่อยู่ตั้งต้น ...'],
                'pluginOptions' => [
                    //'dropdownParent' => new JsExpression("$('#partner-modal')"),
                    'allowClear' => true,
                    //'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => $url,
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { 
                            return {
                                q:params.term
                                }; 
                            }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup){
                        //console.log("escapeMarkup",markup);
                        return markup; }'),
                    'templateResult' => new JsExpression('function(partner) {
                        //console.log("templateResult",partner);
                        return partner.text; }'),
                    //'templateSelection' => new JsExpression('formatRepoSelection'),
                ]
            ])
            ->label('ที่อยู่ตั้งต้น')
            ->hint('กรณีไม่ระบุที่อยู่ของคู่ค้าในเอกสารต่างๆ เอกสารจะใช้ที่อยู่ตั้งต้นจะเป็นค่าปรกติ');
            ?>
        </div>
    </div>
    
    
    <?php  $form->field($model, 'type')->dropDownList(['contact'=>'contact']) ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <h3>Addresses:</h3>
    <?= $this->render('_address_form_1',['model'=>$model,'form'=>$form])?>
    
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>
    
    <?php ActiveForm::end(); ?>
    
    <hr/>
    

</div>
