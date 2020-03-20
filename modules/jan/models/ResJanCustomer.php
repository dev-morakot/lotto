<?php

namespace app\modules\jan\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "res_users".
 *
 * @property integer $id
 * @property string $code รหัสพนักงาน
 * @property string $name
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property integer $login_date
 * @property integer $discount
 * @property integer $active
 * @property integer $company_id
 * @property integer $create_uid
 * @property string $create_date
 * @property integer $write_uid
 * @property string $write_date
 */
class ResJanCustomer extends \yii\db\ActiveRecord
{

   
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'res_jan_customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
             [['name'], 'required'],
            [['code'],'unique'],
            [['discount', 'create_uid', 'write_uid'], 'integer'],
            [['create_date', 'write_date'], 'safe'],
           
            [['code'],'string'],
         
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code'=>Yii::t('app','รหัสพนักงาน'),
            'name' => Yii::t('app', 'Name'),
           
            'discount' => Yii::t('app', 'Discount'),
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
    
    
}
