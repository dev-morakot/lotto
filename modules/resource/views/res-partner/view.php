<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResPartner */
?>
<?php
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'คู่ค้า'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name];

$formatter = app\components\AppHelper::defaultFormatter();
?>
<p><?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?></p>
<div class="res-partner-view">
    <div class="row">
        <div class="col-lg-6">
            <h4><?=$model->name?></h4>
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'code:text:รหัสคู่ค้า',
                    'name',
                    //'display_name',
                    'function:text:ตำแหน่ง',
                    'tax_no:text:เลขประจำตัวผู้เสียภาษี',
                    'email:email:Email',
                    'mobile:text:เบอร์โทรศัพท์มือถือ',
                    'phone:text:เบอร์โทรศัพท์',
                    'fax:text:เบอร์แฟกซ์'
                    
                ],
            ])
            ?>
            
            <h4>ที่อยู่ (Default)</h4>
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'defaultAddress.address1:text:ที่อยู่',
                    'defaultAddress.address2:text:ที่อยู่2',
                    'defaultAddress.district.name:text:อำเภอ/เขต',
                    'defaultAddress.province.name:text:จังหวัด',
                    'defaultAddress.country.name:text:ประเทศ'
                ],
            ])
            ?>
        </div>
        <div class="col-lg-6">
            <h4>ข้อมูลระบบ</h4>
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'supplier:boolean:เป็นผู้จัดจำหน่าย',
                    'customer:boolean:เป็นลูกค้า',
                    'is_company:boolean:เป็นบริษัท',
                    [
                        'attribute' => 'company.name',
                        'label' => 'สังกัดบริษัท'
                    ],
                    'active:boolean',
                    'salearea.name:text:เขตการขาย',
                    'comment:ntext',
                ],
            ])
            ?>
            <h4>บัญชี</h4>
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'accountReceivable.displayName:text:บัญชีลูกหนี้ A/R',
                    'accountPayable.displayName:text:บัญชีเจ้าหนี้ A/P'
                ],
            ])
            ?>
            
            <h4>อื่นๆ</h4>
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'create_date:datetime:วันที่สร้าง',
                    'createUser.email:text:ผู้สร้าง',
                    'write_date:datetime:วันที่ปรับปรุง',
                    'writeUser.email:text:ผู้ปรับปรุง'
                ],
            ])
            ?>
        </div>
    </div>

    <?php Pjax::begin(['id'=>'pjax_addresses']); ?> 
    <div class="">
        <h4>ที่อยู่ (เพิ่มเติม)</h4>
    <?php
        $detailDataProvider = new yii\data\ActiveDataProvider([
           'query'=>$model->getAddresses()
        ]);
        echo GridView::widget([
        'dataProvider' => $detailDataProvider,
        'columns' => [
            'id',
            'first_name',
            'last_name',
            'company_name',
            'address1',
            'address2',
            'subdistrict.name:text:แขวง/ตำบล',
            'district.name:text:เขต/อำเภอ',
            'province.name:text:จังหวัด',
            'country.name:text:ประเทศ',
            'postal_code',
            'phone',
            'mobile',
            'fax'
        ],
    ]); ?>
        <?php Pjax::end(); ?>


</div>
