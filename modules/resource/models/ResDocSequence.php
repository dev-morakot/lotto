<?php

namespace app\modules\resource\models;

use Yii;

/**
 * This is the model class for table "res_doc_sequence".
 *
 * @property integer $id
 * @property string $name
 * @property string $prefix
 * @property string $date_format
 * @property integer $running_length
 * @property integer $counter
 */
class ResDocSequence extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'res_doc_sequence';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','code'], 'required'],
            [['running_length', 'counter'], 'integer'],
            [['name','code', 'date_format','type'], 'string'],
            [['prefix'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code'=>Yii::t('app','Code'),
            'name' => Yii::t('app', 'refference name'),
            'prefix' => Yii::t('app', 'Prefix'),
            'date_format' => Yii::t('app', 'date include in doc no'),
            'running_length' => Yii::t('app', 'Running number length'),
            'counter' => Yii::t('app', 'Current counter for document'),
        ];
    }
    
    public function getDisplayName(){
        return $this->name.' ['.$this->prefix.']';
    }
    
    public function getPreview(){
        return \app\components\DocSequenceHelper::genDocNoByIdPreview($this->id);
    }

    /**
     * @inheritdoc
     * @return ResDocSequenceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ResDocSequenceQuery(get_called_class());
    }
}
