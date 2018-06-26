<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Logs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Log'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            //'level',
            'category',
            ['attribute'=>'log_time',
                'value'=>function($model){
                    $datetime = new DateTime();
                    $datetime->setTimestamp($model->log_time);
                    $datetime->setTimezone(new DateTimeZone('Asia/Bangkok'));
                    //date_timestamp_set($datetime, $model->log_time);
                    $out = $datetime->format('d/m/Y H:i:s');
                    return $out;
                }
                ],
            'log_time',
            'prefix:ntext',
            'message:ntext',
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
