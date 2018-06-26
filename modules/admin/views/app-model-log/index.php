<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\resource\models\ResDocMessage;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\resource\models\ResDocMessage */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'App Model Logs');
$this->params['homeLink'] = ['label'=>'Admin','url'=>Url::to('default')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-userlog-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            //'user_id',
            [
                'class'=>'yii\grid\DataColumn',
                'attribute'=>'user_id',
                'value'=> 'user.username',
                'label'=> 'Username'
            ],
            [
                'class'=>'yii\grid\DataColumn',
                'attribute'=>'firstname',
                'value'=> 'user.firstname',
                'label'=> 'ผู้ใช้'
            ],
            //'ref_id',
            //'ref_model',
            //'username',
            'name',
            'message:ntext',
            'create_date',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
