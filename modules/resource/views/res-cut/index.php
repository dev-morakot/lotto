<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use app\modules\resource\assets\ResCutAsset;


ResCutAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResUsers */

$this->title = Yii::t('app', 'ตั้งค่าตัดเก็บรายตัว');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['/admin/default']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
    display: none !important;
}');
?>
<div ng-app="myapp" ng-cloak ng-controller="FormController" class="res-users-create">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <div  ng-if="msg === true" class='alert alert-success'>{{ submitMsg }}</div>
    
    <form novalidate class="form-horizontal" name="form">
    
        <div id="my-messsage"></div>
        <div class="well">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">3 ตัวบน</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" ng-model="model.three_top">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">3 ตัวล่าง</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" ng-model="model.three_below">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">3 ตัวโต๊ด</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" ng-model="model.tree_otd">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">2 ตัวบน</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" ng-model="model.two_top">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">2 ตัวล่าง</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" ng-model="model.two_below">
                        </div>
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