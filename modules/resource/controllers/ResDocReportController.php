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

/**
 * Default controller for the `resource` module
 */
class ResDocReportController extends Controller
{
   
    public function actionReportAll(){

        $three_top_amount = (new Query())
            ->select('sum(top_amount)')
            ->from('res_doc_lotto')
            ->where(['type' => 'สามตัวบน'])
            ->scalar();

        $three_below_amount = (new Query())
            ->select('sum(below_amount)')
            ->from('res_doc_lotto')
            ->where(['type' => 'สามตัวล่าง'])
            ->scalar();
        $three_otd_amount = (new Query())
            ->select('sum(otd_amount)')
            ->from('res_doc_lotto')
            ->where(['type' => 'สามตัวโต๊ด'])
            ->scalar();

        $two_top_amount = (new Query())
            ->select('sum(top_amount)')
            ->from('res_doc_lotto')
            ->where(['type' => 'สองตัวบน'])
            ->scalar();

        $two_below_amount = (new Query())
            ->select('sum(below_amount)')
            ->from('res_doc_lotto')
            ->where(['type' => 'สองตัวล่าง'])
            ->scalar();

        $temp['three_top_amount'] = $three_top_amount;
        $temp['three_below_amount'] = $three_below_amount;
        $temp['three_otd_amount'] = $three_otd_amount;
        $temp['two_top_amount'] = $two_top_amount;
        $temp['two_below_amount'] = $two_below_amount;

        return $this->render('report-all',[
            'temp' => $temp
        ]);
    }

    public function actionDelete(){
        $model = ResDocLotto::deleteAll();
        return $this->redirect(['report-all']);
    }

    public function actionReportTwo(){
        return $this->render('report-two');
    }

    public function actionAll(){
        $model = new LottoAll();
        return $this->render('all', [
            'model' => $model
        ]);
    }

    public function actionReportSummary($filter=""){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $queryParams = Yii::$app->request->queryParams;
        Yii::info(\yii\helpers\VarDumper::dumpAsString($queryParams));
        Yii::info("filter" . \yii\helpers\VarDumper::dumpAsString($queryParams['filter']));
        $filters = Json::decode($filter);
       
        
        $query = (new Query())->select('id , number, type, top_amount,below_amount,otd_amount')
        ->from('res_doc_lotto');

        if(!empty(@$filters['type'])) {
            $input = @$filters['type'];
            $query->where(['like','type',$input]);
        }
        $result_query = $query->all();

        // ค้นหาชื่อประเภทหวย
        $array = [];
        $sum = 0;
        $amount = '';
        foreach($result_query as $key => $r) {
                 
                switch (@$r['type']) {
                    case 'สามตัวบน':
                        $amount = $r['top_amount'];
                        break;
                    case 'สามตัวล่าง':
                        $amount = $r['below_amount'];
                        break;
                    case 'สามตัวโต๊ด':
                        $amount = $r['otd_amount'];
                        break;
                    case 'สองตัวบน':
                        $amount = $r['top_amount'];
                        break;
                    case 'สองตัวล่าง':
                        $amount = $r['below_amount'];
                        break;
                }
                $sum += $amount;
                $key_array = [
                    'id' => @$r['id'],
                    'number' => $r['number'],
                    'type' => $r['type'],
                    'amount' => $amount
                ];
        $array[] = $key_array;
        }
        return ['result' => $array, 'sum' => $sum];
    }

    public function actionLottoDelete() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = Yii::$app->request->rawBody;
        $post = Json::decode($data);
        $id = $post['id'];
        $model = ResDocLotto::findOne(['id' => $id])->delete();
        
    }
}

?>