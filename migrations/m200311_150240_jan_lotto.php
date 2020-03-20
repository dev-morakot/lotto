<?php

use yii\db\Migration;

/**
 * Class m200311_150240_jan_lotto
 */
class m200311_150240_jan_lotto extends Migration
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
         $this->createTable('res_jan_lotto', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'number' => $this->string(100)->comment('ตัวเลข'),
            'top_amount' => $this->string(100)->comment('บน/จำนวนเงิน'),
            'below_amount' => $this->string(100)->comment('ล่าง/จำนวนเงิน'),
            'otd_amount' => $this->string(100)->comment('โต๊ด/กลับ จำนวนเงิน'),
            'type' => $this->string()->comment('ประเภทหวย'),
            
            'line_amount_total'=>$this->decimal(13,2)->comment('ยอดซื้อทั้งหมด'),
            
            //
            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp()->defaultValue(null), // Created on
            'write_uid'=> $this->integer(), // Last Updated by
            'write_date' => $this->timestamp()->defaultValue(null), // Last Updated on
        ], $this->tableOptions());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('res_jan_lotto');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200311_150240_jan_lotto cannot be reverted.\n";

        return false;
    }
    */
}
