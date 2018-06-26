<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResGroup */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'กลุ่มผู้ใช้งาน'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="res-group-view">

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
            'name',
            'code',
//            'create_uid',
//            'create_date',
//            'write_uid',
//            'write_date',
        ],
    ]) ?>
    
    <?php
        $detailDataProvider = new yii\data\ActiveDataProvider([
            'query' => $model->getResUsers()
        ]);
        
        echo GridView::widget([
            'dataProvider' => $detailDataProvider,
            'columns' => [
                'id',
                'firstname',
                'lastname',
                'email'
                
            ],
        ]);
        ?>

</div>
