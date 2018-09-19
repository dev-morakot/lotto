<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use app\modules\resource\assets\ResDocCheckLottoAsset;


ResDocCheckLottoAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResUsers */

$this->title = Yii::t('app', 'ตรวจหวย');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['/admin/default']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
    display: none !important;
}');
?>
<div ng-app="myapp" ng-cloak ng-controller="FormController" class="res-doc-check-lotto">
<iframe src="https://www.lottery.co.th/show" width="100%" height="340" frameborder="0"></iframe>
<br />
<hr />
    <div class="row">
    <div class="col-sm-6">
        <h1><?= Html::encode($this->title) ?></h1>   
        <div class="well">
        <form class="form-horizontal" role="form" ng-submit="addRow()">
            
	        <div class="form-group">
		        <label class="col-md-2 control-label">ตัวเลข</label>
		        <div class="col-md-6">
			        <input type="text" class="form-control" name="name" ng-model="model.number" />
		        </div>
	        </div>

	        <div class="form-group">								
		        <div style="padding-left:110px">
			    <input type="submit" ng-click="openAddLine()" value="บันทึก" class="btn btn-primary"/>
		        </div>
	        </div>
        </form>
        </div>
    </div>
    
    <div class="col-sm-6">
        <h1>ผลการตรวจ</h1>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th style='text-align: center'>ประเภทหวย</th>
                    <th style='text-align: center'>ตัวเลข</th>
                    <!--<th style='text-align: center'>จำนวนเงิน</th>-->
                    <th style='text-align: center'>รายละเอียด</th>
                </tr>
            </thead>
            <tbody ng-repeat="line in result">
                <tr>
                    <td>{{ line.type }}</td>
                    <td>{{ line.number }}</td>
                   <!-- <td align='right'>{{ line.amount | number }}</td> -->
                    <td align="center">
                        <button ng-if="line.expanded" ng-click="line.expanded = false" class='btn btn-primary btn-sm'><i class='glyphicon glyphicon-minus'></i></button>
                        <button ng-if="!line.expanded" ng-click="expandSelected(line)" class='btn btn-primary btn-sm'><i class='glyphicon glyphicon-plus'></i></button>
                    </td>
                </tr>
                <tr ng-if="line.expanded" ng-repeat="row in line.rows">
                    <td colspan='4'>
                        <p> ชื่อ : {{ row.name }} - {{ row.lastname }} </p> 
                        <p>เลข :  <span class='badge'> {{ row.number }} </span> </p>
                        <p>ราคาซื้อ: {{ row.amount | number }}   บาท</p>
                        <p>มีส่วนลด (ถ้ามี) :  {{ row.discount }}   %</p>
                        <p>ราคาหวยที่ซื้อทั้งหมด :  {{ row.all_amount_lotto }}  บาท </p>
                        <p>วิธีคำนวณค่าหวย : ค่าหวยที่ซือทั้งหมด - ส่วนลด% (ถ้ามี)  </p>
                        <p>ถ้าถูกหวย : ราคาที่ซื้อ * ราคาหวย </p>
                        <p>จากสูตร : {{ row.all_amount_lotto }} - {{ row.discount }} %</p>
                        <p>จากสูตร :  {{ row.amount | number }} * {{ row.message }}</p>
                        <p>ดังนั้น : {{ row.line_total }} - {{ row.diff_amount }}</p>
                        <p>รวมเงิน : {{ row.amount_total | number }} </p>
                    </td>
                   
                </tr>
            </tbody>
        </table>

    </div>

    </div>
</div>