<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResPartner */
?>
<?php
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Partners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="res-partner-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
