<?php

namespace app\modules\resource\models;

use Yii;

/**
 * This is the model class for table "res_province".
 *
 * @property integer $id
 * @property string $name

 *
 * @property ResDistrict[] $resDistricts
 
 */
class ResProvince extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'res_province';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
            [['name'], 'string', 'max' => 255],
            
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
            
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResDistricts()
    {
        return $this->hasMany(ResDistrict::className(), ['province_id' => 'id']);
    }

   

    /**
     * @inheritdoc
     * @return ResProvinceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ResProvinceQuery(get_called_class());
    }
}
