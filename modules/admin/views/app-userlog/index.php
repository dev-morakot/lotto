<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\AppUserlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'App Userlogs');
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
                'attribute'=>'user',
                'value'=> 'user.username',
                'label'=> 'Username'
            ],
            [
                'class'=>'yii\grid\DataColumn',
                'attribute'=>'firstname',
                'value'=> 'user.firstname',
                'label'=> 'ผู้ใช้'
            ],
            //'username',
            'log_time',
            'ip_address',
            'category',
            'prefix:ntext',
            'message:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
