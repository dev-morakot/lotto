<?php

namespace app\modules\admin\models;

use Yii;
use app\modules\resource\models\ResUsers;
/**
 * This is the model class for table "app_userlog".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $username
 * @property string $log_time
 * @property string $ip_address
 * @property string $category
 * @property string $prefix
 * @property string $message
 */
class AppUserlog extends \yii\db\ActiveRecord
{
    const PURCHASE_ORDER = "PO";
    const PURCHASE_REQUEST = "PR";
    const ACCOUNT_MOVE = "AM";
    const ACCOUNT_INVOICE = "IV";
    const ACCOUNT_PAYMENT = "PAY";
    const STOCK_PICKING = "SP";
    const STOCK_MOVE = "STM";
    const SALE_ORDER = "SO";

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_userlog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['log_time'], 'safe'],
            [['prefix', 'message'], 'string'],
            [['username', 'category'], 'string', 'max' => 30],
            [['ip_address'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'username' => Yii::t('app', 'Username'),
            'log_time' => Yii::t('app', 'Log Time'),
            'ip_address' => Yii::t('app', 'Ip Address'),
            'category' => Yii::t('app', 'Category'),
            'prefix' => Yii::t('app', 'Prefix'),
            'message' => Yii::t('app', 'Message'),
        ];
    }
    
    public function getUser(){
        return $this->hasOne(ResUsers::className(), ['id'=>'user_id']);
    }

    /**
     * @inheritdoc
     * @return AppUserlogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AppUserlogQuery(get_called_class());
    }
}
