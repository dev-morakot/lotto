<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Log */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'ResLottoDefault',
]) . $model->id;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['/admin/default']];
$this->params['breadcrumbs'][] = Yii::t('app', 'กำหนดราคาหวย');
?>
<div class="log-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
