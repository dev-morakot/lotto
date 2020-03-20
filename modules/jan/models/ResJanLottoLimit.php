<?php

namespace app\modules\jan\models;
use app\modules\jan\models\ResJanCustomer;
use Yii;
use yii\db\Expression;
/**
 * This is the model class for table "res_doc_lotto_limit".
 *
 * @property int $id
 * @property int $user_id
 * @property int $number ตัวเลข
 * @property int $top_amount บน/จำนวนเงิน
 * @property int $below_amount ล่าง/จำนวนเงิน
 * @property int $otd_amount โต๊ด/กลับ จำนวนเงิน
 * @property string $type ประเภทหวย
 * @property int $create_uid
 * @property string $create_date
 * @property int $write_uid
 * @property string $write_date
 */
class ResJanLottoLimit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'res_jan_lotto_limit';
    }

    /**
     * @inheritdoc
     */
   /* public function rules()
    {
        return [
            [['user_id', 'top_amount', 'below_amount', 'otd_amount', 'create_uid', 'write_uid'], 'integer'],
            [['type','number'], 'string', 'max' => 30],
            [['create_date', 'write_date'], 'safe'],
        ];
    }*/

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'number' => 'ตัวเลข',
            'top_amount' => 'บน/จำนวนเงิน',
            'below_amount' => 'ล่าง/จำนวนเงิน',
            'otd_amount' => 'โต๊ด/กลับ จำนวนเงิน',
            'type' => 'ประเภทหวย',
            'create_uid' => 'Create Uid',
            'create_date' => 'Create Date',
            'write_uid' => 'Write Uid',
            'write_date' => 'Write Date',
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($isInsert) {
        if($isInsert){
            $this->create_uid = Yii::$app->user->id;
            $this->create_date = new Expression("NOW()");
            $this->write_uid = Yii::$app->user->id;
            $this->write_date = new Expression("NOW()");
        } else {
            $this->write_uid = Yii::$app->user->id;
            $this->write_date = new Expression("NOW()");
        }
        return true;
    }

   
  
}
