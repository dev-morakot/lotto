<?php

use yii\db\Migration;

/**
 * Class m180724_045935_number_restraints
 */
class m180724_045935_number_restraints extends Migration
{

    public function tableOptions() {
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
        $this->createTable('res_restraints', [
            'id' => $this->bigPrimaryKey(),
            'number_limit' => $this->string(100),
            'type' => $this->string(50),
            'active' => $this->boolean(),
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
        echo "m180724_045935_number_restraints cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180724_045935_number_restraints cannot be reverted.\n";

        return false;
    }
    */
}
