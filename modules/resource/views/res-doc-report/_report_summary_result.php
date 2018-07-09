<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\modules\resource\models\ResDocLotto;

$result = $model->result;
if(!$result){
    return;
}

if($printable){
    
}
Yii::beginProfile('render');
?>

<dvi class="table-responsive">
    
        <table class="table table-bordered table-striped" style='margin-top:10px'>
            <thead>
                <tr>
                    <th style='text-align:center'> ประเภทหวย</th>
                    <th style='text-align:center'>ตัวเลข</th>
                    <th style='text-align:center'>จำนวนเงิน</th>
                    <th style='text-align:center'>ลบ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($result['arr'] as $line): ?>
                <tr>
                    <td><?= $line['type']; ?></td>
                    <td align='right'><?= $line['number']; ?></td>
                    <td align='right'><?= number_format($line['amount']); ?></td>
                    <td align='center'>
                        <?php
                        echo Html::a(Yii::t('app', 'ลบ'),['lotto-delete', 'id' => $line['id']], [
                            'class' => 'btn btn-danger btn-sm',
                            'data-pjax' => 0,
                            'data' => [
                                'confirm' => 'ยืนยันการลบข้อมูล',
                                'method' => 'post'
                            ],
                        ]);
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan='2'>รวม</td>
                    <td align='right'><?= number_format($model->result['sum']); ?> </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

<?php

Yii::endProfile('render');

?>
