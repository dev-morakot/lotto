<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\modules\resource\assets\ResDocLottoAsset;
use app\modules\resource\models\ResDocLotto;

ResDocLottoAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResUsers */

$this->title = Yii::t('app', 'สรุปเลขทั้งหมด');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['/admin/default']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
    display: none !important;
}');

$array = [
        ['id' => 1, 'type' => 'ทั้งหมด'],
        ['id' => 2, 'type' => 'สามตัวบน'],
        ['id' => 3, 'type' => 'สามตัวล่าง'],
        ['id' => 4, 'type' => 'สามตัวโต๊ด'],
        ['id' => 5, 'type' => 'สองตัวบน'],
        ['id' => 6, 'type' => 'สองตัวล่าง']
    ];
$type = ArrayHelper::map($array, 'type', 'type');

?>
<div class="res-doc-report">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="well">
    <?php $form = ActiveForm::begin([
        'id'=>'form',
        //'action' => ['stock-card-officer'],
        'method' => 'get',
        'options'=>[
            'data-pjax'=>false,
            'class'=>'form-inline'
        ],
        'fieldConfig'=>[
            'options'=>[
                'class'=>'form-group form-group-sm'
            ],
            'template'=>'{label} <div class="row"><div class="col-sm-4" style="width:150%">{input}{error}{hint}</div></div>'
        ]
    ]); ?>
        <?php echo $form->field($model, 'type')
            ->widget(Select2::className(),[
                'data'=>$type,
                'options' => [
                    'class'=>'form-group form-group-sm',
                    'placeholder' => 'Select a type ...'
                    ],
                'pluginOptions'=>[
                    'allowClear'=>true
                ]
            ])
            ->label('ประเภทหวย');
        ?>
    </div>
    <div class="pull-left">
        <?= Html::submitButton(Yii::t('app', 'Run Report'), ['class' => 'btn btn-primary btn-sm']) ?>
        <?= Html::input('submit', 'Export Xls', 'Export Xls', ['class'=>'btn btn-info btn-sm','formaction'=>'/stock/stock-report-summary/excel'])?>
        <?= Html::input('submit', 'Export Pdf', 'Export Pdf', ['class'=>'btn btn-info btn-sm','formaction'=>'/stock/stock-report-summary/pdf'])?>    
    </div>
    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>

    
</div>
