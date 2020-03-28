<?php

namespace app\modules\jan\controllers;

use yii\web\Controller;
use app\components\DocSequenceHelper;
use Yii;
use yii\helpers\Html;
use yii\base\Model;
use app\modules\jan\models\ResJanLotto;
use app\modules\jan\models\ResJanCustomer;
use app\modules\jan\models\ResJanLottoLimit;
use app\modules\resource\models\ResDocSequence;
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
use app\modules\resource\models\ResResTraints;
use kartik\mpdf\Pdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
//use mPDF;
/**
 * Default controller for the `jan` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

     public function actionFormSearch()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = Yii::$app->request->rawBody;
        $post = Json::decode($data);
        $response = Yii::$app->response;
        $search = $post['search'];

        $res = ResJanLotto::find()->where(['number' => $search])->asArray()->all();
            $arr = [];
            $sum = 0;
            foreach($res as $id => $val) {
                $sum += ($val['line_amount_total']);
                $data = [
                    "number"=> $val['number'],
                    "top_amount"=>$val['top_amount'],
                    "below_amount"=>$val['below_amount'],
                    "otd_amount"=>$val['otd_amount'],
                    "line_amount_total"=>$val['line_amount_total']
                ];
                $arr[] = $data;
            }
            return [
                'res' => $arr,
                "sum"=>$sum
            
            ];       
    }

     public function actionReportAllCustomerLine()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = Yii::$app->request->rawBody;
        $post = Json::decode($data);
        $response = Yii::$app->response;
        $id = $post['lineId'];

        $res = ResJanLotto::find()->where(['user_id' => $id])->asArray()->all();
            $arr = [];
            $sum = 0;
            foreach($res as $id => $val) {
                $sum += ($val['line_amount_total']);
                $data = [
                    "number"=> $val['number'],
                    "top_amount"=>$val['top_amount'],
                    "below_amount"=>$val['below_amount'],
                    "otd_amount"=>$val['otd_amount'],
                    "line_amount_total"=>$val['line_amount_total']
                ];
                $arr[] = $data;
            }
            return [
                'res' => $arr,
                "sum"=>$sum
            
            ];       
    }

     public function actionReportAllCustomerLineLimit()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = Yii::$app->request->rawBody;
        $post = Json::decode($data);
        $response = Yii::$app->response;
        $id = $post['lineId'];

        $res = ResJanLottoLimit::find()->where(['user_id' => $id])->asArray()->all();
            $arr = [];
            $sum = 0;
            foreach($res as $id => $val) {
                $sum += ($val['line_amount_total']);
                $data = [
                    "number"=> $val['number'],
                    "top_amount"=>$val['top_amount'],
                    "below_amount"=>$val['below_amount'],
                    "otd_amount"=>$val['otd_amount'],
                    "line_amount_total"=>$val['line_amount_total']
                ];
                $arr[] = $data;
            }
            return [
                'res' => $arr,
                "sum"=>$sum
            
            ];       
    }

    public function actionReportAllCustomer() {

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = ResJanCustomer::find()->asArray()->all();
        
        $locs = [];
        $total = 0;
        $dis = 0;
        $amount = 0;
        foreach($model as $key => $line) {
            $loc_obj = [];
           
            $loc_obj['id'] = $line['id'];
            $loc_obj['state'] = $line['state'];
            $loc_obj['name'] = $line['name'];
            $loc_obj['code'] = $line['code'];
            $loc_obj['amount_total'] = $line['amount_total'];
            $loc_obj['discount'] = $line['discount'];
            $loc_obj['amount_total_remain'] = $line['amount_total_remain'];
            $total += ($line['amount_total']);
            $dis += ($line['discount']);
            $amount += ($line['amount_total_remain']);
            $locs[] = $loc_obj;
        } // end query cus

        return [
            'customers' => $locs,
            'total' => $total,
            'dis'=>$dis,
            'amount'=>$amount
        ];

    }


    public function actionTest() {
          $res = ResRestraints::find()->where(['active' => 1])->asArray()->all();
                         // เลขทืี่ไม่รับซื้อ
                    foreach($res as $id => $val) {
                        $num = $val['number_limit'];
                        $nums[] = $num;
                    }
                    print_r($nums);


       $model = [
            ["number" => "30"],
            ["number" => "31"],
           
            ["number"=> "50"],
            ["number"=> "55"],
             ["number"=> "23"],
       ];
    

                    $valid = ArrayHelper::getColumn($model, 'number');
                    foreach($valid as $id => $val) {
                        $def[] = $val;
                    }
      
        $result = array_diff($def, $nums);
      
        print_r($result);

        $g = array_intersect($def, $nums);
       print_r($g);
       if(count($g) >= 0) {
        print_r("true");
       }  else {
        print_r("false");
       }
    }

    public function actionFormSave() {
    	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = Yii::$app->request->rawBody;
        $post = Json::decode($data);
        $response = Yii::$app->response;
        $customer = $post['modline'];       
        $model = $post['lottos'];        
        $tx = ResJanLotto::getDb()->beginTransaction();
        try {

                $res = ResRestraints::find()->where(['active' => 1])->asArray()->all();
                         // เลขทืี่ไม่รับซื้อ
                    foreach($res as $id => $val) {
                        $num = $val['number_limit'];
                        $nums[] = $num;
                    }
                $valid = ArrayHelper::getColumn($model, 'number');
                    foreach($valid as $id => $val) {
                        $def[] = $val;
                    }
                // แยกเลข ที่ไม่อั้น
                $result = array_diff($def, $nums);

                // แยกเลขที่อั้น
                $g = array_intersect($def, $nums);
        	
                $cus = new ResJanCustomer();
                $cus->name = $customer['name'];
                $cus->code = DocSequenceHelper::genDocNo(DocSequenceHelper::PR_DOC_NO);
                $cus->amount_total = $customer['amount_total'];
                $cus->amount_total_remain = $customer['amount_total_remain'];
                $cus->discount = $customer['discount'];
                $cus->type = $customer['type'];
                $cus->discount_run = $customer['discount_run'];
                $cus->discount_two = $customer['discount_two'];
                $cus->discount_three = $customer['discount_three'];
                $cus->create_uid = Yii::$app->user->id;
			    $cus->create_date = new Expression("NOW()");
			    $cus->write_uid = Yii::$app->user->id;
			    $cus->write_date = new Expression("NOW()");
                if(count($g) >= 0) {
                    $cus->state = "limit";
                }
                if($cus->save(false)) {
					foreach ($result as $key => $value) {
                       if(array_diff($def, $nums)) {
                            $lotto = new ResJanLotto();
                            $lotto->user_id = $cus->id;
                            $lotto->number = $model[$key]['number'];
                            $lotto->top_amount = ($model[$key]['top_amount'])?$model[$key]['top_amount']:0;
                            $lotto->below_amount =($model[$key]['below_amount'])?$model[$key]['below_amount']:0;
                            $lotto->otd_amount = ($model[$key]['otd_amount'])?$model[$key]['otd_amount']:0;
                            $lotto->line_amount_total = ($model[$key]['top_amount'] + $model[$key]['below_amount'] + $model[$key]['otd_amount']);
                            
                            $lotto->create_uid = Yii::$app->user->id;
                            $lotto->create_date = new Expression("NOW()");
                            $lotto->write_uid = Yii::$app->user->id;
                            $lotto->write_date = new Expression("NOW()");
                            $lotto->save(false);
                       }
                		
            		}

                    if(count($g) >= 0) {
                        foreach ($g as $key => $v) {
                            if(array_intersect($def,$nums)) {
                                $limit = new ResJanLottoLimit();
                                $limit->user_id = $cus->id;
                                $limit->number = $model[$key]['number'];
                                $limit->top_amount = ($model[$key]['top_amount'])?$model[$key]['top_amount']:0;
                                $limit->below_amount =($model[$key]['below_amount'])?$model[$key]['below_amount']:0;
                                $limit->otd_amount = ($model[$key]['otd_amount'])?$model[$key]['otd_amount']:0;
                                $limit->line_amount_total = ($model[$key]['top_amount'] + $model[$key]['below_amount'] + $model[$key]['otd_amount']);
                                
                                $limit->create_uid = Yii::$app->user->id;
                                $limit->create_date = new Expression("NOW()");
                                $limit->write_uid = Yii::$app->user->id;
                                $limit->write_date = new Expression("NOW()");
                                $limit->save(false);
                            }
                            
                        }
                    }

                    
                }

                
         

        	$tx->commit();
            return [
                        'limit' => count($g),
                        'cusid' => $cus->id
                    ];
        } catch (\Exception $e){
            $tx->rollBack();
            throw $e;
        }
    }

    public function actionClearBill(){
        $model = ResJanLotto::deleteAll();
        $cus = ResJanCustomer::deleteAll();

        $seq = ResDocSequence::findOne(1);
        $seq->counter = 1;
        $seq->save(false);
    }

    public function actionPdf($id){
       
        $cus = ResJanCustomer::findOne($id);
        $res = ResJanLotto::find()->where(['user_id' => $id])->asArray()->all();
            $arr = [];
            $sum = 0;
            foreach($res as $id => $val) {
                $sum += ($val['line_amount_total']);
                $data = [
                    "number"=> $val['number'],
                    "top_amount"=>$val['top_amount'],
                    "below_amount"=>$val['below_amount'],
                    "otd_amount"=>$val['otd_amount'],
                ];
                $arr[] = $data;
            }

        $content = $this->renderPartial('_preview' , [
            'customer'=> $cus,
            'arr' => $arr
            
        ]);
       // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([

            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            //'format' => Pdf::FORMAT_A4,
            // A4 paper format
            'format' => [80, 105],//Pdf::FORMAT_A4,
            'marginLeft' => 5,
            'marginRight' => 5,
            'marginTop' => 0,
            'marginBottom' => 0,
          //  'marginFooter' => 5,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
           // 'cssFile' => '@frontend/web/css/pdf.css',
            // any css to be embedded if required
            'cssInline' => '*,body{font-family:thsarabun;font-size:20pt;font-weight: Bold}',
            // set mPDF properties on the fly
            'options' => ['title' => 'Preview Report Case:'],
            // call mPDF methods on the fly
            'methods' => [
                'SetTitle' => 'ระบบเจ้ามือหวย By Janny',
               // 'SetSubject' => 'Generating PDF files via yii2-mpdf extension has never been easy',
                //'SetHeader' => ['Krajee Privacy Policy||Generated On: ' . date("r")],
               // 'SetFooter' => ['<div style="text-align: center;font-size: 16px" >@ITHiNKplus</div>'],
               // 'SetAuthor' => 'Kartik Visweswaran',
               // 'SetCreator' => 'Kartik Visweswaran',
                //'SetKeywords' => 'Krajee, Yii2, Export, PDF, MPDF, Output, Privacy, Policy, yii2-mpdf',
            ]
        ]);

         $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        /**
         * We set more options as showing in "vendors/kartik-v/yii2-mpdf/src/Pdf.php/Pdf/options" method
         * What we do, we merge the options array to the existing one.
         */
        $pdf->options = array_merge($pdf->options , [
            'fontDir' => array_merge($fontDirs, [ Yii::$app->basePath . '/web/fonts']),  // make sure you refer the right physical path
            'fontdata' => array_merge($fontData, [
                'thsarabun' => [
                    'R' => 'THSarabunNew.ttf',
                    'I' => 'THSarabunNew Italic.ttf',
                    'B' => 'THSarabunNew Bold.ttf',
                ]
            ])
        ]);

      
        // return the pdf output as per the destination setting
        return $pdf->render();
        
      
    }

    public function actionPdfLimit($id){
       
        $cus = ResJanCustomer::findOne($id);
        $res = ResJanLottoLimit::find()->where(['user_id' => $id])->asArray()->all();
            $arr = [];
            $sum = 0;
            foreach($res as $id => $val) {
                $sum += ($val['line_amount_total']);
                $data = [
                    "number"=> $val['number'],
                    "top_amount"=>$val['top_amount'],
                    "below_amount"=>$val['below_amount'],
                    "otd_amount"=>$val['otd_amount'],
                ];
                $arr[] = $data;
            }

        $content = $this->renderPartial('_preview' , [
            'customer'=> $cus,
            'arr' => $arr
            
        ]);
       // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([

            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            //'format' => Pdf::FORMAT_A4,
            // A4 paper format
            'format' => [80, 150],//Pdf::FORMAT_A4,
            'marginLeft' => 5,
            'marginRight' => 5,
            'marginTop' => 10,
            'marginBottom' => 10,
            'marginFooter' => 5,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
           // 'cssFile' => '@frontend/web/css/pdf.css',
            // any css to be embedded if required
            'cssInline' => '*,body{font-family:thsarabun;font-size:20pt;font-weight: Bold}',
            // set mPDF properties on the fly
            'options' => ['title' => 'Preview Report Case:'],
            // call mPDF methods on the fly
            'methods' => [
                'SetTitle' => 'ระบบเจ้ามือหวย By Janny',
               // 'SetSubject' => 'Generating PDF files via yii2-mpdf extension has never been easy',
                //'SetHeader' => ['Krajee Privacy Policy||Generated On: ' . date("r")],
               // 'SetFooter' => ['<div style="text-align: center;font-size: 16px" >@ITHiNKplus</div>'],
               // 'SetAuthor' => 'Kartik Visweswaran',
               // 'SetCreator' => 'Kartik Visweswaran',
                //'SetKeywords' => 'Krajee, Yii2, Export, PDF, MPDF, Output, Privacy, Policy, yii2-mpdf',
            ]
        ]);

         $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        /**
         * We set more options as showing in "vendors/kartik-v/yii2-mpdf/src/Pdf.php/Pdf/options" method
         * What we do, we merge the options array to the existing one.
         */
        $pdf->options = array_merge($pdf->options , [
            'fontDir' => array_merge($fontDirs, [ Yii::$app->basePath . '/web/fonts']),  // make sure you refer the right physical path
            'fontdata' => array_merge($fontData, [
                'thsarabun' => [
                    'R' => 'THSarabunNew.ttf',
                    'I' => 'THSarabunNew Italic.ttf',
                    'B' => 'THSarabunNew Bold.ttf',
                ]
            ])
        ]);

      
        // return the pdf output as per the destination setting
        return $pdf->render();
        
      
    }
}
