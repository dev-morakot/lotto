<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResDistrict */
?>
<div class="res-subdistrict-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'district.name',
            'province.name',
        ],
    ]) ?>

</div>
