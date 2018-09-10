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
        $defualt = ResLottoDefault::current();
        $model = $this->models;

        $sql = $this->query_sql($model);
        $obj = [];
        $locs = [];
        foreach($sql['objQuery'] as $key => $line) {
            $loc_obj = [];

            $move_lines = $this->processQueryLotto($line);

            if($line['type'] == "สามตัวโต๊ด") {
                $a = $line['number'];
                $_a = str_split($a);
                $output = $this->permutation($_a);
                
                $m_sql = $this->selectQuery($output);
                foreach($m_sql as $sql) {

                    $sum_total_amount = $this->SetSwitch($sql, $defualt);

                    if($sql['type'] == 'สามตัวโต๊ด') {
                        $data = [
                            'name' => $sql['firstname'],
                            'lastname' => $sql['lastname'],
                            'number' => $sql['number'],
                            'amount' => $sum_total_amount['amount'],
                            'amount_total' => $sum_total_amount['amount_total'],
                            'message' =>  $sum_total_amount['message'],
                            'discount' => $sql['discount'],
                            'all_amount_lotto' => $sum_total_amount['all_amount_lotto'],
                            'sum_discount' => $sum_total_amount['discount'],
                            'line_total' => $sum_total_amount['line_total']
                        ];
                        $loc_obj['rows'][] = $data;
                    }                       
                }                               
            }

            foreach($move_lines as $k => $value) {

                // คำนวนค่าหวยที่ถูก
                $sum_total_amount = $this->SetSwitch($value, $defualt);
                if($line['type'] == "สามตัวบน") {
                    $data = [                    
                        'name' => $value['firstname'],
                        'lastname' => $value['lastname'],
                        'number' => $value['number'],
                        'amount' => $sum_total_amount['amount'],
                        'amount_total' => $sum_total_amount['amount_total'],
                        'message' =>  $sum_total_amount['message'],
                        'discount' => $value['discount'],
                        'all_amount_lotto' => $sum_total_amount['all_amount_lotto'],
                        'sum_discount' => $sum_total_amount['discount'],
                        'line_total' => $sum_total_amount['line_total']
                    ];
                    $loc_obj['rows'][] = $data;
                }

                if($line['type'] == 'สามตัวล่าง') {
                    $data = [                    
                        'name' => $value['firstname'],
                        'lastname' => $value['lastname'],
                        'number' => $value['number'],
                        'amount' => $sum_total_amount['amount'],
                        'amount_total' => $sum_total_amount['amount_total'],
                        'message' =>  $sum_total_amount['message'],
                        'discount' => $value['discount'],
                        'all_amount_lotto' => $sum_total_amount['all_amount_lotto'],
                        'sum_discount' => $sum_total_amount['discount'],
                        'line_total' => $sum_total_amount['line_total']
                    ];
                    $loc_obj['rows'][] = $data;
                }

                if($line['type'] == 'สองตัวบน') {
                    $data = [                    
                        'name' => $value['firstname'],
                        'lastname' => $value['lastname'],
                        'number' => $value['number'],
                        'amount' => $sum_total_amount['amount'],
                        'amount_total' => $sum_total_amount['amount_total'],
                        'message' =>  $sum_total_amount['message'],
                        'discount' => $value['discount'],
                        'all_amount_lotto' => $sum_total_amount['all_amount_lotto'],
                        'sum_discount' => $sum_total_amount['discount'],
                        'line_total' => $sum_total_amount['line_total']
                    ];
                    $loc_obj['rows'][] = $data;
                }
                if($line['type'] == 'สองตัวล่าง') {
                    $data = [                    
                        'name' => $value['firstname'],
                        'lastname' => $value['lastname'],
                        'number' => $value['number'],
                        'amount' => $sum_total_amount['amount'],
                        'amount_total' => $sum_total_amount['amount_total'],
                        'message' =>  $sum_total_amount['message'],
                        'discount' => $value['discount'],
                        'all_amount_lotto' => $sum_total_amount['all_amount_lotto'],
                        'sum_discount' => $sum_total_amount['discount'],
                        'line_total' => $sum_total_amount['line_total']
                    ];
                    $loc_obj['rows'][] = $data;
                }
                
                
            } // end move
            $loc_obj['type'] = $line['type'];
            $loc_obj['number'] = $line['number'];

            $locs[] = $loc_obj;
        } // end queryLine
        return [
            'locs' => $locs
        ];
    }

    public function query_sql($model) {
        $query = (new Query())->select('a.number, a.top_amount , a.below_amount, a.type, a.otd_amount, b.firstname, b.lastname, b.discount,b.id')
            ->from('res_doc_lotto as a')
            ->leftJoin('res_users as b','b.id = a.user_id');
        $query->where(['<>', 'a.number', 'is null']);

        $query->andFilterWhere(['a.number' => @$model['number']]);
        Yii::info($query->createCommand()->rawSql);
        $query->groupBy('a.number,a.type');
        $objQuery = $query->all();
        return [
            'objQuery' => $objQuery
        ];
    }

    public function processQueryLotto($value) {
        $query = (new Query())->select('
            a.number, a.top_amount, a.below_amount, a.type, a.otd_amount, b.firstname, b.lastname,b.discount,b.id
        ')->from('res_doc_lotto as a')
        ->leftJoin('res_users as b','b.id = a.user_id');
        $query->where(['<>', 'a.number','is null']);
        $query->andWhere(['a.type' => @$value['type']]);
        $query->andFilterWhere(['like','a.number', @$value['number']]);
        $query->orderBy('a.number asc');
        $res = $query->all();
        return $res;
    }

    /**
     * query หาสามเลขโต๊ด
     * 
     */
    public function selectQuery($output) {
        $query = (new Query())->select('
            a.number, a.top_amount, a.below_amount, a.type, a.otd_amount, b.firstname, b.lastname,b.discount, b.id
        ')->from('res_doc_lotto as a')
        ->leftJoin('res_users as b','b.id = a.user_id');
        $query->where(['<>','a.number', 'is null']);
        if($output) {
            $query->andWhere(['in','a.number', $output]);
        }
        $res = $query->all();
        return $res;

    }

    /**
     * คำนวนรวมราคาหวยทั้งหมดของคนๆ นั้น
     *
     */
    private function useAmountLotto($value) {
        $query = (new Query())->select('
            sum(top_amount) as top_amount,
            sum(below_amount) as below_amount, 
            sum(otd_amount) as otd_amount
        ')
        ->from('res_doc_lotto')
        ->where(['user_id' => $value['id']]);
        $res = $query->all();
        $amount = 0;
        foreach ($res as $key => $value) {
            # code...
            $amount = ($value['top_amount'] + $value['below_amount'] + $value['otd_amount']);
        }

        return $amount;
    }
    
    /**
     *
     * สามตัวโต๊ด
     * @return $otd_amount
     */
    private function permutation($_a, $buffer='', $delimiter='') {
        Yii::info("number Otd");
        $output = [];
        
        $num = count($_a);
        if($num > 1) {
            foreach($_a as $key => $val) {
                $temp = $_a;
                unset($temp[$key]);
                sort($temp);

                $return = $this->permutation($temp, trim($buffer.$delimiter.$val, $delimiter), $delimiter);

                if(is_array($return)) {
                    $output = array_merge($output, $return);
                    $output = array_unique($output);
                } else {
                    $output[] = $return;
                }
            }
            return $output;
        } else {
            return $buffer.$delimiter.$_a[0];
        }
    }


    private function SetSwitch($value, $defualt) {
        $amount_total = 0;
        $amount = 0;
        $discount = 0;
        $line_total = 0;
        $user_amount_lotto = 0;
        switch (@$value['type']) {
            case 'สามตัวบน':
                $amount = $value['top_amount'];
                $this->message =  $defualt->three_top." / บาท";

                $user_amount_lotto = $this->useAmountLotto($value);
                
                // ถ้ามีส่วนลด ให้ทำ
                if($value['discount']) {
                    // ถ้ามีส่วนลด ให้นำ จำนวนซื้อทั้งหมด - ส่วนลด
                    $discount = ($user_amount_lotto * $value['discount']) / 100;

                    // ถ้าถูก เอา ราคาที่ซื้อหวย * ราคาหวย เช้น 3ตัว บาทละ 500 เป็นต้น
                    $line_total = ($amount * $defualt->three_top);
                    $amount_total = ($line_total - $discount);

                   // $line_total = ($amount *  $defualt->three_top);
                   // $discount = ($line_total * $value['discount']) / 100;
                   // $amount_total = ($line_total - $discount);
                } else {
                    $amount_total = ($amount *  $defualt->three_top);
                }

                break;
            case 'สามตัวล่าง':
                $amount = $value['below_amount'];
                $this->message = $defualt->three_below." / บาท";
                
                $user_amount_lotto = $this->useAmountLotto($value);
                // ถ้ามีส่วนลด ให้ทำ
                if($value['discount']) {
                    // ถ้ามีส่วนลด ให้นำ จำนวนซื้อทั้งหมด - ส่วนลด
                    $discount = ($user_amount_lotto * $value['discount']) / 100;

                    // ถ้าถูก เอา ราคาที่ซื้อหวย * ราคาหวย เช้น 3ตัว บาทละ 500 เป็นต้น
                    $line_total = ($amount * $defualt->three_below);
                    $amount_total = ($line_total - $discount);

                   // $line_total = ($amount *  $defualt->three_below);
                   // $discount = ($line_total * $value['discount']) / 100;
                   // $amount_total = ($line_total - $discount);
                } else {
                    $amount_total = ($amount * $defualt->three_below);
                }
               
                break;
            case 'สามตัวโต๊ด':
                $amount = $value['otd_amount'];
                $this->message = $defualt->three_otd." / บาท";
                

                $user_amount_lotto = $this->useAmountLotto($value);
                // ถ้ามีส่วนลด ให้ทำ
                if($value['discount']) {
                    // ถ้ามีส่วนลด ให้นำ จำนวนซื้อทั้งหมด - ส่วนลด
                    $discount = ($user_amount_lotto * $value['discount']) / 100;

                    // ถ้าถูก เอา ราคาที่ซื้อหวย * ราคาหวย เช้น 3ตัว บาทละ 500 เป็นต้น
                    $line_total = ($amount * $defualt->three_otd);
                    $amount_total = ($line_total - $discount);

                   // $line_total = ($amount *  $defualt->three_otd);
                   // $discount = ($line_total * $value['discount']) / 100;
                   // $amount_total = ($line_total - $discount);
                } else {
                    $amount_total = ($amount * $defualt->three_otd);
                }    
             
                break;
            case 'สองตัวบน':
                $amount = $value['top_amount'];
                $this->message = $defualt->two_top." / บาท";
                

                $user_amount_lotto = $this->useAmountLotto($value);
                // ถ้ามีส่วนลด ให้ทำ
                if($value['discount']) {
                    // ถ้ามีส่วนลด ให้นำ จำนวนซื้อทั้งหมด - ส่วนลด
                    $discount = ($user_amount_lotto * $value['discount']) / 100;

                    // ถ้าถูก เอา ราคาที่ซื้อหวย * ราคาหวย เช้น 3ตัว บาทละ 500 เป็นต้น
                    $line_total = ($amount * $defualt->two_top);
                    $amount_total = ($line_total - $discount);

                   // $line_total = ($amount *  $defualt->two_top);
                   // $discount = ($line_total * $value['discount']) / 100;
                   // $amount_total = ($line_total - $discount);
                } else {
                    $amount_total = ($amount * $defualt->two_top);
                }    

                break;
            case 'สองตัวล่าง':
                $amount = $value['below_amount'];
                $this->message = $defualt->two_below." / บาท";
                

                $user_amount_lotto = $this->useAmountLotto($value);
                // ถ้ามีส่วนลด ให้ทำ
                if($value['discount']) {
                    // ถ้ามีส่วนลด ให้นำ จำนวนซื้อทั้งหมด - ส่วนลด
                    $discount = ($user_amount_lotto * $value['discount']) / 100;

                    // ถ้าถูก เอา ราคาที่ซื้อหวย * ราคาหวย เช้น 3ตัว บาทละ 500 เป็นต้น
                    $line_total = ($amount * $defualt->two_below);
                    $amount_total = ($line_total - $discount);

                   // $line_total = ($amount *  $defualt->two_top);
                   // $discount = ($line_total * $value['discount']) / 100;
                   // $amount_total = ($line_total - $discount);
                } else {
                    $amount_total = ($amount * $defualt->two_below);
                } 
                break;
        }
        return [
            'amount_total' => $amount_total, 
            'message' => $this->message,
            'all_amount_lotto' => $user_amount_lotto,
            'amount' => $amount,
            'discount' => $discount,
            'line_total' => $line_total
        ];
    }
}
?>