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


    public function actionTest(){
       
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
     
        $a = 312;
        $_a = str_split($a);
        $output = $this->permutation($_a);
        
        
        print_r($output);
        $m_sql = $this->selectQuery($output);
        foreach($m_sql as $sql) {
           if($sql['type'] == 'สามตัวโต๊ด') {
            print_r($sql);
           }
            

        }

        
    }

    public function selectQuery($output) {
        $query = (new Query())->select('
            a.number, a.top_amount, a.below_amount, a.type, a.otd_amount, b.firstname, b.lastname,b.discount
        ')->from('res_doc_lotto as a')
        ->leftJoin('res_users as b','b.id = a.user_id');
        $query->where(['<>','a.number', 'is null']);
        if($output) {
            $query->andWhere(['in','a.number', $output]);
        }
        $res = $query->all();
        return $res;

    }

    private function permutation($_a, $buffer='', $delimiter='') {
        $output = array();
    
        $num = count($_a);
        if ($num > 1) {
            foreach ($_a as $key=>$val) {
                $temp = $_a;
                unset($temp[$key]);
                sort($temp);
    
                $return = $this->permutation($temp, trim($buffer.$delimiter.$val, $delimiter), $delimiter);
    
                if(is_array($return)) {
                    $output = array_merge($output, $return);
                    $output = array_unique($output);
                }
                else {
                    $output[] = $return;
                }
    
            }
            return $output;
        }
        else {
            return $buffer.$delimiter.$_a[0];
        }
    }


    
    
}

?>