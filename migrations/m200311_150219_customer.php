<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m200311_150219_customer
 */
class m200311_150219_customer extends Migration
{

    public function tableOptions(){
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        return $tableOptions;
    }
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        // User group
        $this->createTable('res_jan_customer',[
            'id'=>$this->primaryKey(),
            'name'=>$this->string()->comment('name'),
            'code'=>$this->string(5)->comment('code'),
            'type'=>$this->string(50)->comment('ประเภทหวย'),
            'discount_run'=>$this->decimal(13,2)->comment('ส่วนลดเลขวิ่ง'),
            'discount_two'=>$this->decimal(13,2)->comment('ส่วนลดเลข 2 ตัว'),
            'discount_three'=>$this->decimal(13,2)->comment('ส่วนลดเลข 3 ตัว'),
            'amount_total'=>$this->decimal(13,2)->comment('ยอดซื้อทั้งหมด'),
            'amount_total_remain'=>$this->decimal(13,2)->comment('คงเหลือ'),
            'discount'=>$this->decimal(13,2)->comment('ส่วนลด'),
            'state'=>$this->string(5)->comment('สถานะมีเลขอั้นไหม'),
            //
            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp()->defaultValue(null), // Created on
            'write_uid'=> $this->integer(), // Last Updated by
            'write_date' => $this->timestamp()->defaultValue(null), // Last Updated on
        ],$this->tableOptions());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('res_jan_customer');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200311_150219_customer cannot be reverted.\n";

        return false;
    }
    */
}
