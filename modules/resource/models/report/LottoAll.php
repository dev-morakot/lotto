<?php

namespace app\modules\resource\models\report;

use Yii;
use app\modules\resource\models\ResDocLotto;
use yii\db\Query;
use yii\helpers\Json;


/**
 *
 Report Model will generate data Like this.
 * 
 *
 */
class LottoAll extends \yii\base\Model {

    public $type;
    public $result;
    private $totals;

    public function rules(){
        return [
            [['type'], 'string'],
           // [['type'],'required']
        ];
    }

    /**
     * process
     */
    public function process(){
        if(!$this->validate()) {
            return false;
        }

        if($this->type == 'ทั้งหมด') {
            $totals = [
                'close' => $this->getSearchAll()
            ];
            $this->totals = $totals;
        } else {
            $totals = [
                'close' => $this->getSearch($this->type)
            ];
            $this->totals = $totals;
        }

        // ค้นหาชื่อประเภทหวย
        foreach($totals['close'] as $key => $r) {
            $array = [];
            $sum = 0;        
                switch ($r['type']) {
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
                    'id' => $r['id'],
                    'number' => $r['number'],
                    'type' => $r['type'],
                    'amount' => 33
                ];
                $array[] = $key_array;
            
        }

        $data = [
            'arr' => $array,
            'sum' => $sum
        ];
        return $this->result = $data;


    }

    /**
     * 
     * @params type $type
     */
    private function getSearch($type) {
        $q = (new Query())
            ->select("*")
            ->from('res_doc_lotto')
            ->where(['<>', 'number', 'is null']);
        if($type) {
            $q->andWhere(['like','type', $type]);
        }
        $q->orderBy('id asc');
        $res = $q->all();

        return $res;
    }

    /**
     * 
     * @params type $type
     */
    private function getSearchAll() {
        $q = (new Query())
            ->select("*")
            ->from('res_doc_lotto');
        $q->orderBy('id asc');
        $res = $q->all();

        return $res;
    }

}