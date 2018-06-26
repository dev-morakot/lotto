<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('สร้างผู้ใช้', ['signup'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'id',
                'options'=>['width'=>'30px']
            ],
            [
                'attribute'=>'username',
                'format'=>'raw',
                'value'=>function($model){
                    return Html::a($model->username,Url::to(['/user/view','id'=>$model->id]));
                }
            ],
            // 'username:text:Username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            // 'verifymail_token',
            'email:text:Email',
            'code',
            'firstname',
            'lastname',
            'active:boolean',
            // 'company_id',
            // 'default_section_id',
            'login_date',
            // 'create_uid',
            // 'create_date',
            // 'write_uid',
            'write_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
