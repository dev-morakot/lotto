<?php

namespace app\modules\resource\models;

use Yii;

/**
 * This is the model class for table "res_district".
 *
 * @property integer $id
 * @property string $name
 * @property integer $province_id
 *
 * @property ResProvince $province
 */
class ResDistrict extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'res_district';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['province_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['province_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResProvince::className(), 'targetAttribute' => ['province_id' => 'id']],
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
            'province_id' => Yii::t('app', 'Province ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvince()
    {
        return $this->hasOne(ResProvince::className(), ['id' => 'province_id']);
    }

    /**
     * @inheritdoc
     * @return ResDistrictQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ResDistrictQuery(get_called_class());
    }
}
