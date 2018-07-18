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
                    <th style='text-align: center'>จำนวนเงิน</th>
                    <th style='text-align: center'>รายละเอียด</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat-start="line in result">
                    <td>{{ line.type }}</td>
                    <td>{{ line.number }}</td>
                    <td align='right'>{{ line.amount | number }}</td>
                    <td align="center">
                        <button ng-if="line.expanded" ng-click="line.expanded = false" class='btn btn-primary btn-sm'><i class='glyphicon glyphicon-minus'></i></button>
                        <button ng-if="!line.expanded" ng-click="expandSelected(line)" class='btn btn-primary btn-sm'><i class='glyphicon glyphicon-plus'></i></button>
                    </td>
                </tr>
                <tr ng-if="line.expanded" ng-repeat-end="">
                    <td colspan='4'>
                        <p> ชื่อ : {{ line.name }} - {{ line.lastname }} </p>
                        <p>มีส่วนลด (ถ้ามี) :  {{ line.discount }}   %</p>
                        <p>วิธีคำนวณค่าหวย :  ราคาหวย  *  {{ line.message }}  </p>
                        <p>รวมเงิน : {{ line.amount_total | number }} </p>
                    </td>
                   
                </tr>
            </tbody>
        </table>

    </div>

    </div>
</div>