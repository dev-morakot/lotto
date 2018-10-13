
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\resource\models\ResResTraintsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'เลขไม่รับซื้อ';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['/admin/default']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="res-res-traints-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('กำหนดเลขที่ไม่รับซื้อ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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
            'create_date',
            //'write_uid',
            'write_date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
