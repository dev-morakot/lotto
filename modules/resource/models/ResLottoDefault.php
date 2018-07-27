<?php

namespace app\modules\resource\models;

use Yii;
use yii\db\Expression;
use yii\base\Model;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "res_lotto_default".
 *
 * @property int $id
 * @property int $three_top 3ตัวบน
 * @property int $three_below 3ตัวล่าง
 * @property int $tree_otd 3ตัวโต๊ด
 * @property int $two_top 2ตัวบน
 * @property int $two_below 2ตัวล่าง
 * @property int $create_uid
 * @property string $create_date
 * @property int $write_uid
 * @property string $write_date
 */
class ResLottoDefault extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'res_lotto_default';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['three_top', 'three_below', 'three_otd', 'two_top', 'two_below', 'create_uid', 'write_uid'], 'integer'],
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
            'three_top' => '3ตัวบน',
            'three_below' => '3ตัวล่าง',
            'three_otd' => '3ตัวโต๊ด',
            'two_top' => '2ตัวบน',
            'two_below' => '2ตัวล่าง',
            'create_uid' => 'Create Uid',
            'create_date' => 'Create Date',
            'write_uid' => 'Write Uid',
            'write_date' => 'Write Date',
        ];
    }

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
        return parent::beforeSave($isInsert);
    }

    /**
     * @inheritdoc
     * @return ResLottoDefaultQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ResLottoDefaultQuery(get_called_class());
    }

    public static function current(){
        $o = new ResLottoDefaultQuery(get_called_class());
        return $o->current()->one();
    }
}
