<?php

namespace app\modules\resource\models;

use Yii;
use yii\db\Expression;
/**
 * This is the model class for table "res_restraints".
 *
 * @property string $id
 * @property int $number_limit
 * @property integer $active
 * @property int $create_uid
 * @property string $create_date
 * @property int $write_uid
 * @property string $write_date
 */
class ResRestraints extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'res_restraints';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active','number_limit', 'create_uid', 'write_uid'], 'integer'],
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
            'number_limit' => 'เลข ไม่รับซื้อ',
            'active' => Yii::t('app', 'Active'),
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

    /**
     * @inheritdoc
     * @return ResRestraintsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ResRestraintsQuery(get_called_class());
    }
}
