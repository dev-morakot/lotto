<?php

use unclead\multipleinput\TabularInput;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\TabularColumn;
use app\modules\resource\models\ResAddress;
use unclead\multipleinput\renderers\TableRenderer;
use kartik\select2\Select2;
use yii\web\JsExpression;

?>

<?php

    $addrs = $model->addresses;
    if(!$addrs){
        $addrs = [new ResAddress()];
    }
    
?>

<?=TabularInput::widget([
        'models' => $addrs,
        //'rendererClass' => \unclead\multipleinput\renderers\ListRenderer::className(),
        //'rendererClass' => unclead\multipleinput\renderers\TableRenderer::className(),
        'rendererClass'=> app\components\renderers\MyListRenderer::className(),
        'addButtonPosition' => unclead\multipleinput\TabularInput::POS_ROW,
        'attributeOptions' => [
//            'enableAjaxValidation' => true,
//            'enableClientValidation' => false,
//            'validateOnChange' => false,
//            'validateOnSubmit' => true,
//            'validateOnBlur' => false,
        ],
        'form' => $form,
//        'rowOptions' => function ($model, $index, $context){
//            return [
//                'id'=>'resaddress-'.$index.'-'
//            ];
//        },
        'columns' => [
            [
                'name' => 'id',
                'type' => TabularColumn::TYPE_HIDDEN_INPUT
            ],
            [
                'title'=>'รูปแบบ',
                'name'=>'type',
                'type'=> unclead\multipleinput\MultipleInputColumn::TYPE_DROPDOWN,
                'items'=>['billing'=>'Billing','shipping'=>'Shipping'],
                'options'=>['prompt'=>'-']
                
            ],
            [
                'title'=>'First Name',
                'name'=>'first_name',
                'type'=> \unclead\multipleinput\MultipleInputColumn::TYPE_TEXT_INPUT,
            ],
            [
                'title'=>'Last Name',
                'name'=>'last_name',
                'type'=> TabularColumn::TYPE_TEXT_INPUT,
            ],
            [
                'title'=>'Address 1',
                'name'=>'address1',
                'type'=> TabularColumn::TYPE_TEXT_INPUT,
                'options'=>[
                    'class'=>'bic-required-field'
                ]
            ],
            [
                'title'=>'Address2',
                'name'=>'address2',
                'type'=> TabularColumn::TYPE_TEXT_INPUT,
            ],
            [
                'title'=>'City',
                'name' => 'city',
                
            ],
            [
                'title'=>'Country',
                'name'=>'country_id',
                'type'=> kartik\select2\Select2::className(),
                'enableError' => true,    
                'options'=>function($model){
                    $countryDesc = empty($model->country_id) ? '' : app\modules\resource\models\ResCountry::findOne($model->country_id)->name;
                    return [
                        'initValueText'=> $countryDesc,
                        'options' => ['placeholder' => 'เลือกประเทศ....'],
                        'pluginEvents' => [
                            "select2:opening"=>'function(){ console.log("country-opening");}',
                            "select2:unselect" => 'function() { console.log("country-unselect"); }',
                            "select2:select" => 'function() { console.log("country-select",$(this).val()); }',
                        ],
                        'pluginOptions'=> [
                            'allowClear'=>true,
                            'ajax'=>[
                                'url'=>'country-list',
                                'dataType'=>'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ]
                        ]
                    ];
                }
            ],
            [
                'title'=>'Province',
                'name'=>'province_id',
                'type'=> kartik\select2\Select2::className(),
                'options'=>function($model){
                    Yii::info(['opt_model'=>$model->formName()]);
                    $provinceDesc = empty($model->province_id) ? '' : app\modules\resource\models\ResProvince::findOne($model->province_id)->name;
                    return [
                        'initValueText'=> $provinceDesc,
                        'options' => ['placeholder' => 'เลือกจังหวัด....'],
                        'pluginOptions'=> [
                            'allowClear'=>true,
                            'ajax'=>[
                                'url'=>'province-list',
                                'dataType'=>'json',
                                'data' => new JsExpression('function(params) { 
                                    var id_country = $(this).attr("id").replace("province_id","country_id");
                                    var params =  {
                                            q:params.term,
                                            country_id:$("#"+id_country).val()
                                        }; 
                                    console.log("province-list params",params);
                                    return params;
                                    }')
                            ]
                        ]
                    ];
                }
            ],
            [
                'title'=>'District',
                'name'=>'district_id',
                'type'=> kartik\select2\Select2::className(),
                'options'=>function($model){
                    $districtDesc = empty($model->district_id) ? '' : app\modules\resource\models\ResDistrict::findOne($model->district_id)->name;
                    return [
                        'initValueText'=> $districtDesc,
                        'options' => ['placeholder' => 'Select a District....'],
                        'pluginOptions'=> [
                            'allowClear'=>true,
                            'ajax'=>[
                                'url'=>'district-list',
                                'dataType'=>'json',
                                'data' => new JsExpression('function(params) { 
                                    var id_province = $(this).attr("id").replace("district_id","province_id");
                                    var params = {
                                        q:params.term,
                                        province_id:$("#"+id_province).val()
                                    };
                                    console.log("district-list params",params);
                                    return params;
                                    }')
                            ]
                        ]
                    ];
                }
            ],
            [
                'title'=>'Sub District',
                'name'=>'subdistrict_id',
                'type'=> kartik\select2\Select2::className(),
                'options'=>function($model){
                    $subDistrictDesc = empty($model->subdistrict_id) ? '' : app\modules\resource\models\ResSubdistrict::findOne($model->subdistrict_id)->name;
                    return [
                        'initValueText'=> $subDistrictDesc,
                        'options' => ['placeholder' => 'Select a Sub District....'],
                        'pluginOptions'=> [
                            'allowClear'=>true,
                            'ajax'=>[
                                'url'=>'sub-district-list',
                                'dataType'=>'json',
                                'data' => new JsExpression('function(params) { 
                                    var id_district = $(this).attr("id").replace("subdistrict_id","district_id");
                                    var params = {
                                        q:params.term,
                                        district_id:$("#"+id_district).val()
                                    };
                                    console.log("subdistrict-list params",params);
                                    return params;
                                    }')
                            ]
                        ]
                    ];
                }
            ],
            [
                'title' => 'รหัสไปรษณีย์',
                'name' => 'postal_code',
//                'options'=>[
//                    'class'=>'bic-required-field'
//                ]
            ],
    //        [
    //            'name'  => 'file',
    //            'title' => 'File',
    //            'type'  => \vova07\fileapi\Widget::className(),
    //            'options' => [
    //                'settings' => [
    //                    'url' => ['site/fileapi-upload']
    //                ]
    //            ]
    //        ],
            
        ],
    ]) ?>