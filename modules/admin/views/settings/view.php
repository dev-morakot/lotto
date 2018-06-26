<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Log */

$this->title = 'ตั้งค่าเริ่มต้นบริษัท';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Organization Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
     
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'name_en',
            'legal_name',
            'currency.name:text:สกุลเงิน',
            [
                'label'=>'โลโก้',
                'format'=>'raw',
                'value'=>Html::img(Yii::getAlias('@web/img_com/'.$model->logo),['width'=>'120'])
            ],
            'tax_no'
        ],
    ]) ?>
    
    <h4>Accountings:</h4>
    <div class="help-block">ตั้งค่าเริ่มต้น สำหรับการดำเนินรายการทางบัญชี</div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'accountReceivable.displayName:text:บัญชีลูกหนี้',
            'taxReceivable.displayName:text:บัญชีภาษีขาย',
            'accountPayable.displayName:text:บัญชีเจ้าหนี้',
            'taxPayable.displayName:text:บัญชีภาษีซื้อ',
            'saleJournal.displayName:text:สมุดบัญชีขาย(ตั้งหนี้)',
            'purchaseJournal.displayName:text:สมุดบัญชีซื้อ(ตั้งหนี้)',
            'payInJournal.displayName:text:สมุดบัญชีรับ(Pay In)',
            'payOutJournal.displayName:text:สมุดบัญชีจ่าย(Pay Out)',
            'generalJournal.displayName:text:สมุดปัญชีทั่วไป'
            ],
    ]) ?>

</div>
