<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\RelatedModelRouter;

/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResAttach */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Res Attaches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="res-attach-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
            'style'=>($model->state=='done')?'display:none':""
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'state',
            'date:datetime:วันที่',
            'description:ntext',
            'file',
            'revision',
            'hash',
            'origin',
            [
                'attribute'=>'related_id',
                'format'=>'raw',
                'value'=> RelatedModelRouter::relatedHtml($model)
            ]
        ],
    ]) ?>

</div>
