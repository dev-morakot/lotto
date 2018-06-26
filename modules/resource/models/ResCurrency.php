<?php

namespace app\modules\resource\models;

use Yii;

/**
 * This is the model class for table "res_currency".
 *
 * @property integer $id
 * @property string $name
 * @property double $rounding
 * @property string $symbol
 * @property string $symbol_position
 * @property integer $base
 * @property integer $active
 * @property integer $company_id
 * @property integer $create_uid
 * @property string $create_date
 * @property integer $write_uid
 * @property string $write_date
 */
class ResCurrency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'res_currency';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rounding'], 'number'],
            [['base', 'active', 'company_id', 'create_uid', 'write_uid'], 'integer'],
            [['create_date', 'write_date'], 'safe'],
            [['name'], 'string', 'max' => 3],
            [['symbol'], 'string', 'max' => 5],
            [['symbol_position'], 'string', 'max' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'rounding' => Yii::t('app', 'Rounding Factor'),
            'symbol' => Yii::t('app', 'Symbol'),
            'symbol_position' => Yii::t('app', 'Position [before/after]'),
            'base' => Yii::t('app', 'Is Base Currency'),
            'active' => Yii::t('app', 'Is Active'),
            'company_id' => Yii::t('app', 'Company ID'),
            'create_uid' => Yii::t('app', 'Create Uid'),
            'create_date' => Yii::t('app', 'Create Date'),
            'write_uid' => Yii::t('app', 'Write Uid'),
            'write_date' => Yii::t('app', 'Write Date'),
        ];
    }

    /**
     * @inheritdoc
     * @return ResCurrencyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ResCurrencyQuery(get_called_class());
    }
}
