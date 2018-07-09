<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use app\modules\resource\assets\ResDocLottoAsset;


ResDocLottoAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResUsers */

$this->title = Yii::t('app', 'สรุปยอดซื้อ');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['/admin/default']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
    display: none !important;
}');
?>
<div class="res-doc-report">
    <h1><?= Html::encode($this->title) ?></h1>

    <?=  Html::a('ล้างข้อมูล', Url::to(['delete']), ['class' => 'btn btn-danger']); ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped" style="margin-top:15px">
            <thead>
                <tr>
                    <th style="text-align: center">สองตัวบน</th>
                    <th style="text-align: center">สองตัวล่าง</th>
                    <th style="text-align: center">สามตัวบน</th>
                    <th style="text-align: center">สามตัวล่าง</th>
                    <th style="text-align: center">สามตัวโต๊ด</th>                   
                </tr>
            </thead>
            <tbody>                
                <tr>
                    <td align="right"><?php echo number_format($temp['two_top_amount']); ?></td>  
                    <td align="right" ><?php echo number_format($temp['two_below_amount']); ?></td>
                    <td align="right"><?php echo number_format($temp['three_top_amount']); ?></td>  
                    <td align="right"><?php echo number_format($temp['three_below_amount']); ?></td>
                    <td align="right"><?php echo number_format($temp['three_otd_amount']); ?></td>               
                </tr>
                
            </tbody>
        </table>
    </div>
</div>