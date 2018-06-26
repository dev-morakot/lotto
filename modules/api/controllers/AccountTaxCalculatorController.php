<?php

namespace app\modules\api\controllers;

use Yii;
use app\modules\purchase\models\PurchaseOrder;
use app\modules\purchase\models\PurchaseOrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\modules\purchase\Purchase;
use app\modules\purchase\models\PurchaseOrderLine;
use app\modules\product\models\ProductUom;
use yii\db\Query;
use app\components\DateHelper;
use yii\helpers\Html;
use app\modules\stock\models\StockLocation;
use app\modules\stock\models\StockPickingType;
use app\components\DocSequenceHelper;
use app\modules\resource\models\ResUsers;
use yii\helpers\Json;
use app\modules\admin\models\AppUserlog;
use yii\filters\AccessControl;
use app\modules\resource\models\ResReportText;
use app\modules\account\models\AccountTax;
use app\modules\purchase\models\PurchaseCategory;
use app\modules\account\Account;
use app\modules\resource\models\ResAddress;
use app\modules\purchase\models\PurchaseRequestLine;
use yii\rest\ActiveController;

/**
 * PurchaseOrderController implements the CRUD actions for PurchaseOrder model.
 */
class AccountTaxCalculatorController extends ActiveController {

    public $modelClass = "app\modules\account\models\utils\AccountTaxCalculator";


    /**
     * POST
     * handle http://localhost:8080/api/purchase-orders/search?name=supplier
     * @return type
     */
    public function actionCal(){
        $input = Yii::$app->request->rawBody;
        //Yii::info(['post',$input]);
        $obj = Json::decode($input);
        Yii::info(['json',$obj]);
        $lines = $obj['lines'];
        $total = [];
        $a = new \app\modules\account\models\utils\AccountTaxCalculator();
        $total = $a->calculateVat($lines, 
                $obj['tax_type'], 
                $obj['tax_rate'],
                isset($obj['total_discount_amount'])?$obj['total_discount_amount']:null);
        return ['lines'=>$lines,'total'=>$total];
    }
    
    public function actionCalInvoice(){
        $input = Yii::$app->request->rawBody;
        //Yii::info(['post',$input]);
        $obj = Json::decode($input);
        //Yii::info(['json',$obj]);
        $model = $obj['model'];
        $lines = $obj['lines'];
        $a = new \app\modules\account\models\utils\AccountTaxCalculator();
        $tax = AccountTax::findOne($model['tax_id']);
        
        $total = $a->calculateVat($lines, 
                $model['tax_type'], 
                ($tax)?$tax->rate:null,
                isset($model['discount_amount'])?$model['discount_amount']:null);
        
        return ['total'=>$total];
    }
    
    
    public function actionCalInvoiceLine(){
        $input = Yii::$app->request->rawBody;
        //Yii::info(['post',$input]);
        $obj = Json::decode($input);
        $line = $obj['line'];
        $cal_type = $obj['cal_type'];
        $quantity = isset($line['quantity'])?$line['quantity']:0;
        $price_unit = $line['price_unit'];
        $discount_amount = $line['discount_amount'];
        $price_subtotal = isset($line['price_subtotal'])?$line['price_subtotal']:0;
        if($quantity==0){
            return $line;
        }
        
        if($cal_type=='price_unit'){
            $price_subtotal = ($quantity * $price_unit) - $discount_amount;
        }
        if($cal_type=='price_subtotal'){
            $base_price_unit = $price_subtotal/$quantity;
            $price_unit = ceil($base_price_unit*1000)/1000; //3digit
            $base_discount_amount = ($price_unit - $base_price_unit) * $quantity;
            $discount_amount = floor($base_discount_amount*100)/100; //floor 2digit
        }
        $line['price_subtotal'] = round($price_subtotal,2);
        $line['price_unit'] = round($price_unit,3);
        $line['discount_amount'] = $discount_amount;
        
        return $line;
    }
    
    /**
     * GET
     * @param type $id
     */
    public function actionPoLineVat($id){
        $line = PurchaseOrderLine::findOne(['id'=>$id]);
        $po = $line->order;
        $poLines = $po->purchaseOrderLines;
        $lines = [];
        foreach($poLines as $line){
            $price_subtotal = $line->lineTotalAmount;
            $lines[] = [
                'id'=>$line->id,
                'price_subtotal'=>$price_subtotal,
                'quantity'=>$line->product_qty
                    ];
        }
        $a = new \app\modules\account\models\utils\AccountTaxCalculator();
        $total = $a->calculateVat($lines, 
                $po->tax_type, 
                ($po->tax)?$po->tax->rate:0,
                $po->discount_amount);
        return ArrayHelper::index($lines, 'id')[$id];
    }
    
    /**
     * Calculate for Sale Order
     * @return type
     */
    public function actionCalSo(){
        $input = Yii::$app->request->rawBody;
        //Yii::info(['post',$input]);
        $obj = Json::decode($input);
        Yii::info(['json',$obj]);
        $lines = $obj['lines'];
        $total = [];
        $a = new \app\modules\account\models\utils\AccountTaxCalculator();
        $result = $a->calculateVatOnLine($lines);
        return ['lines'=>$result['lines'],'total'=>$result['total'],'tax_info'=>$result['tax_info']];
    }
    
    /**
     * Calculate single line so
     * คำนวน หา price_unit, ส่วนลด จากยอดรวม x quantity
     * @param type $line
     */
    public function actionCalBicSoLine(){
        $requireds = ['tax_type','tax_rate','quantity','line_total_amount'];
        $input = Yii::$app->request->rawBody;
        //Yii::info(['post',$input]);
        $line = Json::decode($input);
        Yii::info(['json',$line]);
        $tax_type = isset($line['tax_type'])?$line['tax_type']:'no_tax';
        $tax_rate = $line['tax_rate'];
        $line_total_amount = $line['line_total_amount'];
        
        if($tax_type=='tax_in'){
            $line['amount_tax'] = $line_total_amount * ($tax_rate/100) / (1 + ($tax_rate/100));
            $line['amount_untaxed'] = $line_total_amount - $line['amount_tax'];
            $line['price_unit'] = $line_total_amount / $line['quantity'];
        }
        if($tax_type=='tax_ex'){
            // SO คียย์รวม tax มาแล้ว ดังนั้น ถอด vat ออก หา price_unit จริง
            $line['amount_tax'] = $line_total_amount * ($tax_rate/100) / (1 + ($tax_rate/100));
            $line['amount_untaxed'] = $line_total_amount - $line['amount_tax'];
            $line['price_unit'] = $line['amount_untaxed'] / $line['quantity'];
        }
        if($tax_type=='no_tax'){
            $line['amount_tax'] = 0;
            $line['amount_untaxed'] = $line_total_amount;
            $line['price_unit'] = $line_total_amount / $line['quantity'];
        }
        $line['amount_tax'] = round($line['amount_tax'],2);
        $line['amount_untaxed'] = round($line['amount_untaxed'],2);
        $line['price_unit'] = round($line['price_unit'],3);
        return $line;
    }
}
