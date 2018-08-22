<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use app\modules\resource\assets\ResDocLottoAsset;


ResDocLottoAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResUsers */

$this->title = Yii::t('app', 'คีย์ข้อมูลหวย');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['/admin/default']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
    display: none !important;
}');
?>
<div ng-app="myapp" ng-cloak ng-controller="FormController" class="res-doc-lotto">
    <h1><?= Html::encode($this->title) ?></h1>   

    <form novalidate class="form-horizontal" name="form">
        <div id="my-message"></div>
        <div class="well">
            <div class="row">
                <div class="col-sm-6">

                    <div class="form-group form-group-sm">
                        <label class="control-label col-sm-3">ผู้ซื้อ/คนเดินโพย</label>
                        <div class="col-sm-9">
                            <bic-res-users-select bic-model="model.user"
                                on-delete="modUserRemove(cUser)"
                                on-select="modUserChange(cUser)">
                            </bic-res-users-select>

                          
                        </div>
                    </div>

                    <div class="form-group form-group-sm">
                        <label class="control-label col-sm-3">ตัวเลข</label>
                        <div class="col-sm-9">
                            <input type="text" name="input" class="form-control bic-required-field" id="f_1" ng-keydown="keydown($event,2)" ng-model="model.number" min="0" max="999" required />
                            <small class="help-block help-block-error" ng-show="form.input.$error.max">กรุณาใส่จำนวนตัวเลขไม่เกิน 3 หลัก</small>
                        </div>
                        
                    </div>
                    

                    <div class="form-group form-group-sm">
                        <label class="control-label col-sm-3">บน (จำนวนเงิน)</label>
                        <div class="col-sm-9">
                            <input type="text" name="top_amount" class="form-control bic-required-field" id="f_2" ng-keydown="keydown($event,3)" ng-model="model.top_amount" />
                        </div>
                    </div>

                    <div class="form-group form-group-sm">
                        <label class="control-label col-sm-3">โต๊ด (จำนวนเงิน)</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bic-required-field" id="f_3" ng-keydown="keydown($event,4)" name="otd_amount" ng-model="model.otd_amount" />
                        </div>
                    </div>

                    <div class="form-group form-group-sm">
                        <label class="control-label col-sm-3">ล่าง (จำนวนเงิน)</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bic-required-field" id="f_4" ng-keydown="keydown($event, 5)" name="below_amount" ng-model="model.below_amount" />
                        </div>
                    </div>

                    

                </div>
            </div>
        </div>
    </form>

    <div class="pull-left">
        <button type="button" class="btn btn-primary"  id="f_5" ng-click="openAddLine()">เพิ่มรายการ</button>
    </div>
    <div class="clearfix"></div>

    <div id="gridModelLine">
    <table class="table table-striped table-hover" style="margin-top: 15px">
        <thead>
            <tr>
                <th></th>
                <th style="text-align: center">ประเภทหวย</th>
                <th style="text-align: center">ตัวเลข </th>
                <th style="text-align: center">จำนวนเงิน </th>
                <th style="text-align: center">ผู้ซื้อ </th>
                <th style="text-align: center">-</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="line in lottos">
                <td align="center">
                    <span class="label label-primary">{{ $index + 1 }}</span>
                </td>
                <td align="center">{{ line.type }}</td>
                <td align="center">{{ line.number }}</td>
                <td align="right"> {{ line.amount | number }}</td>
                <td align="center">{{ line.firstname }}</td>
                <td align="center">
                    <span class="btn btn-warning btn-sm" ng-click="doRemoveLine(line)">ลบ</span>
                </td>
            </tr>
        </tbody>
    </table>
    </div>

    <form novalidate class="form-horizontal">
        <div class="row">
            <div class="col-sm-6"></div>
            <div class="col-sm-6 form-horizontal">
                <div class="form-group form-group-sm">
    				<div class="col-xs-3"></div>
    				<div class="col-xs-9">
    					<div style="border-top:thick solid #D3D3D3"></div>
    				</div>
    			</div>
                

                <div class="form-group form-group-sm">
                    <label for="purchaseorder-amount_untaxed" class="col-xs-3 control-label">ยอดรวมทั้งหมด </label>
                    <div class="col-xs-9">
                        <p id='purchaseorder-amount_total' 
                        style="background-color:#449D44;color: white;"
                           class="form-control show-number text-right" 
                           >{{modline.amount_total| number }}</p>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <div class="row" style="margin-bottom: 10px">
        <div class="col-sm-6">
            <button type="button" class="btn btn-primary" ng-click="formSave()"> บันทึก </button>
            <?php
            echo Html::a('ยกเลิก', Url::to(['index']), ['class' => 'btn btn-warning']);
            ?>
        </div>
    </div>

</div>