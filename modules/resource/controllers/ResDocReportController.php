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
class ResDocReportController extends Controller
{
   
    public function actionReportAll(){

        $result = [];
        $two_top_amount = null;
        $two_below_amount = null;
        $lotto = ResDocLotto::find() 
            ->groupBy(['type'])           
            ->all();
        foreach($lotto as $line) {
            if($line->type == 'สองตัวบน'){
                $two_top_amount += $line->top_amount;
               $temp['two_top_amount'] = $two_top_amount;
            }
            if($line->type == 'สองตัวล่าง'){
                $two_below_amount += $line->below_amount;
                $temp['two_below_amount'] = $two_below_amount;
            }
            array_push($result, $temp);
            
        }
        return $this->render('report-all',[
            'result' => $result
        ]);
    }
}

?>