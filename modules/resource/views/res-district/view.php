<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResDistrict */
?>
<div class="res-district-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'province_id',
        ],
    ]) ?>

</div>
