<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResResTraints */

$this->title = 'สร้างเลขไม่รับซื้อ';
$this->params['breadcrumbs'][] = ['label' => 'Res Res Traints', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="res-res-traints-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>