<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\resource\models\ResAttachModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Res Attaches';
$this->params['breadcrumbs'][] = $this->title;

$this->params['body_container'] = 'container-fluid';
?>
<?php
    $formatter = app\components\AppHelper::defaultFormatter();
?>
<div class="res-attach-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Res Attach', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'date',
            'description:ntext',
            'file',
            // 'revision',
            // 'hash',
            'origin',
            'related_model',
            'createUser.firstname:text:ผู้สร้าง',
            'state',
            'isExist:boolean:Exist',
            //'fullPath:text',
            // 'related_id',
            // 'company_id',
            // 'create_uid',
            // 'create_date',
            // 'write_uid',
            // 'write_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
