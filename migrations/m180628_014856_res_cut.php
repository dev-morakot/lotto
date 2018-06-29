<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180628_014856_res_cut
 */
class m180628_014856_res_cut extends Migration
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
        $this->createTable('res_cut', [
            'id' => $this->primaryKey(),
            'three_top' => $this->integer()->comment('3ตัวบน'),
            'three_below'=>$this->integer()->comment('3ตัวล่าง'),
            'tree_otd' => $this->integer()->comment('3ตัวโต๊ด'),
            'two_top'=> $this->integer()->comment('2ตัวบน'),
            'two_below'=>$this->integer()->comment('2ตัวล่าง'),

            //
            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp()->defaultValue(null), // Created on
            'write_uid'=> $this->integer(), // Last Updated by
            'write_date' => $this->timestamp()->defaultValue(null), // Last Updated on
        ], $this->tableOptions());

        $this->batchInsert('res_cut', ['id', 'three_top', 'three_below', 'tree_otd', 'two_top', 'two_below'], [
            [1,100,100,100,100,100]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('res_cut');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180628_014856_res_cut cannot be reverted.\n";

        return false;
    }
    */
}
