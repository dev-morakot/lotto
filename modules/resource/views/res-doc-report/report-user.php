<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\web\View;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\modules\resource\assets\ResDocReportAllAsset;
use app\modules\resource\models\ResDocLotto;

ResDocReportAllAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResUsers */

$this->title = Yii::t('app', 'สรุปยอดซื้อตามรายชื่อลูกค้า');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['/admin/default']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
    display: none !important;
}');


?>

<div class="res-users-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            [
                    'attribute'=>'firstname',
                    'format'=>'raw',
                    'value' => function($data)
                    {
                        return
                        Html::a($data->firstname, ['view','id'=> $data->id], ['class'=>'no-pjax']);
                    }
            ],
            
            'lastname',
            'discount:text:ส่วนลด %',
            //'groupDisplay:text:สังกัด',
            //'active:boolean',
            // 'company_id',
            // 'default_section_id',
            // 'create_uid',
            // 'create_date',
            // 'write_uid',
            // 'write_date',

          //  ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>