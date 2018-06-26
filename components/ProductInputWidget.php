<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * Product Input Widget
 * Under Construction
 * TODO:: Under Construction
 * @author wisaruthk
 */
class ProductInputWidget extends \yii\base\Widget {
    public $id;
    public $model;
    public $attribute;
    public $htmlOptions = [];
    public $name;
    public $url;
    
    protected function hasModel()
    {
        return $this->model instanceof Model
                && $this->attribute !== null;
    }
    
    public function run()
    {
        $this->url = Url::to(['/product/product-product/product-list']);
        
        if (!$this->hasModel()){
            throw new Exception('Model must be set');
        }
        
        $product_id = \yii\helpers\BaseHtml::getAttributeValue($this->model, $this->attribute);
        $prod = \app\modules\product\models\ProductProduct::findOne($product_id);
        $prodDesc = ($prod)?$prod->default_code.' '.$prod->name:'Product Not Found';
        $s = \kartik\select2\Select2::widget([
            'model'=>$this->model,
            'attribute'=>$this->attribute,
            'initValueText'=>$prodDesc,
            'size'=>"sm",
            'options'=>[
                'id'=>$this->id,
                'name'=>$this->name,
                'placeholder'=>'Select a product...'
            ],
            'pluginOptions'=>[
                'allowClear'=>true,
                'ajax'=>[
                    'url'=>$this->url,
                    'dataType'=>'json',
                    'data'=>new JsExpression('function(params){return {q:params.term}; }')
                ],
                'escapeMarkup'=>new JsExpression('function (markup){ return markup;}'),
                    'templateResult'=>new JsExpression('function(product){  
                        var code = (product.default_code)?product.default_code:"";
                        return    code+"<br>"+product.text+"<br>"+product.text_en;}'),
                    'templateSelection'=>new JsExpression('function(product){ 
                        var code = (product.default_code)?product.default_code:"";
                         return   code+" "+product.text;}')
            ]
        ]);
        
        
    
    return $s;
    }
}
