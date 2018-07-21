<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use app\modules\resource\assets\ResReportTwoAsset;


ResReportTwoAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResUsers */

$this->title = Yii::t('app', 'สรุปเลขสองตัว');
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
        <a href="#" ng-click="SaveSend()" data-toggle="modal" data-target="#mySend" class="btn btn-success btn-sm">สรุปส่วน ตัดส่ง</a>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-sm-6">

       
                <table class="table table-bordered table-striped" style='margin-top: 15px'>
                    <thead>
                        <tr>
                            <th colspan='2' style='text-align: center'> สองตัวบน</th>
                        </tr>
                        <tr>
                            <th style='text-align: center'> ตัวเลข </th>
                            <th style='text-align: center'>  จำนวนเงิน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="line in two_top">
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

        <div class="col-sm-6">

           
                <table class="table table-bordered table-striped" style='margin-top: 15px'>
                    <thead>
                        <tr>
                            <th colspan='2' style='text-align: center'> สองตัวล่าง </th>
                        </tr>
                        <tr>
                            <th style='text-align: center'> ตัวเลข </th>
                            <th style='text-align: center'>  จำนวนเงิน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="line in two_below">
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

    <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style='width: 60%'> 
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">สรุปยอดตัดเก็บ เลขสองตัว</h4>
      </div>
      <div class="modal-body">

        <div id='exportable' class="export-table row">
            <div class="col-sm-6">

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th colspan='2' style='text-align: center'> เลขสองตัวบน </th>
                        </tr>
                        <tr>
                            <th style='text-align: center'> ตัวเลข </th>
                            <th style='text-align: center'>  จำนวนเงิน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="line in two_cut_top_amount">
                            <td align='center'>{{ line.number }}</td>
                            <td align='right' >{{ line.amount | number }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>รวม</td>
                            <td align='right'>{{ amount_total_top | number }}</td>
                        </tr>
                    </tfoot>
                </table>
            
            </div>
            <div class="col-sm-6">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th colspan='2' style='text-align: center'> เลขสองตัวล่าง </th>
                        </tr>
                        <tr>
                            <th style='text-align: center'> ตัวเลข </th>
                            <th style='text-align: center'>  จำนวนเงิน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="line in two_cut_below_amount">
                            <td align='center'>{{ line.number }}</td>
                            <td align='right' >{{ line.amount | number }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>รวม</td>
                            <td align='right'>{{ amount_total_below | number }}</td>
                        </tr>
                    </tfoot>
                </table>
            
            </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" ng-click="downloadPdf()" class="btn btn-default">พิมพ์รายงาน</button>
        <button type="button" ng-click="exportData()" class="btn btn-primary">บันทึก Excel</button>
      </div>
    </div>
  </div>
</div>




<!-- Modal ตัดส่วนส่ง-->
<div class="modal fade" id="mySend" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style='width: 60%'> 
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">สรุปยอดส่วนตัดส่ง เลขสองตัว</h4>
      </div>
      <div class="modal-body">

        <div id='exportableSend' class="export-table row">
            <div class="col-sm-6">

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th colspan='2' style='text-align: center'> เลขสองตัวบน </th>
                        </tr>
                        <tr>
                            <th style='text-align: center'> ตัวเลข </th>
                            <th style='text-align: center'>  จำนวนเงิน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="line in two_send_top_amount">
                            <td align='center'>{{ line.number }}</td>
                            <td align='right' >{{ line.amount | number }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>รวม</td>
                            <td align='right'>{{ total_top | number }}</td>
                        </tr>
                    </tfoot>
                </table>
            
            </div>
            <div class="col-sm-6">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th colspan='2' style='text-align: center'> เลขสองตัวล่าง </th>
                        </tr>
                        <tr>
                            <th style='text-align: center'> ตัวเลข </th>
                            <th style='text-align: center'>  จำนวนเงิน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="line in two_send_below_amount">
                            <td align='center'>{{ line.number }}</td>
                            <td align='right' >{{ line.amount | number }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>รวม</td>
                            <td align='right'>{{ total_below | number }}</td>
                        </tr>
                    </tfoot>
                </table>
            
            </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" ng-click="downloadPdfSend()" class="btn btn-default">พิมพ์รายงาน</button>
        <button type="button" ng-click="exportDataSend()" class="btn btn-primary">บันทึก Excel</button>
      </div>
    </div>
  </div>
</div>


</div>