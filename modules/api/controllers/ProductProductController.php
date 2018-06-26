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
class ProductProductController extends ActiveController {

    public $modelClass = "app\modules\product\models\ProductProduct";


    /**
     * handle http://localhost:8080/api/purchase-orders/search?name=supplier
     * @return type
     */
    public function actionSearch(){
        $queryParams = Yii::$app->request->queryParams;
        Yii::info(['queryparams**',$queryParams]);
        $searchModel = new \app\modules\product\models\ProductProductSearch();
        $searchModel->attributes = $queryParams;
        $dataProvider = $searchModel->search([]);
        
        Yii::info(['name**',$searchModel->name]);
        return $dataProvider;
    }
    
    /**
     * handle http://localhost:8080/api/purchase-orders/uoms?product_id=
     * get available uom
     */
    public function actionUomIds(){
        $product_id = Yii::$app->request->get('product_id');
        $product = \app\modules\product\models\ProductProduct::findOne(['id'=>$product_id]);
        $uom = $product->uom;
        
        $query = new Query();
        $result = $query->select(['id'])
                ->from('product_uom')
                ->where(['category_id'=>$uom->category_id])->all();
        return ArrayHelper::getColumn($result, 'id');
    }
}
