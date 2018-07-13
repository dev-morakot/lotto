<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use app\modules\resource\assets\ResReportThreeAsset;


ResReportThreeAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResUsers */

$this->title = Yii::t('app', 'สรุปเลขสามตัว');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['/admin/default']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
    display: none !important;
}');
?>
<div ng-app="myapp" ng-controller="FormController" ng-cloak class="res-doc-report">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="pull-left">
        <a href="#" ng-click="SaveCut()" class="btn btn-primary btn-sm">สรุปส่วน ตัดเก็บ</a>
        <a href="#" ng-click="SaveSend()" class="btn btn-success btn-sm">สรุปส่วน ตัดส่ง</a>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-sm-4">

        <div class="panel panel-primary" style='margin-top: 15px'>
            <div class="panel-heading">    สามตัวบน </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style='text-align: center'> ตัวเลข </th>
                            <th style='text-align: center'>  จำนวนเงิน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="line in three_top">
                            <td align='center'>{{ line.number }}</td>
                            <td align='right' >{{ line.amount | number }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>รวม</td>
                            <td align='right'>{{ sum_top | number }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>        
        </div>
        
        </div>

        <div class="col-sm-4">

            <div class="panel panel-primary" style='margin-top: 15px'>
                <div class="panel-heading"> สามตัวล่าง </div>
                <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style='text-align: center'> ตัวเลข </th>
                            <th style='text-align: center'>  จำนวนเงิน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="line in three_below">
                            <td align='center'>{{ line.number }}</td>
                            <td align='right' >{{ line.amount | number }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>รวม</td>
                            <td align='right'>{{ sum_below | number }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>        
        </div>

    </div>

    <div class="col-sm-4">

            <div class="panel panel-primary" style='margin-top: 15px'>
                <div class="panel-heading"> สามตัวโต๊ด </div>
                <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style='text-align: center'> ตัวเลข </th>
                            <th style='text-align: center'>  จำนวนเงิน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="line in three_otd">
                            <td align='center'>{{ line.number }}</td>
                            <td align='right' >{{ line.amount | number }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>รวม</td>
                            <td align='right'>{{ sum_otd | number }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>        
        </div>

    </div>

    </div>
</div>