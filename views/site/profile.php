<?php

use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'ERP Application';
?>
<?php
$role_str = "";
$authManager = Yii::$app->authManager;
$idField = Yii::$app->getModule('rbac')->userModelIdField;
$roles = [];
foreach ($authManager->getRolesByUser($model->{$idField}) as $role) {
    $roles[] = $role->name;
}
if (count($roles) == 0) {
    $role_str = Yii::t("yii", "(not set)");
} else {
    $role_str = implode(",", $roles);
}
?>
<div class="site-profile">

    <div class="body-content">
        <div class="panel panel-default">
            <div class="panel-heading">Profile</div>
            <div class="panel-body">
                <dl class="dl-horizontal">
                    <dt>รหัสพนักงาน</dt>
                    <dd><?= $model->code?></dd>
                    <dt>ชื่อ</dt>
                    <dd><?= $model->firstname ?></dd>
                    <dt>นามสกุล</dt>
                    <dd><?= $model->lastname ?></dd>
                    <dt>ฝ่าย</dt>
                    <dd><?= $model->groupDisplay ?></dd>
                    <dt>สิทธิการใช้งานระบบ</dt>
                    <dd><?= $role_str ?></dd>
                </dl>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">Password Reset</div>
            <div class="panel-body">
                <?=Html::a('ตั้งรหัสผ่านใหม่', ['/site/request-password-reset'])?>
            </div>
        </div>
    </div>
</div>
