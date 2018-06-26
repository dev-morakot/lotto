<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResUsers */

$this->title = $model->firstname."     ". $model->lastname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'ผู้ซื้อ/คนเดินโพยหวย'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="res-users-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            //'verifymail_token',
            'email:email',
            'firstname',
            'lastname',
            'discount',
            'active:boolean',
           
//            'company_id',
//            'default_section_id',
//            'create_uid',
//            'create_date',
//            'write_uid',
//            'write_date',
        ],
    ]) ?>

    <h4>กลุ่ม/สังกัด</h4>
    <?php
        $detailDataProvider = new yii\data\ActiveDataProvider([
            'query' => $model->getGroups()
        ]);
        
        echo GridView::widget([
            'dataProvider' => $detailDataProvider,
            'columns' => [
                'id',
                'code',
                'name'
                
            ],
        ]);
        ?>
    
</div>
