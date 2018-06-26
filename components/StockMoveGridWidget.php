<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use app\modules\resource\models\ResDocMessage;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DocStateWidget
 *
 * @author wisaruthk
 */
class StockMoveGridWidget extends Widget {
    //put your code here
    public $stockMoves;
    public $excludes = [];
    
    private $ref_model;
    private $html ="";

    public function init(){
        parent::init();
        
        
    }
    
    public function run(){
        $formatter = \Yii::$app->formatter;
        $detailDataProvider = new ActiveDataProvider([
            'query' => $this->stockMoves
        ]);
        $this->html = GridView::widget([
                'dataProvider'=>$detailDataProvider,
                'columns'=>[
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label'=>'วันที่',
                        'attribute'=>'date',
                        'format'=>'raw',
                        'value'=>function($model) use ($formatter){
                            $link = Html::a("<span class='glyphicon glyphicon-arrow-right'></span>",
                                    ['/stock/stock-move/view','id'=>$model->id],
                                    ['target'=>'_blank','data-pjax'=>0]);
                            $date_str = $formatter->asDatetime($model->date);
                            return $date_str.' '.$link;
                        }
                    ],
                    [
                        'label'=>'สินค้า',
                        'format'=>'raw',
                        'value'=>function($model){
                            $prod = $model->product;
                            if($prod){
                                return Html::tag('small', $prod->default_code.'<br>'.$prod->name);
                            }
                            return "";
                        }
                    ],
                    [
                        'label'=>'ชื่อรายการ',
                        'attribute'=>'name',
                    ],
                    [
                        'label'=>'Lot/Ctrl No',
                        'attribute'=>'lot.name',
                        'format'=>'raw',
                        'value'=>function($model){
                            $link = Html::a("<span class='glyphicon glyphicon-arrow-right'></span>",
                                    ['/stock/stock-report/stock-card','product_id'=>$model->product_id,'lot_id'=>($model->lot_id)?$model->lot_id:null],
                                    ['target'=>'_blank','data-pjax'=>0]);
                            $lot_name = @$model->lot->name;
                            return $lot_name.' '.$link;
                        }
                    ],
                    [
                        'attribute'=>'product_uom_qty',
                        'format'=>'decimal',
                        'contentOptions'=>['class'=>'text-right'],
                        'headerOptions'=>['class'=>'text-right'],
                        'label'=>'จำนวน'
                    ],
                    'productUom.name:text:หน่วย',
                    [
                        'label'=>'Qty2',
                        'attribute'=>'qty2',
                        'format'=>['decimal',3],
                        'contentOptions'=>['class'=>'text-right'],
                        'headerOptions'=>['class'=>'text-right']
                    ],
                    [
                        'label'=>'จำนวน(คลัง)',
                        'value'=>'shortStockQtyInfo'
                    ],
                    [
                        'attribute'=>'price_unit',
                        'format'=>['decimal',3],
                        'contentOptions'=>['class'=>'text-right'],
                        'headerOptions'=>['class'=>'text-right'],
                        'label'=>'มูลค่าต่อหน่วย'
                    ],
                    [
                        'attribute'=>'cost_amount',
                        'format'=>['decimal',2],
                        'contentOptions'=>['class'=>'text-right'],
                        'headerOptions'=>['class'=>'text-right'],
                        'label'=>'มูลค่าคงคลัง'
                    ],
                    'locationSrc.name:text:รหัสต้นทาง',
                    'locationDest.name:text:รหัสปลายทาง',
                    'state:text:สถานะ',
                    [
                        'label'=>'PO',
                        'attribute'=>'purchaseOrderLine.name',
                        'visible'=>!in_array('po',$this->excludes)  
                    ],
                    'notes' 
                ]
            ]);
//        $items = ResDocMessage::find()->where(['ref_id'=>$this->model->id,'ref_model'=>$this->ref_model])->all();
//        foreach ($items as $item) {
//            $line = "<div class='bs-callout bs-callout-info>";
//            $line = $line."<p>".$item->message."</p>";
//            $line = $line."<p>".$item->create_date."</p>";
//            $line = $line."<p>".$item->user->firstname."</p>";
//            $line = $line."</div>";
//            $this->html = $this->html.$line;
//        }
        return $this->html;
    }
}
