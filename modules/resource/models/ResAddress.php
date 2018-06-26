<?php

namespace app\modules\resource\models;

use Yii;
use app\modules\resource\models\ResCountry;
use app\modules\resource\models\ResProvince;
use app\modules\resource\models\ResDistrict;
use app\modules\resource\models\ResSubdistrict;
use yii\db\Expression;
/**
 * This is the model class for table "res_address".
 *
 * @property integer $id
 * @property string $name
 * @property string $first_name
 * @property string $last_name
 * @property string $company_name
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property integer $district_id
 * @property integer $province_id
 * @property integer $country_id
 * @property string $postal_code
 * @property integer $partner_id
 * @property string $phone
 * @property string $mobile
 * @property string $fax
 * @property string $type
 * @property integer $is_company_addr
 * @property integer $setting_id
 * @property integer $company_id
 * @property integer $create_uid
 * @property string $create_date
 * @property integer $write_uid
 * @property string $write_date
 */
class ResAddress extends \yii\db\ActiveRecord
{
    public $is_default;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'res_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['district_id', 'province_id', 'subdistrict_id', 'country_id', 'partner_id', 'is_company_addr','setting_id', 'company_id', 'create_uid', 'write_uid'], 'integer'],
            [['create_date', 'write_date'], 'safe'],
            [['name', 'first_name', 'last_name', 'company_name', 'address1', 'address2', 'postal_code', 'phone', 'mobile', 'fax','city'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 32],
            [['type'],'in','range'=>['billing','shipping','mailing','reporting','other']],
            //[['postal_code'],'required'],
            [['address1'],'required'],
            [['first_name','last_name','address1','address2','postal_code','phone','mobile','fax','city'],'trim'],
            [['is_default'],'boolean']
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
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'company_name' => Yii::t('app', 'Company Name'),
            'address1' => Yii::t('app', 'Address1'),
            'address2' => Yii::t('app', 'Address2'),
            'district_id' => Yii::t('app', 'District'),
            'province_id' => Yii::t('app', 'Province'),
            'country_id' => Yii::t('app', 'Country'),
            'postal_code' => Yii::t('app', 'Postal'),
            'partner_id' => Yii::t('app', 'Partner'),
            'phone' => Yii::t('app', 'Phone'),
            'mobile' => Yii::t('app', 'Mobile No.'),
            'fax' => Yii::t('app', 'Fax No.'),
            'type' => Yii::t('app', 'type [billing,shipping]'),
            'is_company_addr' => Yii::t('app', 'address for our company'),
            'company_id' => Yii::t('app', 'Company ID'),
            'create_uid' => Yii::t('app', 'Create Uid'),
            'create_date' => Yii::t('app', 'Create Date'),
            'write_uid' => Yii::t('app', 'Write Uid'),
            'write_date' => Yii::t('app', 'Write Date'),
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
    
    
    public function getPartner(){
        return $this->hasOne(ResPartner::className(), ['id'=>'partner_id']);
    }
    
    
    public function getCountry(){
        return $this->hasOne(ResCountry::className(), ['id'=>'country_id']);
    }
    
    
    public function getProvince(){
        return $this->hasOne(ResProvince::className(),['id'=>'province_id']);
    }
    
    
    public function getDistrict(){
        return $this->hasOne(ResDistrict::className(), ['id'=>'district_id']);
    }
    
    
    public function getCountryDisplay(){
        if($this->country){
            return $this->country->name;
        } else {
            return "";
        }
    }


    public function getProvinceDisplay(){
        if($this->province){
            return $this->province->name;
        } else {
            return "";
        }
    }
    
    
    public function getDistrictDisplay(){
        if($this->district){
            return $this->district->name;
        } else {
            return "";
        }
    }

    
    public function getSubdistrict(){

        return $this->hasOne(ResSubdistrict::className(), ['id' => 'subdistrict_id']);
    }

    
    public function getDisplayName(){
        $addr = "";
        if($this->company_name){
            $addr .= $this->company_name." ";
        }
        $addr .= $this->address1." ";
        if($this->address2){
            $addr .= $this->address2." ";
        }
        if($this->subdistrict){
            $addr .= $this->subdistrict->name." ";
        }
        if($this->district){
            $addr .= $this->district->name." ";
        }
        if($this->province){
            $addr .= $this->province->name." ";
        }
        if($this->postal_code){
            $addr .= $this->postal_code." ";
        }
        return $addr;
    }

    
    /**
     * @inheritdoc
     * @return ResAddressQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ResAddressQuery(get_called_class());
    }
}
