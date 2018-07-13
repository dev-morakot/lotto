<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\modules\resource\assets\ResDocReportAllAsset;
use app\modules\resource\models\ResDocLotto;

ResDocReportAllAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResUsers */

$this->title = Yii::t('app', 'สรุปเลขทั้งหมด');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['/admin/default']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
    display: none !important;
}');


?>
<div ng-app="myapp" ng-controller="FormController as ctrl" ng-cloak class="res-doc-report">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="well">
        <form class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-2 control-label">ประเภทหวย </label>
                <div class="col-sm-5">
                    <select class='form-control' ng-change="ctrl.loadData()" ng-model='model.type'>
                            <option value=''>แสดงทั้งหมด </option>
                            <option ng-repeat="q in arr" ng-value="q.type" >{{ q.type }}</option>
                        </select>
                </div>
            </div>
        </form>
    
    </div>
    <div class="pull-left">        
        <a href="#" class="btn btn-primary btn-sm" ng-click="exportData()"> Export Excel</a>
        
        <a href="#" class="btn btn-primary btn-sm" ng-click="downloadPdf()"> Export Pdf</a>
    </div>
    <div class="clearfix"></div>
   

    <div id='exportPdf' class="table-responsive">
    
        <table class="export-table table table-bordered table-striped" style='margin-top:10px'>
            <thead>
                <tr>
                    <th style='text-align:center'> ประเภทหวย</th>
                    <th style='text-align:center'>ตัวเลข</th>
                    <th style='text-align:center'>จำนวนเงิน</th>
                    <th style='text-align:center'>ลบ</th>
                </tr>
            </thead>
            <tbody>               
                <tr ng-repeat="line in result">
                    <td>{{ line.type }}</td>
                    <td align='right'>{{ line.number }}</td>
                    <td align='right'>{{ line.amount | number }}</td>
                    <td align='center'>
                       <a href="#" class="btn btn-danger btn-sm" ng-click="delete(line)"> ลบ </a>
                    </td>
                </tr>
                
            </tbody>
            <tfoot>
                <tr>
                    <td colspan='2'>รวม</td>
                    <td align='right'>{{ sum | number }} </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        </div>
        <div id="exportable" style='display:none'>
            
            <?php echo $this->render('_all-excel'); ?>
        
         </div>
</div>
