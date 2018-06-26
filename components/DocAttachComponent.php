<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use yii;
use yii\base\Component;
use yii\di\Instance;
use yii\db\Connection;
use yii\bootstrap\Html;
use yii\helpers\Url;
use app\modules\resource\models\ResAttach;

/**
 * Component ช่วยเหลือในการแนบไฟล์
 * @author wisaruthk
 */
class DocAttachComponent extends Component {

    public $db = 'db';
    public $msgTable = '{{%res_attach}}';

    /**
     * Initializes the DbTarget component.
     * This method will initialize the [[db]] property to make sure it refers to a valid DB connection.
     * @throws InvalidConfigException if [[db]] is invalid.
     */
    public function init() {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::className());
    }
    
    public function buttonUrl($model){
        return Html::a('เพิ่มไฟล์แนบ', 
                Url::to(['/resource/res-attach/create-for-model','related_model'=>$model::tableName(),'related_id'=>$model->id]),
                ['target'=>'_blank','class'=>'btn btn-sm btn-primary']);
    }
    /**
     * 
     * @param string $related_model ชื่อ table
     * @param integer $related_id primarykey
     * @param \yii\web\UploadedFile $file
     */
    public function attach($related_model,$related_id,$file,$description=null){
        $query = new \yii\db\Query();
        $query->select('id,name')->from($related_model)->where(['id'=>$related_id]);
        $model = $query->one();
        $id = null;
        $table_name = $related_model;
        $path = sprintf(Yii::getAlias('@attach').'/%s',$table_name);
        if(!is_dir($path)){
            mkdir($path);
        }
        $name = $file->baseName.'.'.$file->extension;
        $d = new \DateTime();
        $d->setTimezone(new \DateTimeZone('Asia/Bangkok'));
        $date_prefix = $d->format('ymdHi');
        $prefix = preg_replace("/[^A-Za-z0-9\_\-\.]/", '', $model['name']);
        $filename = $prefix.'_'.$date_prefix.'_'.$file->baseName.'.'.$file->extension;
        $savepath = $path.'/'.$filename;
        $result = $file->saveAs($savepath);
        if($result==true){
            $sql  = "INSERT INTO res_attach (name,state,description,date,file,origin,related_model,related_id,create_uid) "
                    . "VALUES (:name,:state,:description,:date,:file,:origin,:related_model,:related_id,:create_uid)";
            $command = $this->db->createCommand($sql);
            $user = Yii::$app->user->identity;
            $user_id = 0;
            $username = "guest";
            if($user){
                $user_id = $user->id;
            }
            $origin = (strlen($model['name'])>30)?mb_substr($model['name'],0,25)."...":$model['name'];
            $affected_row = $command->bindValues([
            ':name'=> $name,
            ':state'=>'draft',
            ':description'=>$description,
            ':date'=> DateHelper::nowStringDB(),
            ':file' => $filename,
            ':origin'=> $origin,
            ':related_model' => $table_name,
            ':related_id' => $model['id'],
            ':create_uid' => $user_id,
        ])->execute();
            
            $id = $this->db->getLastInsertID();
        } else {
            $msg = "";
            if($file->error == 1){
                $msg = "[".$file->error."]MAX FILE SIZE";
            }
            throw new \Exception("Save File Error:".$msg);
        }
        return $id;
    }
    
    /**
     * Update เป็น done จะไม่สามารถลบ เมื่อ model เป็นสถานะที่กำหนด
     * @param \yii\db\ActiveRecord $model
     * @param array $done_when_state_in
     */
    public static function updateAttachState($model,$done_when_state_in=['done']){
        $related_model = $model::tableName();
        $related_id = $model->id;
        $attaches = \app\modules\resource\models\ResAttach::find()
                ->where(['related_model'=>$related_model,'related_id'=>$related_id])
                ->all();
        $next_state = 'draft';
        if(in_array($model->state, $done_when_state_in)){
            $next_state = 'done';
        } 
        
        foreach ($attaches as $model){
            $model->state = $next_state;
            $model->update(false);
        }
        
    }

}
