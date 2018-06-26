<?php

namespace app\modules\resource\models;

use Yii;

/**
 * This is the model class for table "res_country".
 *
 * @property integer $id
 * @property string $name
 * @property string $name_en
 *
 * @property ResProvince[] $resProvinces
 */
class ResCountry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'res_country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_en'], 'string', 'max' => 255],
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
            'name_en' => Yii::t('app', 'Name En'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResProvinces()
    {
        return $this->hasMany(ResProvince::className(), ['country_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ResCountryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ResCountryQuery(get_called_class());
    }
}
