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
use app\modules\resource\models\ResResTraints;

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

    public function actionReportThree() {
        return $this->render('report-three');
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


    public function actionGetTwoTop() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = ResDocLotto::find()
            ->where(['type' => 'สองตัวบน'])
            ->asArray()
            ->all();
        $arr = [];
        $amount = 0;
        $sum = 0;
        foreach($model as $line) {
            $sum += $line['top_amount'];
            $data = [
                'number'=> $line['number'],
                'amount' => $line['top_amount'],
            ];
            $arr[] = $data;
        }

        return ['arr' => $arr, 'sum' => $sum];
    }

    public function actionGetTwoBelow() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = ResDocLotto::find()
            ->where(['type' => 'สองตัวล่าง'])            
            ->asArray()
            ->all();
        $arr = [];
        $amount = 0;
        $sum = 0;
        foreach($model as $line) {
            $sum += $line['below_amount'];
            $data = [
                'number'=> $line['number'],
                'amount' => $line['below_amount'],
            ];
            $arr[] = $data;
        }
        return ['arr' => $arr, 'sum' => $sum];
    }

    public function actionGetThreeOtd(){ 
        error_reporting(E_ALL ^ E_NOTICE);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $result = ResDocLotto::find()
            ->where(['type' => 'สามตัวโต๊ด'])
            ->orderBy('id asc')
            ->asArray()
            ->all();
        $data = [];
        foreach($result as $line) {
            $row = [
                'number' => $line['number'],
                'amount' => $line['otd_amount']
            ];
            $data[] = $row;
        }
       /* $data = [
            ['name' => 'a', 'number' => 123, 'otd_amount' => 100],
            ['name' => 'b' , 'number' => 321, 'otd_amount' => 50],
            ['name' => 'c' , 'number' => 213, 'otd_amount' => 200],
            ['name' => 'd', 'number' =>  231, 'otd_amount' => 100],
            ['name' => 'e' , 'number' => 312, 'otd_amount' => 150],
            ['name' => 'f' , 'number' => 132, 'otd_amount' => 100],
            ['name' => 'g' , 'number' => 456, 'otd_amount' => 500],
            ['name' => 'h' , 'number' => 654, 'otd_amount' => 500],
            ['name' => 'i' , 'number' => 789, 'otd_amount' => 1000],
        ];*/
        

        $sum = [];
        $arr = [];
        $total = 0;
        foreach($data as $key => $item) {
            $item['code_temp'] = str_split($item['number']);
            sort($item['code_temp']);
            $item['number'] = implode('', $item['code_temp']);
            $number = $item['number'];
            $data[$key] = $item;
            $sum[$item['number']] += $item['amount'];
        }   

        foreach($sum as $key => $line) {
            $get = [
                'number' => $key,
                'amount' => $line
            ];
            $arr[] = $get;
            $total += $line;
        }
        
        return [
            'arr' => $arr,
            'sum' => $total
        ];
    }

    public function actionGetThreeTop(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = ResDocLotto::find()
            ->where(['type' => 'สามตัวบน'])
            ->orderBy('id asc')
            ->asArray()
            ->all();
        $arr = [];
        $amount = 0;
        $sum = 0;
        foreach($model as $line) {
            $sum += $line['top_amount'];
            $data = [
                'number' => $line['number'],
                'amount' => $line['top_amount']
            ];
            $arr[] = $data;
        }
        return ['arr'=>$arr, 'sum'=>$sum];
    }

    public function actionGetThreeBelow(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = ResDocLotto::find()
            ->where(['type' => 'สามตัวล่าง'])
            ->orderBy('id asc')
            ->asArray()
            ->all();
        $arr = [];
        $amount = 0;
        $sum = 0;
        foreach($model as $line) {
            $sum += $line['below_amount'];
            $data = [
                'number' => $line['number'],
                'amount' => $line['below_amount']
            ];
            $arr[] = $data;
        }
        return ['arr'=>$arr, 'sum'=>$sum];
    }

    /**
     * 
     * Save as cut ส่วนตัดเก็บ
     */
    public function actionSaveAsCut() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $two_top = $data['two_top'];
        $two_below = $data['two_below'];

        $current = ResCut::current();
        $tx = ResDocLotto::getDb()->beginTransaction();
        try {        

            $amount_top = 0;
            $amount_below = 0;
            $sum_top = 0;
            $sum_below = 0;
            $res_top_amount = [];
            $res_below_amount = [];
            foreach($two_top as $line) {
                if($line['amount'] > $current->two_top) {
                    $amount_top = $current->two_top;
                } else {
                    $amount_top = $line['amount'];
                }
                $data = [
                    'number' => $line['number'],
                    'amount' => $amount_top
                ];
               
                $res_top_amount[] = $data;
            }

            $res = ResRestraints::find()->where(['active' => 1])->asArray()->all();
            $valid = ArrayHelper::getColumn($res_top_amount, 'number');
        
            // เลขทืี่ไม่รับซื้อ
            foreach($res as $val) {
                $num = $val['number_limit'];
                $nums[] = $num;
            }
            foreach($valid as $key => $val) {
            
                $keydict = @$nums[$key];

                // ถ้าเลขไม่รับซื้อ ตรงกับ เลขในระบบ ไม่ต้องตัดเก็บ
                if($val !== $keydict) {
                    $TopArray[] = [
                        'number' => $res_top_amount[$key]['number'],
                        'amount' => $res_top_amount[$key]['amount']
                    ];
                    $sum_top += $res_top_amount[$key]['amount'];
                }  
            }


            // เลขที่ไม่รับซื่อ 2 ตัวล่าง จะไม่เข้าในส่วนตัดเก็บ
            foreach($two_below as $line) {
                if($line['amount'] > $current->two_below) {
                    $amount_below = $current->two_below;
                } else {
                    $amount_below = $line['amount'];
                }
               
                $data = [
                    'number' => $line['number'],
                    'amount' => $amount_below
                ];
                $res_below_amount[] = $data;
            }


            $Belowvalid = ArrayHelper::getColumn($res_below_amount, 'number');
        
            // เลขทืี่ไม่รับซื้อ
            foreach($res as $val) {
                $num = $val['number_limit'];
                $nums[] = $num;
            }
            foreach($Belowvalid as $key => $val) {
            
                $keydict = @$nums[$key];

                // ถ้าเลขไม่รับซื้อ ตรงกับ เลขในระบบ ไม่ต้องตัดเก็บ
                if($val !== $keydict) {
                    $BelowArray[] = [
                        'number' => $res_below_amount[$key]['number'],
                        'amount' => $res_below_amount[$key]['amount']
                    ];
                    $sum_below += $res_below_amount[$key]['amount'];
                }  
            }


            $tx->commit();
        } catch (\Exception $e) {
            $tx->rollBack();
            throw $e;
        }

        return [
            'res_top_amount' => $TopArray,
            'res_below_amount' => $BelowArray,
            'sum_top' => $sum_top,
            'sum_below' => $sum_below
        ];
    }

    /**
     * 
     * Save send ส่วนตัดส่ง
     */
    public function actionSaveSendLotto() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $two_top = $data['two_top'];
        $two_below = $data['two_below'];
        $current = ResCut::current();
        $tx = ResDocLotto::getDb()->beginTransaction();
        try {

            $res = ResRestraints::find()->where(['active' => 1])->asArray()->all();
            
            // เลขทืี่ไม่รับซื้อ
            foreach($res as $val) {
                $num = $val['number_limit'];
                $nums[] = $num;
            }

            $amount_top = 0;
            $amount_below = 0;
            $sum_top = 0;
            $sum_below = 0;
            $res_send_top_amount = [];
            $res_send_below_amount = [];
            $BelowArray = [];
            $TopArray = [];
            foreach($two_top as $key => $line) {
               
                if($line['amount'] > $current->two_top) {
                    $amount_top = ($line['amount'] - $current->two_top);
                    $data = [
                        'number' => $line['number'],
                        'amount' => $amount_top,                       
                    ];
                   // $sum_top += $amount_top;
                    $res_send_top_amount[] = $data;
                }
               
            }

            // เลขที่ไม่รับซื้อ 2 ตัวบน ถ้า เลขตรงกันในระบบ ให้ตัดส่งที่เจ้ามือทันที่
            $valid = ArrayHelper::getColumn($two_top, 'number');
            $avalidble = [];
            foreach($valid as $key => $line) {
                $keydict = @$nums[$key];
                if($line == $keydict) {

                    if($two_top[$key]['amount'] > $current->two_top) {
                        $temp['number'] = $two_top[$key]['number'];
                        $temp['amount'] = $two_top[$key]['amount'];
                        $sum_top += $two_top[$key]['amount'];
                        array_push($avalidble, $temp);
                    }                    
                } else {
                    if($two_top[$key]['amount'] > $current->two_top) {
                        $amount_top = ($two_top[$key]['amount'] - $current->two_top);
                        $temp['number'] = $two_top[$key]['number'];
                        $temp['amount'] = $amount_top;
                        $sum_top += $amount_top;
                        array_push($avalidble, $temp);
                    }
                }
            }
         


            foreach($two_below as $line) {
                if($line['amount'] > $current->two_below) {
                    $amount_below = ($line['amount'] - $current->two_below);
                    $data = [
                        'number' => $line['number'],
                        'amount' => $amount_below
                    ];
                  //  $sum_below += $amount_below;
                    $res_send_below_amount[] = $data;
                }
                
            }

            // เลขไม่รับซื้อ 2 ตัวล่าง ถ้าตรงกับระบบ ให้ตัดส่งไปเจ้ามือเลย
            $Belowvalid = ArrayHelper::getColumn($two_below, 'number');
            $avalidbleBelow = [];
            foreach($Belowvalid as $key => $val) {
            
                $keydict = @$nums[$key];

                // ถ้าเลขไม่รับซื้อ ตรงกับ เลขในระบบ ให้ตัดส่งทันที่
                if($val == $keydict) {

                    if($two_below[$key]['amount'] > $current->two_below) {
                        $temp['number'] = $two_below[$key]['number'];
                        $temp['amount'] = $two_below[$key]['amount'];
                        $sum_top += $two_below[$key]['amount'];
                        array_push($avalidbleBelow, $temp);
                    }                    
                } else {
                    if($two_below[$key]['amount'] > $current->two_below) {
                        $amount_below = ($two_below[$key]['amount'] - $current->two_below);
                        $temp['number'] = $two_below[$key]['number'];
                        $temp['amount'] = $amount_below;
                        $sum_below += $amount_below;
                        array_push($avalidbleBelow, $temp);
                    }
                }
                 
            }
           
            $tx->commit();
        } catch (\Exception $e) {
            $tx->rollBack();
            throw $e;
        }

        return [
            'res_send_top_amount' => $avalidble,
            'res_send_below_amount' => $avalidbleBelow,
            'sum_top' => $sum_top,
            'sum_below' => $sum_below
        ];
    }


    /**
     * 
     * ส่วนตัดเก็บ 3 ตัว
     */
    public function actionSaveCutThree(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $three_top = $data['three_top'];
        $three_below = $data['three_below'];
        $three_otd = $data['three_otd'];
        $current = ResCut::current();
        $tx = ResDocLotto::getDb()->beginTransaction();
        try {

            $amount_three_top = 0;
            $res_amount_three_top = [];
            $sum_three_top = 0;
            foreach($three_top as $line) {
                if($line['amount'] > $current->three_top) {
                    $amount_three_top = $current->three_top;
                } else {
                    $amount_three_top = $line['amount'];
                }

                $data = [
                    'number' => $line['number'],
                    'amount' => $amount_three_top
                ];
                $sum_three_top += $amount_three_top;
                $res_amount_three_top[] = $data;
            }

            $amount_three_below = 0;
            $res_amount_three_below = [];
            $sum_three_below = 0;
            foreach($three_below as $line) {
                if($line['amount'] > $current->three_below) {
                    $amount_three_below = $current->three_below;
                } else {
                    $amount_three_below = $line['amount'];
                }
                $data = [
                    'number' => $line['number'],
                    'amount' => $amount_three_below
                ];
                $sum_three_below += $amount_three_below;
                $res_amount_three_below[] = $data;
            }

            $amount_three_otd = 0;
            $res_amount_three_otd = [];
            $sum_three_otd = 0;
            foreach($three_otd as $line) {
                if($line['amount'] > $current->tree_otd) {
                    $amount_three_otd = $current->tree_otd;
                } else {
                    $amount_three_otd = $line['amount'];
                }
                $data = [
                    'number' => $line['number'],
                    'amount' => $amount_three_otd
                ];
                $sum_three_otd += $amount_three_otd;
                $res_amount_three_otd[] = $data;
            }   

            $tx->commit();
        } catch (\Exception $e) {
            $tx->rollBack();
            throw $e;
        }

        return [
            'res_amount_three_top' => $res_amount_three_top,
            'sum_three_top' => $sum_three_top,
            'res_amount_three_below' => $res_amount_three_below,
            'sum_three_below' => $sum_three_below,
            'res_amount_three_otd' => $res_amount_three_otd,
            'sum_three_otd' => $sum_three_otd
        ];
    }

    /**
     * 
     * ตัดส่วน ส่ง 3 ตัว
     */
    public function actionSaveSendThree(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->rawBody;
        $data = Json::decode($post);
        $three_top = $data['three_top'];
        $three_below = $data['three_below'];
        $three_otd = $data['three_otd'];
        $current = ResCut::current();
        $tx = ResDocLotto::getDb()->beginTransaction();
        try {

            $amount_three_top = 0;
            $res_three_top = [];
            $sum_three_top = 0;
            foreach($three_top as $line) {
                if($line['amount'] > $current->three_top) {
                    $amount_three_top = ($line['amount'] - $current->three_top);
                    $data = [
                        'number' => $line['number'],
                        'amount' => $amount_three_top
                    ];
                    $sum_three_top += $amount_three_top;
                    $res_three_top[] = $data;
                }
            }

            $amount_three_below = 0;
            $res_three_below = [];
            $sum_three_below = 0;
            foreach($three_below as $line) {
                if($line['amount'] > $current->three_below) {
                    $amount_three_below = ($line['amount'] - $current->three_below);
                    $data = [
                        'number' => $line['number'],
                        'amount' => $amount_three_below
                    ];
                    $sum_three_below += $amount_three_below;
                    $res_three_below[] = $data;
                }
            }

            $amount_three_otd = 0;
            $res_three_otd = [];
            $sum_three_otd = 0;
            foreach($three_otd as $line) {
                if($line['amount'] > $current->tree_otd) {
                    $amount_three_otd = ($line['amount'] - $current->tree_otd);
                    $data = [
                        'number' => $line['number'],
                        'amount' => $amount_three_otd
                    ];
                    $sum_three_otd += $amount_three_otd;
                    $res_three_otd[] = $data;
                }
            }

            $tx->commit();
        } catch(\Exception $e) {
            $tx->rollBack();
            throw $e;
        }

        return [
            'res_three_top' => $res_three_top,
            'sum_three_top' => $sum_three_top,
            'res_three_below' => $res_three_below,
            'sum_three_below' =>$sum_three_below,
            'res_three_otd' => $res_three_otd,
            'sum_three_otd' => $sum_three_otd
        ];
    }
}

?>