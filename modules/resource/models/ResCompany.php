<?php

namespace app\modules\resource\models;

use Yii;
use yii\db\Expression;
/**
 * This is the model class for table "res_company".
 *
 * @property integer $id
 * @property string $name
 * @property integer $partner_id
 */
class ResCompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'res_company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_id'], 'integer'],
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
            'partner_id' => Yii::t('app', 'Partner ID'),
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

    /**
     * @inheritdoc
     * @return ResCompanyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ResCompanyQuery(get_called_class());
    }
    
    public static function current(){
        $cmp = new ResCompany();
        return $cmp->find()->current()->one();
    }
}
