<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use Yii;
use app\modules\stock\models\StockLocation;
use app\modules\product\models\ProductProduct;

/**
 * Description of QcInspectionHelper
 *
 * @author wisaruthk
 */
class QcInspectionHelper {
    public static function isRequiredQc($product_id,$location_dest_id){
        $qc_process_type = @Yii::$app->params['qc.process.type'];
        if($qc_process_type == 'on_location'){
            $location = StockLocation::findOne($location_dest_id);
            if($location->qc_inspection=='yes'){
                return true;
            } else {
                return false;
            }
        } else {
            // Default on_product
            $product = ProductProduct::findOne($product_id);
            return $product->required_inspection;
        }
    }
}
