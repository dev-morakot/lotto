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


/**
 * Default controller for the `resource` module
 */
class ResDocLottoController extends Controller
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
        $model = $post['lottos'];        
        $tx = ResDocLotto::getDb()->beginTransaction();
        try {

            $lo_lines = [];
            foreach($model as $line) {
                $lotto = new ResDocLotto();
                $lotto->attributes = $line;
                $lo_lines[] = $lotto;
            }
            
            if(Model::validateMultiple($lo_lines)) {
                foreach($lo_lines as $line) {
                    $line->save(false);
                }

            } else {
                $tx->rollBack();
                $response->statusCode = 422;
                $response->statusText = "Validation failed";
                return $lotto->errors;
            }

            // logger
            Yii::$app->userlog->info(
                'คีย์ข้อมูลหวย id=' . $lotto->id . ', เลข =' . $lotto->number . ' , 
                บน (จำนวนเงิน) =' . $lotto->top_amount . '  ล่าง (จำนวนเงิน) =' . $lotto->below_amount  . ' โต๊ด/กลับ (จำนวนเงิน) =' . $lotto->otd_amount,
                AppUserlog::RES_DOC_LOTTO, 'create');
           // Yii::$app->docmsg->msg($lotto, 'คีย์หวย');

            $tx->commit();
        } catch (\Exception $e){
            $tx->rollBack();
            throw $e;
        }
        
        

    }

    public function actionLoadFormAjax(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = ResDocLotto::find()
            ->asArray()
            ->one();
        return $model;
    }

    public function actionUserJson($q, $limit = 50){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $user = ResUsers::find()
            ->where(['like','firstname', $q])
            ->limit($limit)
            ->asArray()
            ->all();
        return $user;
    }


}
