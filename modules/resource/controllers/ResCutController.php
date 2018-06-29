<?php

namespace app\modules\resource\controllers;
use Yii;
use yii\web\Controller;
use yii\helpers\Html;
use yii\base\Model;
use app\modules\resource\models\ResCut;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\Url;

/**
 * Default controller for the `resource` module
 */
class ResCutController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionFormSave(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = Yii::$app->request->rawBody;
        $post = Json::decode($data);
        $response = Yii::$app->response;
        $cut_id = ResCut::current();
        $model = $post['model'];
        $model['three_top'] = $model['three_top'];
        $model['three_below'] = $model['three_below'];
        $model['tree_otd'] = $model['tree_otd'];
        $model['two_top'] = $model['two_top'];
        $model['two_below'] = $model['two_below'];
        $tx = ResCut::getDb()->beginTransaction();
        try {
            $cut = ResCut::findOne(['id' => $cut_id->id]);
            $cut->attributes = $model;
            $cut->save(false);

            $tx->commit();
        } catch (\Exception $e){
            $tx->rollBack();
            throw $e;
        }
        
        return ['id' => $cut_id];

    }

    public function actionLoadFormAjax(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = ResCut::find()
            ->asArray()
            ->one();
        return $model;
    }
}
