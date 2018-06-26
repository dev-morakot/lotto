<?php

namespace app\modules\resource\models;

use Yii;
use app\modules\resource\models\ResGroup;
use yii\db\Expression;

/**
 * This is the model class for table "res_users".
 *
 * @property integer $id
 * @property string $code รหัสพนักงาน
 * @property string $username
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
class ResUsers extends \yii\db\ActiveRecord
{

   
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'res_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['username','email'],'unique'],
            [['active', 'discount' ,'company_id', 'create_uid', 'write_uid'], 'integer'],
            [['create_date', 'write_date'], 'safe'],
            [['username','firstname','lastname','email'], 'string', 'max' => 255],
            [['code'],'string'],
            ['password_hash', 'required'],
            ['password_hash', 'string', 'min' => 6],
        ];
    }
    
    public function fields() {
        $fields = [
            'id','firstname','lastname','email'
        ];
        
        return $fields;
        
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
            'login_date' => Yii::t('app', 'Login Date'),
            'active' => Yii::t('app', 'Active'),
            'company_id' => Yii::t('app', 'Company ID'),
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
    
    public function getGroups()
    {
        return $this->hasMany(ResGroup::className(), ['id' => 'group_id'])
                ->viaTable('res_users_group_rel', ['user_id'=>'id']);
    }
    
    public function getGroupDisplay(){
        $groups = $this->getGroups()->asArray()->all();
        $group_names = array_column($groups, 'name');
        $str = implode(", ", $group_names);
        return $str;
    }
    
    public static function currentUser(){
        return ResUsers::findOne(['id'=>Yii::$app->user->id]);
    }
    
    public static function currentUserGroups(){
        $user = ResUsers::find()
                ->where(['id'=>Yii::$app->user->id])
                ->one();
        return $user->groups;
    }
    
    public function getDisplayName(){
        return $this->firstname.' '.$this->lastname.' ('.$this->email.')';
    }
    
   
    
    public function getFullName(){
        return $this->firstname.' '.$this->lastname;
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new ResUsers();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->discount = $this->discount;
            $user->code = $this->code;
            $user->firstname = $this->firstname;
            $user->lastname = $this->lastname;
            $user->active = $this->active;
            $user->setPassword($this->password_hash);
            
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
    * Generates verify mail token
    */
    public function generateVerifyMailToken()
    {
      $this->verifymail_token = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    
    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
 
    
}
