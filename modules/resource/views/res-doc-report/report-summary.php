<?php
use yii\bootstrap\Html;
use yii\helpers\Url;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/* @var $this yii\web\View */
/* @var $model app\modules\stock\models\reports\StockCardOfficerReport */


?>
<?php
    $this->registerCss(' 
        .end-location {
            border-bottom: 2px solid green;
            padding-bottom: 5px;
            color:green;
        }
        th {
            text-align:center;
        }
    ');
?>

<div class="stock-card-officer">
    <?php echo $this->render('_report_summary_search', ['model' => $model]); ?>
    
    <?php echo $this->render('_report_summary_result',['model'=>$model,'printable'=>$printable])?>
</div>