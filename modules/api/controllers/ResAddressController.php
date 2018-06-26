<?php

namespace app\modules\api\controllers;

use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use app\components\DateHelper;
use yii\helpers\Html;
use app\components\DocSequenceHelper;
use app\modules\resource\models\ResUsers;
use yii\helpers\Json;
use yii\filters\AccessControl;
use yii\rest\ActiveController;

/**
 * PurchaseOrderController implements the CRUD actions for PurchaseOrder model.
 */
class ResAddressController extends ActiveController {

    public $modelClass = "app\modules\\resource\models\ResAddress";


    /**
     * handle http://localhost:8080/api/purchase-orders/search?name=supplier
     * @return type
     */
    public function actionSearch(){
        $queryParams = Yii::$app->request->queryParams;
        Yii::info(['queryparams**',$queryParams]);
        $searchModel = new \app\modules\resource\models\ResAddressSearch();
        $searchModel->attributes = $queryParams;
        $dataProvider = $searchModel->search([]);
        
        Yii::info(['name**',$searchModel->name]);
        return $dataProvider;
    }
}
