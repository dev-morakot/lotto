<?php

namespace app\modules\resource\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "res_attach".
 *
 * @property integer $id
 * @property string $name
 * @property string $state
 * @property string $date
 * @property string $description
 * @property string $file
 * @property integer $revision
 * @property string $hash
 * @property string $origin
 * @property string $related_model
 * @property integer $related_id
 * @property integer $company_id
 * @property integer $create_uid
 * @property string $create_date
 * @property integer $write_uid
 * @property string $write_date
 */
class ResAttach extends \yii\db\ActiveRecord
{
    public $uploadFile;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'res_attach';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'create_date', 'write_date'], 'safe'],
            [['description'], 'string'],
            [['revision', 'related_id', 'company_id', 'create_uid', 'write_uid'], 'integer'],
            [['name', 'file', 'hash'], 'string', 'max' => 255],
            [['origin'], 'string', 'max' => 30],
            [['related_model'], 'string', 'max' => 64],
            [['uploadFile'],'file','skipOnEmpty'=>true,'extensions'=>'png, jpg, pdf','maxSize'=>1024*1024*3],
            [['state'],'string'],
            [['state'],'in','range'=>['draft','done']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'date' => 'Date',
            'description' => 'Description',
            'file' => 'File',
            'revision' => 'Revision',
            'hash' => 'Hash',
            'origin' => 'Origin',
            'related_model' => 'Related Model',
            'related_id' => 'Related ID',
            'company_id' => 'Company ID',
            'create_uid' => 'Create Uid',
            'create_date' => 'Create Date',
            'write_uid' => 'Write Uid',
            'write_date' => 'Write Date',
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
    
    public function getCreateUser(){
        return $this->hasOne(ResUsers::className(), ['id'=>'create_uid']);
    }
    
    /**
     * fullpath with file name
     */
    public function getFullPath(){
        $path = sprintf(Yii::getAlias('@attach').'/%s',$this->related_model);
        $fullpath = $path.'/'.$this->file;
        return $fullpath;
    }
    
    public function getIsExist(){
        return file_exists($this->getFullPath());
    }
    
    
    public function delete() {
        if($this->state=='done'){
            $this->addError('state', 'เอกสารยืนยันแล้ว ไม่สามารถลบ');
            return false;
        }
        $tx = Yii::$app->db->beginTransaction();
        try {
            if($this->getIsExist()){
                unlink($this->getFullPath());
            }
            parent::delete();
            Yii::$app->docmsg->addMsg($this->origin,'ลบไฟล์ '.$this->name,$this->related_id,$this->related_model);
            $tx->commit();
        } catch (\Exception $ex){
            $tx->rollback();
            throw $ex;
        }
        return true;
        
    }
    
    /**
     * @inheritdoc
     * @return ResAttachQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ResAttachQuery(get_called_class());
    }
}
