<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'code',
            'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            //'verifymail_token',
            'email:email',
            'firstname',
            'lastname',
            'active:boolean',
            'company_id',
            'default_section_id',
            'login_date:dateTime:วันที่ Login',
            'createUser.displayName:text:ผู้สร้าง',
            'create_date:date:วันที่สร้าง',
            'writeUser.displayName:text:ผู้ปรับปรุง',
            'write_date:date:วันที่ปรับปรุง',
        ],
    ]) ?>

    <p>
        <?= Html::a(Yii::t('app', 'Reset Password'), ['request-reset-password', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'ยืนยันส่งเมล์เพื่อให้ผู้ใช้ตั้ง password ใหม่?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
</div>
