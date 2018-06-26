<?php

namespace app\modules\resource\models;

use Yii;
use app\modules\resource\models\ResUsers;
/**
 * This is the model class for table "res_doc_message".
 *
 * @property integer $id
 * @property string $name
 * @property string $message
 * @property integer $ref_id
 * @property string $ref_model
 * @property integer $user_id
 * @property integer $company_id
 * @property integer $create_uid
 * @property string $create_date
 * @property integer $write_uid
 * @property string $write_date
 */
class ResDocMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'res_doc_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['ref_id', 'user_id', 'company_id', 'create_uid', 'write_uid'], 'integer'],
            [['create_date', 'write_date'], 'safe'],
            [['name'], 'string', 'max' => 20],
            [['ref_model'], 'string', 'max' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Doc Name'),
            'message' => Yii::t('app', 'Message'),
            'ref_id' => Yii::t('app', 'Reference ID'),
            'ref_model' => Yii::t('app', 'Reference Type [pr,po]'),
            'user_id' => Yii::t('app', 'User'),
            'company_id' => Yii::t('app', 'Company ID'),
            'create_uid' => Yii::t('app', 'Create Uid'),
            'create_date' => Yii::t('app', 'Create Date'),
            'write_uid' => Yii::t('app', 'Write Uid'),
            'write_date' => Yii::t('app', 'Write Date'),
        ];
    }
    
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

    public function getUser(){
        return $this->hasOne(ResUsers::className(),['id'=>'user_id']);
    }
    /**
     * @inheritdoc
     * @return ResDocMessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ResDocMessageQuery(get_called_class());
    }
}
