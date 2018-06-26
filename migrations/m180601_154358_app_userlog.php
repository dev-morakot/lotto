<?php

use yii\db\Migration;

class m180601_154358_app_userlog extends Migration {

    public function tableOptions() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        return $tableOptions;
    }

    public function up() {
        $this->createTable('app_userlog', [
            'id' => $this->bigPrimaryKey(),
            'user_id' => $this->integer(),
            'username' => $this->string(30),
            'log_time'=>$this->timestamp(),
            'ip_address' => $this->string(15),
            'category' => $this->string(64),
            'prefix' => $this->text(),
            'message' => $this->text(),

            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp()->defaultValue(null), // Created on
            'write_uid'=> $this->integer(), // Last Updated by
            'write_date' => $this->timestamp()->defaultValue(null), // Last Updated on
                ], $this->tableOptions());
    }

    public function down() {
        $this->dropTable('app_userlog');
        return true;
    }

    /*
      // Use safeUp/safeDown to run migration code within a transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}
