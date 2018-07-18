<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\resource\assets\ResGroupFormAsset;
use app\modules\resource\models\ResDocSequence;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResGroup */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
    ResGroupFormAsset::register($this);
?>
<div class="res-group-form" >
    <input type="hidden" id='model_id' value="<?=$model->id?>">
           
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

  
    <div class="form-group">
        
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), 
                [
                    'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                    ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <hr/>
    <h5>จัดการผู้ใช้</h5>
    <div ng-app="ResGroupApp" 
         ng-controller="ResGroupFormController as ctrl">
    
    <div class="row">
        <div class="col-sm-6">
            <div class="input-group">
                {{model.user}}
                <select id='users' class="form-control"
                        ng-model="model.user_id" >
                    <option ng-value="null">-- เลือก --</option>
                    <option ng-repeat="pt in users" value="{{pt.id}}">
                        {{pt.firstname}} {{pt.lastname}}
                        [{{pt.email}}]</span>
                    </option>

                </select> 
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" ng-click="addUserToGroup()">เพิ่ม</button>
                </span>
            </div><!-- /input-group -->

        </div>
    </div>
    <div class="row">
        <div class='col-sm-6'>
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>รหัส</th>
                        <th>ชื่อ/นามสกุล</th>
                        <th>Email</th>
                        <th>-</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat='line in group_users'>
                        <td>{{line.id}}</td>
                        <td>{{line.firstname}} {{line.lastname}}</td>
                        <td>{{line.email}}</td>
                        <td><button class="btn btn-danger btn-sm" ng-click="deleteGroupUser(line)">ลบ</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>
