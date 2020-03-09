<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\modules\resource\models\ResUsers */

use app\modules\resource\assets\ResReportUserAsset;


ResReportUserAsset::register($this);


$this->title = $model->firstname."     ". $model->lastname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'สรุปยอดซื้อตามรายชื่อลูกค้า'), 'url' => ['report-user']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id='exportableSend' ng-app="myapp" ng-controller="FormController" ng-cloak class="res-doc-report" class="res-users-view">

	<h2>หมายเลขบิล : <?= $model->id ?></h2>
    <h2>คุณ : <?= Html::encode($this->title) ?></h2>
 	<p>
 		<button type="button" ng-click="downloadPdfSend()" class="btn btn-primary">พิมพ์โพยหวย</button>
       
    </p>
     <div class="row">
        <div class="col-sm-8">
        		<input id="id" type="hidden" value="<?php echo $_GET['id']; ?>">
        		<input id="dis" type="hidden" value="<?php echo $model->discount; ?>">
                <table class="table table-bordered table-striped" style='margin-top: 15px'>
                    <thead>
                        <tr>
                            <th colspan='4' style='text-align: center'> {{ createdate  }} </th>
                        </tr>
                        <tr>
                            <th style='text-align: center'> ตัวเลข </th>
                            <th style='text-align: center'> บน </th>
                            <th style='text-align: center'> ล่าง </th>
                            <th style='text-align: center'>  โต๊ด </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="line in lotto_user | orderBy: 'number'">
                            <td align='center'>{{ line.number }}</td>
                            <td align='right' >{{ line.top_amount | number }}</td>
                            <td align='right' >{{ line.below_amount | number }}</td>
                            <td align='right' >{{ line.otd_amount | number }}</td>
                        </tr>
                    </tbody>
                    
                </table>
                <hr style="border-color: 1px solid #000000"/>
                <p style="font-size: 30px;color: blue">ราคารวม : {{ sum | number}}</p>
                <p style="font-size: 30px;color: red">ส่วนลด :  <?php echo $model->discount; ?></p>
                <p style="font-size: 30px;;color: green">เหลือ : {{ total | number}}</p>
            
        
        </div>
    </div>
 </div>
