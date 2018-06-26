<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResPartner */

$this->title = Yii::t('app', 'สร้างคู่ค้า');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Partners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="res-partner-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
