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
        <a href="#" ng-click="SaveCut()" data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm">สรุปส่วน ตัดเก็บ</a>
        <a href="#" ng-click="SaveSend()" data-toggle="modal" data-target="#Send" class="btn btn-success btn-sm">สรุปส่วน ตัดส่ง</a>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-sm-4">

                <table class="table table-bordered table-striped" style='margin-top: 15px'>
                    <thead>
                        <tr>
                            <th colspan='2' style='text-align: center'> สามตัวบน </th>
                        </tr>
                        <tr>
                            <th style='text-align: center'> ตัวเลข </th>
                            <th style='text-align: center'>  จำนวนเงิน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="line in three_top | orderBy: 'number'">
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

        <div class="col-sm-4">

          
<table class="table table-bordered table-striped" style='margin-top: 15px'>
    <thead>
        <tr>
            <th colspan='2' style='text-align: center'>สามตัวโต๊ด</th>
        </tr>
        <tr>
            <th style='text-align: center'> ตัวเลข </th>
            <th style='text-align: center'>  จำนวนเงิน</th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="line in three_otd | orderBy: 'number'">
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

        <div class="col-sm-4">

            
                <table class="table table-bordered table-striped" style='margin-top: 15px'>
                    <thead>
                        <tr>
                            <th colspan='2' style='text-align: center'> สามตัวล่าง </th>
                        </tr>
                        <tr>
                            <th style='text-align: center'> ตัวเลข </th>
                            <th style='text-align: center'>  จำนวนเงิน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="line in three_below | orderBy: 'number'">
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

    


        <!-- Modal ตัดเก็บ -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style='width: 60%'>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">สรุปยอดตัดเก็บ เลขสามตัว</h4>
      </div>
      <div class="modal-body">

      <div id='exportable' class="export-table row">
        <div class="col-sm-4">

                <table class="table table-bordered table-striped" style='margin-top: 15px'>
                    <thead>
                        <tr>
                            <th colspan='2' style='text-align: center'> สามตัวบน </th>
                        </tr>
                        <tr>
                            <th style='text-align: center'> ตัวเลข </th>
                            <th style='text-align: center'>  จำนวนเงิน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="line in amount_three_top | orderBy: 'number'">
                            <td align='center'>{{ line.number }}</td>
                            <td align='right' >{{ line.amount | number }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>รวม</td>
                            <td align='right'>{{ amount_total_three_top | number }}</td>
                        </tr>
                    </tfoot>
                </table>
            
            </div>

            <div class="col-sm-4">

<table class="table table-bordered table-striped" style='margin-top: 15px'>
    <thead>
        <tr>
            <th colspan='2' style='text-align: center'>สามตัวโต๊ด</th>
        </tr>
        <tr>
            <th style='text-align: center'> ตัวเลข </th>
            <th style='text-align: center'>  จำนวนเงิน</th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="line in amount_three_otd | orderBy: 'number'">
            <td align='center'>{{ line.number }}</td>
            <td align='right' >{{ line.amount | number }}</td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td>รวม</td>
            <td align='right'>{{ amount_total_three_otd | number }}</td>
        </tr>
    </tfoot>
</table>
</div>

            <div class="col-sm-4">

                <table class="table table-bordered table-striped" style='margin-top: 15px'>
                    <thead>
                        <tr>
                            <th colspan='2' style='text-align: center'> สามตัวล่าง </th>
                        </tr>
                        <tr>
                            <th style='text-align: center'> ตัวเลข </th>
                            <th style='text-align: center'>  จำนวนเงิน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="line in amount_three_below | orderBy: 'number'">
                            <td align='center'>{{ line.number }}</td>
                            <td align='right' >{{ line.amount | number }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>รวม</td>
                            <td align='right'>{{ amount_total_three_below | number }}</td>
                        </tr>
                    </tfoot>
                </table>
            
            </div>
            


        </div>




      </div>
      <div class="modal-footer">
        <button type="button" ng-click="downloadPdf()" class="btn btn-default">พิมพ์รายงาน</button>
        <button type="button" ng-click="exportData()" class="btn btn-primary">บันทึกเป็น Excel</button>
      </div>
    </div>
  </div>
</div>





        <!-- Modal ตัดส่ง -->
<div class="modal fade" id="Send" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style='width: 60%'>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">สรุปยอดตัดส่ง เลขสามตัว</h4>
      </div>
      <div class="modal-body">

      <div id='exportableSend' class="export-table row">
        <div ng-show="line_total_three_top.length !== 0" class="col-sm-4">

                <table class="table table-bordered table-striped" style='margin-top: 15px'>
                    <thead>
                        <tr>
                            <th colspan='2' style='text-align: center'> สามตัวบน </th>
                        </tr>
                        <tr>
                            <th style='text-align: center'> ตัวเลข </th>
                            <th style='text-align: center'>  จำนวนเงิน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="line in line_total_three_top | orderBy: 'number'">
                            <td align='center'>{{ line.number }}</td>
                            <td align='right' >{{ line.amount | number }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>รวม</td>
                            <td align='right'>{{ total_three_top | number }}</td>
                        </tr>
                    </tfoot>
                </table>
            
            </div>

            <div ng-show="line_total_three_otd.length !== 0" class="col-sm-4">

<table class="table table-bordered table-striped" style='margin-top: 15px'>
    <thead>
        <tr>
            <th colspan='2' style='text-align: center'>สามตัวโต๊ด</th>
        </tr>
        <tr>
            <th style='text-align: center'> ตัวเลข </th>
            <th style='text-align: center'>  จำนวนเงิน</th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="line in line_total_three_otd | orderBy: 'number'">
            <td align='center'>{{ line.number }}</td>
            <td align='right' >{{ line.amount | number }}</td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td>รวม</td>
            <td align='right'>{{ total_three_otd | number }}</td>
        </tr>
    </tfoot>
</table>
</div>


            <div ng-show="line_total_three_below.length !== 0" class="col-sm-4">

                <table class="table table-bordered table-striped" style='margin-top: 15px'>
                    <thead>
                        <tr>
                            <th colspan='2' style='text-align: center'> สามตัวล่าง </th>
                        </tr>
                        <tr>
                            <th style='text-align: center'> ตัวเลข </th>
                            <th style='text-align: center'>  จำนวนเงิน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="line in line_total_three_below | orderBy: 'number'">
                            <td align='center'>{{ line.number }}</td>
                            <td align='right' >{{ line.amount | number }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>รวม</td>
                            <td align='right'>{{ total_three_below | number }}</td>
                        </tr>
                    </tfoot>
                </table>
            
            </div>
            


        </div>




      </div>
      <div class="modal-footer">
        <button type="button" ng-click="downloadPdfSend()" class="btn btn-default">พิมพ์รายงาน</button>
        <button type="button" ng-click="exportDataSend()" class="btn btn-primary">บันทึกเป็น Excel</button>
      </div>
    </div>
  </div>
</div>

    </div>
</div>