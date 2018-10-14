<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResResTraints */

$this->title = $model->number_limit;
$this->params['breadcrumbs'][] = ['label' => 'Res Res Traints', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="res-res-traints-view">

    <h1>เลขไม่รับซื้อ : <?= Html::encode($this->title) ?></h1>

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
            'number_limit',
            [
                'label' => 'ประเภทหวย',
                'format' => 'html',
                'value' => function ($model) {
                    if($model['type'] == 'two_top') {
                        return 'สองตัวบน';
                    } else if($model['type'] == 'two_below') {
                        return 'สองตัวล่าง';
                    } else if($model['type'] == 'two_all') {
                        return 'สองตัวบน - ล่าง';
                    }
                }
            ],
            [
                'label' => 'Active',
                'format' => 'html',
                'value' => function ($model) {
                    if($model->active) {
                        return "<span class='label label-success'> Yes </span>";
                    } else {
                        return "<span class='label label-danger'> No</span>";
                    }
                    
                }
            ],
            'create_uid',
            'create_date',
            'write_uid',
            'write_date',
        ],
    ]) ?>

</div>