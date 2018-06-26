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
use app\components\DateHelper;
use PDO;
/**
 * Description of UserLogComponent
 *
 * @author wisaruthk
 */
class UserLogComponent extends Component {

    public $db = 'db';
    public $logTable = '{{%app_userlog}}';

    /**
     * Initializes the DbTarget component.
     * This method will initialize the [[db]] property to make sure it refers to a valid DB connection.
     * @throws InvalidConfigException if [[db]] is invalid.
     */
    public function init() {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::className());
    }

    public function info($message, $category = NULL,$prefix=NULL) {
        $tableName = $this->db->quoteTableName($this->logTable);
        $sql = "INSERT INTO $tableName ([[user_id]], [[username]], [[log_time]], [[ip_address]], [[category]], [[prefix]], [[message]])
                VALUES (:user_id, :username, NOW(), :ip_address, :category, :prefix, :message)";
        $command = $this->db->createCommand($sql);
        $user = Yii::$app->user->identity;
        $user_id = 0;
        $username = "guest";
        if($user){
            $user_id = $user->id;
            $username = $user->username;
        }
        $request = Yii::$app->request;
        $ip_address = null;
        if($request instanceof yii\console\Request){
            $ip_address = "127.0.0.1";
        } else {
            $ip_address = Yii::$app->request->userIP;
        }
        $command->bindValues([
            ':user_id' => $user_id,
            ':username' => $username,
            ':ip_address' => $ip_address,
            ':category' => $category,
            ':prefix' => $prefix,
            ':message' => $message,
        ])->execute();
    }

}
