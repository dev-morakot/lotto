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
        foreach($model as $key => $line) {
            $sum += $line['top_amount'];
            $data = [                
                'number'=> $line['number'],
                'amount' => $line['top_amount'],
            ];
            
            $arr[] = $data;
         
        }
       // print_r($arr);

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
     * ทดสอบ เลข ไม่รับ
     * 
     */
    public function actionTest(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $current = ResCut::current();
        $model = ResDocLotto::find()
            ->where(['type' => 'สองตัวบน'])
            ->asArray()
            ->all();
        $arr = [];
        $amount = 0;
        $sum = 0;
        $amount_top= 0;
        foreach($model as $line) {
            /*if($line['top_amount'] > $current->two_top) {
                $amount_top = ($line['top_amount'] - $current->two_top);                
            } else {
                $amount_top = $line['top_amount'];
            }*/

            $data = [
                'number' => $line['number'],
                'amount' => $line['top_amount'] //$amount_top,                       
            ];
           
            $res_send_top_amount[] = $data;
        }

        $res = ResRestraints::find()->where(['active' => 1])->asArray()->all();
             // เลขทืี่ไม่รับซื้อ
            foreach($res as $id => $val) {
                $num = $val['number_limit'];
                $nums[] = $num;
        }
       
        $valid = ArrayHelper::getColumn($res_send_top_amount, 'number');
        
       
        print_r($nums);
        foreach($valid as $id => $val) {
            $def[] = $val;
        }
        print_r($def);

        $result = array_diff($def, $nums);
      
        print_r($result);
       
        $g = array_intersect($def, $nums);
        print_r($g);

        $empty = [];
        $emptys = [];
        $avalidbleTwoTop = [];
        foreach($result as $k => $v) {
           if(array_diff($def, $nums)) {
               if($res_send_top_amount[$k]['amount'] > $current->two_top) {
                    $amount_top = ($res_send_top_amount[$k]['amount'] - $current->two_top);
                    $data = [
                        'number' => $res_send_top_amount[$k]['number'],
                        'amount' => $amount_top,                       
                    ];
                    $emptys[] = $data;
               }
           }
        }
        
        print_r($emptys);
       foreach($g as $k => $v) {
            if(array_intersect($def,$nums)) {
                $datas = [
                    'number' => $res_send_top_amount[$k]['number'],
                    'amount' => $res_send_top_amount[$k]['amount'],                       
                ];
                $empty[] = $datas;
            }
       }
        
       print_r($empty);
       $mer = array_merge($emptys, $empty);
       print_r($mer);
      
       
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
        $nums = [];
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
            $res_top_amount = [];
            $res_below_amount = [];
           /* foreach($two_top as $line) {

                // ถ้าราคาหวย มากกว่า ราคาที่กำหนดราคาหวย ให้ตัดออกไป
                if($line['amount'] > $current->two_top) {
                    $amount_top = $current->two_top;
                } else {
                    //  2 ตัวบน ให้เก็บราคาหวยเท่าเดิม
                    $amount_top = $line['amount'];
                }
                $data = [
                    'number' => $line['number'],
                    'amount' => $amount_top
                ];
               
                $res_top_amount[] = $data;
               
            }*/

             // ถ้าหวย2ตัวเหมือนกัน แต่ ซื้อเกินราคาที่กำหนด ให้ ตัดออกไปที่ ตัดส่ง (เจ้ามือ)
                // เช้่น 2 ตัวบน รับแค่ 200 บาท แต่ผู้ซื้อ เลข 88 หลายคน แต่รวมราคาแล้วเกิด กว่า 200
                // ตัวอย่าง ก. 88 = 50  ข. 88 = 50  ค. 88 = 200
                // ดังนั้น 88 = 300 บาท ซึงเกิดราคาที่กำหนดไว้ ให้ ตัดออกไป จะได้เท่ากับ
                // 88 = 200 บาท  อีก 100 บาท จะถูกส่งไปที่ ตัดส่ง

            
            $groups = [];
            $key = 0;
            foreach($two_top as $k => $item) {
                $key = $item['number'];
                if($item['amount'] > $current->two_top) {
                    $amount_top = $current->two_top;
                } else {
                    //  2 ตัวบน ให้เก็บราคาหวยเท่าเดิม
                    $amount_top = $item['amount'];
                }
                if(!array_key_exists($key, $groups)) {
                    $groups[$key] = [
                        'number' => $item['number'],
                        'amount' => $amount_top
                    ];
                } else {               
                    $groups[$key]['amount'] = $groups[$key]['amount'] + $amount_top;
                }
                $key++;
            }
            //sort($groups);

            foreach($groups as $group) {
                // ถ้าราคาหวย มากกว่า ราคาที่กำหนดราคาหวย ให้ตัดออกไป
                if($group['amount'] > $current->two_top) {
                    $amount_top = $current->two_top;
                } else {
                    //  2 ตัวบน ให้เก็บราคาหวยเท่าเดิม
                    $amount_top = $group['amount'];
                }
                $data = [
                    'number' => $group['number'],
                    'amount' => $amount_top
                ];
               
                $res_top_amount[] = $data;
            }
            

           
            $valid = ArrayHelper::getColumn($res_top_amount, 'number');
            
            foreach($valid as $id => $val) {
                $def[] = $val;
            }
            $twotop_nums = $nums;
            $top_result = array_diff($def, $twotop_nums);
            foreach($top_result as $k => $v) {
    
                $TopArray[] = [
                    'number' => $res_top_amount[$k]['number'],
                    'amount' => $res_top_amount[$k]['amount']
                ];
                $sum_top += $res_top_amount[$k]['amount'];
            }
            
    


            // เลขที่ไม่รับซื่อ 2 ตัวล่าง จะไม่เข้าในส่วนตัดเก็บ
            /*foreach($two_below as $line) {
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
            }*/

            $groups_below = [];
            $key_below = 0;
            foreach($two_below as $k => $item) {
                $key_below = $item['number'];
                if($item['amount'] > $current->two_below) {
                    $amount_below = $current->two_below;
                } else {
                    //  2 ตัวบน ให้เก็บราคาหวยเท่าเดิม
                    $amount_below = $item['amount'];
                }
                if(!array_key_exists($key_below, $groups_below)) {
                    $groups_below[$key_below] = [
                        'number' => $item['number'],
                        'amount' => $amount_below
                    ];
                } else {               
                    $groups_below[$key_below]['amount'] = $groups_below[$key_below]['amount'] + $amount_below;
                }
                $key_below++;
            }

            foreach($groups_below as $line) {
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
            foreach($Belowvalid as $id => $val) {
                $def_below[] = $val;
            }
            $twobelow_nums = $nums;
            $below_result = array_diff($def_below, $twobelow_nums);
            foreach($below_result as $k => $v) {
    
                $BelowArray[] = [
                    'number' => $res_below_amount[$k]['number'],
                    'amount' => $res_below_amount[$k]['amount']
                ];
                $sum_below += $res_below_amount[$k]['amount'];
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
            'sum_below' => $sum_below,
            
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
        $nums  = [];
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
            /*foreach($two_top as $key => $line) {
               
                /*if($line['amount'] > $current->two_top) {
                    $amount_top = ($line['amount'] - $current->two_top);
                    $data = [
                        'number' => $line['number'],
                        'amount' => $amount_top,                       
                    ];
                   // $sum_top += $amount_top;
                    $res_send_top_amount[] = $data;
                }*/
                /*$data = [
                    'number' => $line['number'],
                    'amount' => $line['amount']
                ];
                $res_send_top_amount[] = $data;
               
            }*/


            $groups_send_top_amount = [];
            $key = 0;
            foreach($two_top as $k => $item) {
                $key = $item['number'];
              
                if(!array_key_exists($key, $res_send_top_amount)) {
                    $res_send_top_amount[$key] = [
                        'number' => $item['number'],
                        'amount' => $item['amount']
                    ];
                } else {               
                    $res_send_top_amount[$key]['amount'] = $res_send_top_amount[$key]['amount'] + $item['amount'];
                }
                $key++;
            }
            sort($res_send_top_amount);



            // เลขที่ไม่รับซื้อ 2 ตัวบน ถ้า เลขตรงกันในระบบ ให้ตัดส่งที่เจ้ามือทันที่
            $validTop = ArrayHelper::getColumn($res_send_top_amount, 'number');
            foreach($validTop as $id => $val) {
               $defTop[] = $val;
            }
            $twotop_nums = $nums;
            // หาค่าที่ไม่ซ้ำ
            $result_top = array_diff($defTop, $twotop_nums);

            // หาค่าที่เหมือนกัน
            $intersectTop = array_intersect($defTop, $twotop_nums);

            // สร้าง array
            $diff_empty = [];
            $intersect_empty = [];

            foreach($result_top as $k => $v) {
                if(array_diff($defTop, $twotop_nums)) {
                    if($res_send_top_amount[$k]['amount'] > $current->two_top) {
                        $amount_top = ($res_send_top_amount[$k]['amount'] - $current->two_top);
                        $data_diff = [
                            'number' => $res_send_top_amount[$k]['number'],
                            'amount' => $amount_top
                        ];
                        $sum_top += $amount_top;
                        $diff_empty[] = $data_diff;
                    }
                }
            }

            foreach($intersectTop as $k => $v) {
                if(array_intersect($defTop, $twotop_nums)) {
                    $data_intersect = [
                        'number' => $res_send_top_amount[$k]['number'],
                        'amount' => $res_send_top_amount[$k]['amount']
                    ];
                    $sum_top += $res_send_top_amount[$k]['amount'];
                    $intersect_empty[] = $data_intersect;
                }
            }

            $merge_top = array_merge($diff_empty, $intersect_empty);



            // 2 ตัวล่าง
            /*foreach($two_below as $line) {
               
                $rows = [
                    'number' => $line['number'],
                     'amount' => $line['amount']
                ];
                $res_send_below_amount[] = $rows;
            }*/

            $groups_send_below_amount = [];
            $key_send_below_amount = 0;
            foreach($two_below as $k => $item) {
                $key_send_below_amount = $item['number'];
              
                if(!array_key_exists($key_send_below_amount, $groups_send_below_amount)) {
                    $groups_send_below_amount[$key_send_below_amount] = [
                        'number' => $item['number'],
                        'amount' => $item['amount']
                    ];
                } else {               
                    $groups_send_below_amount[$key_send_below_amount]['amount'] = $groups_send_below_amount[$key_send_below_amount]['amount'] + $item['amount'];
                }
                $key_send_below_amount++;
            }
            sort($groups_send_below_amount);


            $helpers = ArrayHelper::getColumn($groups_send_below_amount, 'number');
            foreach($helpers as $id => $val) {
                $defaultBelow[] = $val;
            }
            $twobelow_nums = $nums;
            // หาค่าที่ไม่ซ้ำกัน
            $result_below = array_diff($defaultBelow, $twobelow_nums);

            // หาค่าที่เหมือนกัน 
            $intersectBelow = array_intersect($defaultBelow, $twobelow_nums);

            // สร้าง array
            $diff_empty_below = [];
            $intersect_empty_below = [];

            foreach($result_below as $k => $v) {
                if(array_diff($defaultBelow, $twobelow_nums)) {
                    if($groups_send_below_amount[$k]['amount'] > $current->two_below) {
                        $amount_below = ($groups_send_below_amount[$k]['amount'] - $current->two_below);
                        $data_below = [
                            'number' => $groups_send_below_amount[$k]['number'],
                            'amount' => $amount_below
                        ];
                        $sum_below += $amount_below;
                        $diff_empty_below[] = $data_below;
                    }
                }
            }

            foreach($intersectBelow as $k => $v) {
                if(array_intersect($defaultBelow, $twobelow_nums)) {
                    $below_intersect = [
                        'number' => $groups_send_below_amount[$k]['number'],
                        'amount' => $groups_send_below_amount[$k]['amount']
                    ];
                    $sum_below += $groups_send_below_amount[$k]['amount'];
                    $intersect_empty_below[] = $below_intersect;
                }
            }

            $merge_below = array_merge($diff_empty_below, $intersect_empty_below);

           
            $tx->commit();
        } catch (\Exception $e) {
            $tx->rollBack();
            throw $e;
        }

        return [
            'res_send_top_amount' => $merge_top,
            'res_send_below_amount' => $merge_below,
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
            $groups_three_top = [];
            /*foreach($three_top as $line) {
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
            }*/
            $key = 0;
            foreach($three_top as $k => $item) {
                $key = $item['number'];
                if($item['amount'] > $current->three_top) {
                    $amount_three_top = $current->three_top;
                } else {
                    $amount_three_top = $item['amount'];
                }
              
                if(!array_key_exists($key, $groups_three_top)) {
                    $groups_three_top[$key] = [
                        'number' => $item['number'],
                        'amount' => $amount_three_top
                    ];
                    
                } else {               
                    $groups_three_top[$key]['amount'] = $groups_three_top[$key]['amount'] + $amount_three_top;
                }
                $key++;
            }

            foreach($groups_three_top as $group) {
                // ถ้าราคาหวย มากกว่า ราคาที่กำหนดราคาหวย ให้ตัดออกไป
                if($group['amount'] > $current->three_top) {
                    $amount_three_top = $current->three_top;
                } else {
                    $amount_three_top = $group['amount'];
                }
                $data = [
                    'number' => $group['number'],
                    'amount' => $amount_three_top
                ];
                $sum_three_top += $amount_three_top;
                $res_amount_three_top[] = $data;
            }




            $amount_three_below = 0;
            $res_amount_three_below = [];
            $sum_three_below = 0;
            $groups_three_below = [];
            /*foreach($three_below as $line) {
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
            }*/
            $key_below = 0;
            foreach($three_below as $k => $item) {
                $key_below = $item['number'];
                if($item['amount'] > $current->three_below) {
                    $amount_three_below = $current->three_below;
                } else {
                    $amount_three_below = $item['amount'];
                }
              
                if(!array_key_exists($key_below, $groups_three_below)) {
                    $groups_three_below[$key_below] = [
                        'number' => $item['number'],
                        'amount' => $amount_three_below
                    ];
                   
                } else {               
                    $groups_three_below[$key_below]['amount'] = $groups_three_below[$key_below]['amount'] + $amount_three_below;
                }
                $key_below++;
            }

            foreach($groups_three_below as $group) {
                // ถ้าราคาหวย มากกว่า ราคาที่กำหนดราคาหวย ให้ตัดออกไป
                if($group['amount'] > $current->three_below) {
                    $amount_three_below = $current->three_below;
                } else {
                    $amount_three_below = $group['amount'];
                }
                $data = [
                    'number' => $group['number'],
                    'amount' => $amount_three_below
                ];
                $sum_three_below += $amount_three_below;
                $res_amount_three_below[] = $data;
            }





            $amount_three_otd = 0;
            $res_amount_three_otd = [];
            $sum_three_otd = 0;
            $groups_three_otd = [];
            /*foreach($three_otd as $line) {
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
            }*/
            
            $key_otd = 0;
            foreach($three_otd as $k => $item) {
                $key_otd = $item['number'];
                if($item['amount'] > $current->tree_otd) {
                    $amount_three_otd = $current->tree_otd;
                } else {
                    $amount_three_otd = $item['amount'];
                }
              
                if(!array_key_exists($key_otd, $groups_three_otd)) {
                    $groups_three_otd[$key_otd] = [
                        'number' => $item['number'],
                        'amount' => $amount_three_otd
                    ];
                    $sum_three_otd += $amount_three_otd;
                } else {               
                    $groups_three_otd[$key_otd]['amount'] = $groups_three_otd[$key_otd]['amount'] + $amount_three_otd;
                }
                $key_otd++;
            }


            foreach($groups_three_otd as $group) {
                // ถ้าราคาหวย มากกว่า ราคาที่กำหนดราคาหวย ให้ตัดออกไป
                if($group['amount'] > $current->tree_otd) {
                    $amount_three_otd = $current->tree_otd;
                } else {
                    $amount_three_otd = $group['amount'];
                }
                $data = [
                    'number' => $group['number'],
                    'amount' => $amount_three_otd
                ];
               
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
            $groups_three_top = [];
            /*foreach($three_top as $line) {
                if($line['amount'] > $current->three_top) {
                    $amount_three_top = ($line['amount'] - $current->three_top);
                    $data = [
                        'number' => $line['number'],
                        'amount' => $amount_three_top
                    ];
                    $sum_three_top += $amount_three_top;
                    $res_three_top[] = $data;
                }
            }*/

            $key = 0;
            foreach($three_top as $k => $item) {
                $key = $item['number'];
                
                if(!array_key_exists($key, $groups_three_top)) {
                   // if($item['amount'] > $current->three_top) {
                    //    $amount_three_top = ($item['amount'] - $current->three_top);
                        $groups_three_top[$key] = [
                            'number' => $item['number'],
                            'amount' => $item['amount']
                        ];
                       
                   // }
                    
                } else {               
                    $groups_three_top[$key]['amount'] = $groups_three_top[$key]['amount'] + $item['amount'];
                }
                $key++;
            }

            foreach($groups_three_top as $group) {
                if($group['amount'] > $current->three_top) {
                    $amount_three_top = ($group['amount'] - $current->three_top);
                    $data = [
                        'number' => $group['number'],
                        'amount' => $amount_three_top
                    ];
                    $sum_three_top += $amount_three_top;
                    $res_three_top[] = $data;
                }
            }




            $amount_three_below = 0;
            $res_three_below = [];
            $sum_three_below = 0;
            $groups_three_below = [];
            /*foreach($three_below as $line) {
                if($line['amount'] > $current->three_below) {
                    $amount_three_below = ($line['amount'] - $current->three_below);
                    $data = [
                        'number' => $line['number'],
                        'amount' => $amount_three_below
                    ];
                    $sum_three_below += $amount_three_below;
                    $res_three_below[] = $data;
                }
            }*/

            $key_below = 0;
            foreach($three_below as $k => $item) {
                $key_below = $item['number'];
                
                if(!array_key_exists($key_below, $groups_three_below)) {
                    //if($item['amount'] > $current->three_below) {
                      //  $amount_three_below = ($item['amount'] - $current->three_below);
                        $groups_three_below[$key_below] = [
                            'number' => $item['number'],
                            'amount' => $item['amount']
                        ];
                       
                    //}
                    
                } else {               
                    $groups_three_below[$key_below]['amount'] = $groups_three_below[$key_below]['amount'] + $item['amount'];
                }
                $key_below++;
            }

            foreach($groups_three_below as $group) {
                if($group['amount'] > $current->three_below) {
                    $amount_three_below = ($group['amount'] - $current->three_below);
                    $data = [
                        'number' => $group['number'],
                        'amount' => $amount_three_below
                    ];
                    $sum_three_below += $amount_three_below;
                    $res_three_below[] = $data;
                }
            }





            $amount_three_otd = 0;
            $res_three_otd = [];
            $sum_three_otd = 0;
            $groups_three_otd = [];
            /*foreach($three_otd as $line) {
                if($line['amount'] > $current->tree_otd) {
                    $amount_three_otd = ($line['amount'] - $current->tree_otd);
                    $data = [
                        'number' => $line['number'],
                        'amount' => $amount_three_otd
                    ];
                    $sum_three_otd += $amount_three_otd;
                    $res_three_otd[] = $data;
                }
            }*/

            $key_otd = 0;
            foreach($three_otd as $k => $item) {
                $key_otd = $item['number'];
                
                if(!array_key_exists($key_otd, $groups_three_otd)) {
                  //  if($item['amount'] > $current->tree_otd) {
                  //     $amount_three_otd = ($item['amount'] - $current->tree_otd);
                        $groups_three_otd[$key_otd] = [
                            'number' => $item['number'],
                            'amount' => $item['amount']
                        ];
                        
                  //  }
                    
                } else {               
                    $groups_three_otd[$key_otd]['amount'] = $groups_three_otd[$key_otd]['amount'] + $amount_three_otd;
                }
                $key_otd++;
            }

            foreach($groups_three_otd as $group) {
                if($group['amount'] > $current->tree_otd) {
                    $amount_three_otd = ($group['amount'] - $current->tree_otd);
                    $data = [
                        'number' => $group['number'],
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