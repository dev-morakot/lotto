<?php

namespace app\models;
use yii\db\ActiveRecord;
use Yii;
use app\modules\resource\models\ResUsers;
use yii\db\Expression;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    // public $id;
    // public $username;
    // public $password;
    // public $authKey;
    // public $accessToken;

    private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];

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
            [['username','firstname','lastname','login_date'],'safe'],
            [['code'],'string'],
            [['username','email'],'unique'],
            [['active'],'boolean'],
            ['auth_key','default','value' => ''],
            
        ];
    }
    

    
    public function fields() {
        return ['active','code','id','email','username','firstname','lastname'];
    }
    
    public function getCreateUser(){
        return $this->hasOne(User::className(), ['id'=>'create_uid']);
    }
    
    public function getWriteUser(){
        return $this->hasOne(User::className(), ['id'=>'write_uid']);
    }
    
    public function getDisplayName(){
        return $this->firstname.' '.$this->lastname.' ('.$this->email.')';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        //Yii::info("findIdentity=".$id);
        $user = static::findOne(['id'=>$id,'active'=>true]);
        if($user){
            // ผู้ใช้มีสิทธิ์ log in ส่วนกลาง
            $res_user = ResUsers::findOne(['id'=>$id,'active'=>true]);
            
            if($res_user){
                // ผู้ใช้มีสิทธิ์เข้า ใช้ database ของแต่ละบริษัท
                return $user;
            } else {
                Yii::$app->session->setFlash('warning', 'ผู้ใช้ไม่มีสิทธิ์เข้าใช้งาน (บริษัท)');
                return null;
            
            }
        } else {
            return null;
        }
        //return static::findOne($id);
        //return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        //Yii::info("findIdentityByAccessToken=".$token);
        $user = static::findOne(['access_token'=>$token,'active'=>true]);
        if($user){
            // ผู้ใช้มีสิทธิ์ log in ส่วนกลาง
            $res_user = ResUsers::findOne(['id'=>$user->id,'active'=>true]);
            if($res_user){
                // ผู้ใช้มีสิทธิ์เข้า ใช้ database ของแต่ละบริษัท
                return $user;
            } else {
                Yii::$app->session->setFlash('warning', 'ผู้ใช้ไม่มีสิทธิ์เข้าใช้งาน (บริษัท)');
                return null;
            }
        } else {
            return null;
        }
        //return static::findOne(['access_token'=>$token]);
        
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        // foreach (self::$users as $user) {
        //     if (strcasecmp($user['username'], $username) === 0) {
        //         return new static($user);
        //     }
        // }

        // return null;
        return static::findOne(['username' => $username,'active'=>true]);
    }
    
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            //'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        $valid = Yii::$app->security->validatePassword($password, $this->password_hash);
        return $valid;
    }

    public function beforeSave($insert)
    {
        $uid = 1;
        if($insert){
            $this->create_uid = $uid;
            $this->create_date = new Expression("NOW()");
            $this->write_uid = $uid;
            $this->write_date = new Expression("NOW()");
        } else {
            $this->write_uid = $uid;
            $this->write_date = new Expression("NOW()");
        }
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
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
