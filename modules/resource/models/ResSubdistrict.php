<?php 

namespace app\modules\resource\models;

use Yii;


class ResSubdistrict extends \yii\db\ActiveRecord {

	public static function tableName() {

		return 'res_subdistrict';
	}


	public function rules(){

		return [

			[['district_id'], 'integer'],
			[['name'], 'string', 'max' => 255],
			[['district_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResSubdistrict::className(), 'targetAttribute' => ['district_id' => 'id']],
		];
	}

	public function attributeLabels(){

		return [
			'id' => Yii::t('app', 'ID'),
			'name' => 'ตำบล/แขวง',
			'district_id' => 'อำเภอ/เขต',
			'province_id' => 'จังหวัด'
		];
	}

	public function getProvince(){

		return $this->hasOne(ResProvince::className(), ['id' => 'province_id']);
	}

	public function getDistrict(){

		return $this->hasOne(ResDistrict::className(), ['id' => 'district_id']);
	}

	public static function find() {

		return new ResSubdistrictQuery(get_called_class());
	}



}


?>