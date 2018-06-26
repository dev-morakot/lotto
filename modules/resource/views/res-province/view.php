<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResProvince */
?>
<div class="res-province-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'country_id',
        ],
    ]) ?>

</div>
