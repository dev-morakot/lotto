<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResUsers */

$this->title = Yii::t('app', 'ลงทะเบียนผู้ใช้');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'ผู้ซื้อ/คนเดินโพยหวย'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="res-users-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>โปรดกรอกข้อมูล:</p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
