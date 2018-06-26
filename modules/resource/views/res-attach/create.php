<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResAttach */

$this->title = 'Create Res Attach';
$this->params['breadcrumbs'][] = ['label' => 'Res Attaches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="res-attach-create">

    <h1><?= 'สร้างไฟล์แนบสำหรับ '.$rModel['name'] ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
