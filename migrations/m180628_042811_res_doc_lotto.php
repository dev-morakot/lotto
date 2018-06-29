<?php

use yii\db\Migration;

/**
 * Class m180628_042811_res_doc_lotto
 */
class m180628_042811_res_doc_lotto extends Migration
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
        $this->createTable('res_doc_lotto', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'number' => $this->integer()->comment('ตัวเลข'),
            'top_amount' => $this->integer()->comment('บน/จำนวนเงิน'),
            'below_amount' => $this->integer()->comment('ล่าง/จำนวนเงิน'),
            'otd_amount' => $this->integer()->comment('โต๊ด/กลับ จำนวนเงิน'),

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
        $this->dropTable('res_doc_lotto');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180628_042811_res_doc_lotto cannot be reverted.\n";

        return false;
    }
    */
}
