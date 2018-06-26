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


/**
 * Description of DocMsgComponent
 *
 * @author wisaruthk
 */
class DocMsgComponent extends Component {

    public $db = 'db';
    public $msgTable = '{{%res_doc_message}}';

    /**
     * Initializes the DbTarget component.
     * This method will initialize the [[db]] property to make sure it refers to a valid DB connection.
     * @throws InvalidConfigException if [[db]] is invalid.
     */
    public function init() {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::className());
    }

    /**
     * 
     * @param type $model
     * @param type $message
     * @param type $name_attr
     */
    public function msg($model,$message,$name_attr='name'){
        // EXAMPLE :: ดึงชื่อ table จาก model
        $this->addMsg($model[$name_attr], $message, $model->id, $model::tableName());
    }
    /**
     * 
     * @param string $name ex. PO1234
     * @param string $message ex. create
     * @param integer $ref_id 
     * @param string $ref_model
     */
    public function addMsg($name,$message,$ref_id,$ref_model) {
        $tableName = $this->db->quoteTableName($this->msgTable);
        $sql = "INSERT INTO $tableName ([[user_id]], [[name]], [[message]], [[ref_id]], [[ref_model]],[[create_uid]], [[create_date]], [[write_date]])
                VALUES (:user_id, :name, :message, :ref_id,:ref_model, :create_uid, NOW(),NOW())";
        $command = $this->db->createCommand($sql);
        $user = Yii::$app->user->identity;
        $user_id = 0;
        $username = "guest";
        if($user){
            $user_id = $user->id;
        }
        $command->bindValues([
            ':user_id' => $user_id,
            ':name'=> $name,
            ':message' => $message,
            ':ref_id' => $ref_id,
            ':ref_model' => $ref_model,
            ':create_uid' => $user_id,
        ])->execute();
    }

}
