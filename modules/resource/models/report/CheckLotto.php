<?php 
namespace app\modules\resource\models\report;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\db\Query;
use \DateTime;
use app\modules\resource\models\ResDocLotto;
use app\modules\resource\models\ResLottoDefault;

class CheckLotto extends \yii\base\Model {

    public $models;
    public $message;
    private $result = null;

    public function process() {
        Yii::info('process check lottoery');
        $model = $this->models;

        $sql = $this->query_sql($model);
        $obj = [];
        $amount = 0;
        $amount_total = 0;
        $discount = 0;
        $line_total = 0;
        foreach($sql['objQuery'] as $line) {
            switch (@$line['type']) {
                case 'สามตัวบน':
                    $amount = $line['top_amount'];
                    $this->message =  Yii::$app->params['three_top']." / บาท";
                    
                    // ถ้ามีส่วนลด ให้ทำ
                    if($line['discount']) {
                        $line_total = ($amount *  Yii::$app->params['three_top']);
                        $discount = ($line_total * $line['discount']) / 100;
                        $amount_total = ($line_total - $discount);
                    } else {
                        $amount_total = ($amount *  Yii::$app->params['three_top']);
                    }

                    break;
                case 'สามตัวล่าง':
                    $amount = $line['below_amount'];
                    $this->message = Yii::$app->params['three_below']." / บาท";
                    

                    // ถ้ามีส่วนลด ให้ทำ
                    if($line['discount']) {
                        $line_total = ($amount *  Yii::$app->params['three_below']);
                        $discount = ($line_total * $line['discount']) / 100;
                        $amount_total = ($line_total - $discount);
                    } else {
                        $amount_total = ($amount * Yii::$app->params['three_below']);
                    }
                   
                    break;
                case 'สามตัวโต๊ด':
                    $amount = $line['otd_amount'];
                    $this->message = Yii::$app->params['three_otd']." / บาท";
                    

                    // ถ้ามีส่วนลด ให้ทำ 
                    if($line['discount']) {
                        $line_total = ($amount *  Yii::$app->params['three_otd']);
                        $discount = ($line_total * $line['discount']) / 100;
                        $amount_total = ($line_total - $discount);
                    } else {
                        $amount_total = ($amount *  Yii::$app->params['three_otd']);
                    }          
                 
                    break;
                case 'สองตัวบน':
                    $amount = $line['top_amount'];
                    $this->message = Yii::$app->params['two_top']." / บาท";
                    

                    // ถ้ามีส่วนลดให้ทำ
                    if($line['discount']) {
                        $line_total = ($amount *  Yii::$app->params['two_top']);
                        $discount = ($line_total * $line['discount']) / 100;
                        $amount_total = ($line_total - $discount);
                    } else {
                        $amount_total = ($amount *  Yii::$app->params['two_top']);
                    }

                    break;
                case 'สองตัวล่าง':
                    $amount = $line['below_amount'];
                    $this->message = Yii::$app->params['two_below']." / บาท";
                    

                    // ถ้ามีส่วนลด ให้ทำ
                    if($line['discount']) {
                        $line_total = ($amount *  Yii::$app->params['two_below']);
                        $discount = ($line_total * $line['discount']) / 100;
                        $amount_total = ($line_total - $discount);
                    } else {
                        $amount_total = ($amount *  Yii::$app->params['two_below']);
                    }
                    break;
            }
             $data = [
                'type' => $line['type'],
                'name' => $line['firstname'],
                'lastname' => $line['lastname'],
                'number' => $line['number'],
                'amount' => $amount,
                'amount_total' => $amount_total,
                'message' => $this->message,
                'discount' => $line['discount']
             ];
             $obj[] = $data;
        }
        return [
            'items' => $obj
        ];
    }

    public function query_sql($model) {
        $query = (new Query())->select('a.number, a.top_amount , a.below_amount, a.type, a.otd_amount, b.firstname, b.lastname, b.discount')
            ->from('res_doc_lotto as a')
            ->leftJoin('res_users as b','b.id = a.user_id');
        $query->where(['<>', 'a.number', 'is null']);

        $query->andFilterWhere(['like','a.number', @$model['number']]);
        Yii::info($query->createCommand()->rawSql);

        $objQuery = $query->all();
        return [
            'objQuery' => $objQuery
        ];
    }
}
?>