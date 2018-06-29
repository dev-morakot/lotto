<?php

namespace app\modules\resource\models;
use app\modules\resource\models\ResUsers;
use Yii;

/**
 * This is the model class for table "res_doc_lotto".
 *
 * @property int $id
 * @property int $user_id
 * @property int $number ตัวเลข
 * @property int $top_amount บน/จำนวนเงิน
 * @property int $below_amount ล่าง/จำนวนเงิน
 * @property int $otd_amount โต๊ด/กลับ จำนวนเงิน
 * @property int $create_uid
 * @property string $create_date
 * @property int $write_uid
 * @property string $write_date
 */
class ResDocLotto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'res_doc_lotto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'number', 'top_amount', 'below_amount', 'otd_amount', 'create_uid', 'write_uid'], 'integer'],
            [['create_date', 'write_date'], 'safe'],
        ];
    }

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
            'create_uid' => 'Create Uid',
            'create_date' => 'Create Date',
            'write_uid' => 'Write Uid',
            'write_date' => 'Write Date',
        ];
    }

    public function getUser(){
        return $this->hasOne(ResUsers::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return ResDocLottoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ResDocLottoQuery(get_called_class());
    }
}
