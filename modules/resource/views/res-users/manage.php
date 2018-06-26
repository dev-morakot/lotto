<?php

use yii\helpers\Html;
use app\modules\resource\assets\ResUsersManageAsset;

/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResUsers */

$this->title = Yii::t('app', 'จัดการผู้ใช้');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Res Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
ResUsersManageAsset::register($this);
?>
<div ng-app="ResUsersApp"
     ng-controller="ResUsersManageController as ctrl"
    class="res-users-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <b>ค้นหา: </b><input type="text" ng-model="ctrl.usersearch" ng-change="ctrl.refresh()"></input>
    <div class="row">
        <div class="col-sm-6">
            <h3>ผู้ใช้ส่วนกลาง</h3>
            
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>...</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="u in ctrl.center_users">
                        <td>{{u.id}}</td>
                        <td>{{u.email}}</td>
                        <td>{{u.firstname}}</td>
                        <td>{{u.lastname}}</td>
                        <td>
                            <button class="btn btn-sm btn-primary"
                                    ng-click="ctrl.addToCenter(u)">Add</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-6">
            <h3>ผู้ใช้ระบบ</h3>
            
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>...</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="u in ctrl.res_users">
                        <td>{{u.id}}</td>
                        <td>{{u.email}}</td>
                        <td>{{u.firstname}}</td>
                        <td>{{u.lastname}}</td>
                        <td>
                            
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="help help-block">เพิ่มผู้ใช้ส่วนกลางเข้าสู่ระบบ ให้ผู้ใช้ส่วนกลางสามารถเข้าใช้งานระบบได้</div>
        
</div>
