<?php

namespace app\modules\resource\controllers;
use Yii;
use yii\web\Controller;
use yii\helpers\Html;
use yii\base\Model;
use app\modules\resource\models\ResDocLotto;
use app\modules\resource\models\ResUsers;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\Url;
use app\modules\admin\models\AppUserLog;
use app\modules\resource\models\ResDocMessage;
use app\components\DateHelper;
use app\modules\resource\models\report\LottoAll;
use app\modules\resource\models\ResCut;
use app\modules\resource\models\ResCutQuery;
use app\modules\resource\models\report\CheckLotto;

/**
 * Default controller for the `resource` module
 */
class ResDocCheckLottoController extends Controller
{

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionListCheckLotto($model=""){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $queryParams = Yii::$app->request->queryParams;

        Yii::info(\yii\helpers\VarDumper::dumpAsString($queryParams));
        Yii::info("model" . \yii\helpers\VarDumper::dumpAsString($queryParams['model']));
        $models = Json::decode($model);

        $chk = new CheckLotto();
        $chk->models = $models;
        $chk->process();
        $result = $chk->process();
        return $result;
    }
}

?>