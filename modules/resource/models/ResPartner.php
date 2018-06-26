<?php

namespace app\modules\resource\models;

use Yii;
use yii\log\Logger;
use app\modules\resource\models\ResDistrict;
use app\modules\resource\models\ResProvince;
use app\modules\resource\models\ResCountry;
use app\modules\sale\models\SaleArea;
use yii\db\Expression;
use app\modules\resource\models\ResUsers;
use app\modules\account\models\AccountAccount;

/**
 * This is the model class for table "res_partner".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $display_name
 * @property string $comment
 * @property string $address1
 * @property string $address2
 * @property integer $district_id
 * @property integer $province_id
 * @property integer $country_id
 * @property string $zipcode
 * @property integer $parent_id
 * @property integer $supplier
 * @property integer $customer
 * @property string $email
 * @property integer $is_company
 * @property integer $employee
 * @property integer $active
 * @property string $vat
 * @property string $phone
 * @property string $mobile
 * @property string $fax
 * @property string $type
 * @property string $function
 * @property string $contact_person
 * @property integer $default_address_id
 * @property integer $account_payable_id ตั้งเจ้าหนี้ MONEY OUTGOING
 * @property integer $account_receivable_id ตั้งเจ้าลูกหนี้ MONEY INCOMING
 * 
 */
class ResPartner extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'res_partner';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['comment'], 'string'],
            [['parent_id', 'supplier', 'customer', 'is_company', 'employee', 'active', 'sale_area_id',
            'default_address_id'], 'integer'],
            [['name', 'display_name', 'email', 'tax_no', 'phone', 'mobile', 'fax', 'type', 'function', 'code'], 'string', 'max' => 255],
            [['name'], 'required'],
            [['account_payable_id','account_receivable_id'],'integer'],
            [['contact_person'],'string'],
            [['contact_person'],'trim']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'display_name' => Yii::t('app', 'Display Name'),
            'comment' => Yii::t('app', 'notes'),
            'parent_id' => Yii::t('app', 'Related Company'),
            'supplier' => Yii::t('app', 'Supplier'),
            'customer' => Yii::t('app', 'Customer'),
            'email' => Yii::t('app', 'email'),
            'sale_area_id' => Yii::t('app', 'Sale Area'),
            'is_company' => Yii::t('app', 'Is a company'),
            'employee' => Yii::t('app', 'Is a Employee'),
            'active' => Yii::t('app', 'is Active'),
            'tax_no' => Yii::t('app', 'Tax No'),
            'phone' => Yii::t('app', 'Phone'),
            'mobile' => Yii::t('app', 'Mobile'),
            'fax' => Yii::t('app', 'Fax'),
            'type' => Yii::t('app', 'Address Type [contact]'),
            'function' => Yii::t('app', 'ตำแหน่ง'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($isInsert) {

        if ($isInsert) {
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

    public function getDisplayName() {
        return '[' . $this->code . '] ' . $this->name;
    }

    public function getContacts() {
        // 		return all ResPartner where parent_id = $this.id
        $result = ResPartner::find()->where(['parent_id' => $this->id])->asArray()->all();
        return $result;
    }

    public function getCompany() {
        return $this->hasOne(ResPartner::className(), ['id' => 'parent_id'])->alias('company');
    }

    public function getNameFull() {
        $company = $this->company;
        if ($company) {
            return $this->name . " / " . $company->name;
        } else {
            return $this->name;
        }
    }

    public function getSalearea() {
        return $this->hasOne(SaleArea::className(), ['id' => 'sale_area_id']);
    }

    public function getDistrict() {
        return $this->hasOne(ResDistrict::className(), ['id' => 'district_id']);
    }

    public function getProvince() {
        return $this->hasOne(ResProvince::className(), ['id' => 'province_id']);
    }

    public function getCountry() {
        return $this->hasOne(ResCountry::className(), ['id' => 'country_id']);
    }

    public function getDefaultAddress() {
        return $this->hasOne(ResAddress::className(), ['id' => 'default_address_id']);
    }

    public function getAddresses() {
        return $this->hasMany(ResAddress::className(), ['partner_id' => 'id']);
    }
    
    public function getCreateUser(){
        return $this->hasOne(ResUsers::className(), ['id'=>'create_uid']);
    }
    
    public function getWriteUser(){
        return $this->hasOne(ResUsers::className(), ['id'=>'write_uid']);
    }
    
    public function getAccountPayable(){
        return $this->hasOne(AccountAccount::className(), ['id'=>'account_payable_id']);
    }
    
    public function getAccountReceivable(){
        return $this->hasOne(AccountAccount::className(),['id'=>'account_receivable_id']);
    }

    /**
     * @inheritdoc
     * @return ResPartnerQuery the active query used by this AR class.
     */
    public static function find() {
        return new ResPartnerQuery(get_called_class());
    }

}
