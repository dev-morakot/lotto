<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResDocSequence */
?>
<div class="res-doc-sequence-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'code',
            'name',
            'prefix',
            'date_format',
            'running_length',
            'counter',
        ],
    ]) ?>

</div>
