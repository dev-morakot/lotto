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
use app\modules\sale\models\SaleOrderSearch;

/**
 * SaleOrderController implements the CRUD actions for PurchaseOrder model.
 */
class SaleOrderController extends ActiveController {

    public $modelClass = "app\modules\sale\models\SaleOrder";


    /**
     * handle http://localhost:8080/api/purchase-orders/search?name=supplier
     * @return type
     */
    public function actionSearch(){
        $queryParams = Yii::$app->request->queryParams;
        Yii::info(['queryparams**',$queryParams]);
        $searchModel = new SaleOrderSearch();
        $searchModel->attributes = $queryParams;
        $dataProvider = $searchModel->search([]);
        
        Yii::info(['name**',$searchModel->name]);
        return $dataProvider;
    }
}
