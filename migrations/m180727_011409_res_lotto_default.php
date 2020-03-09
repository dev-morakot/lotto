<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180727_011409_res_lotto_default
 */
class m180727_011409_res_lotto_default extends Migration
{

    public function tableOptions(){
        $tableOptions = null;
        if($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        return $tableOptions;
    }
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('res_lotto_default', [
            'id' => $this->primaryKey(),
            'three_top' => $this->string(100)->comment('3ตัวบน'),
            'three_below'=>$this->string(100)->comment('3ตัวล่าง'),
            'three_otd' => $this->string(100)->comment('3ตัวโต๊ด'),
            'two_top'=> $this->string(100)->comment('2ตัวบน'),
            'two_below'=>$this->string(100)->comment('2ตัวล่าง'),

            //
            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp()->defaultValue(null), // Created on
            'write_uid'=> $this->integer(), // Last Updated by
            'write_date' => $this->timestamp()->defaultValue(null), // Last Updated on
        ], $this->tableOptions());

        $this->batchInsert('res_lotto_default', ['id', 'three_top', 'three_below', 'three_otd', 'two_top', 'two_below'], [
            [1,450,90,90,65,65]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('res_lotto_default');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180727_011409_res_lotto_default cannot be reverted.\n";

        return false;
    }
    */
}
