<?php

use yii\db\Migration;
use yii\db\Schema;

class m180601_153308_init extends Migration
{
    
    public function tableOptions(){
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        return $tableOptions;
    }
    
    public function safeUp()
    {
        // User group
        $this->createTable('res_group',[
            'id'=>$this->primaryKey(),
            'name'=>$this->string()->comment('Group name'),
            'code'=>$this->string(5)->comment('Group code'),
            'pr_sequence_id'=>$this->integer()->comment("Purchase Request Sequence"),
            //
            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp()->defaultValue(null), // Created on
            'write_uid'=> $this->integer(), // Last Updated by
            'write_date' => $this->timestamp()->defaultValue(null), // Last Updated on
        ],$this->tableOptions());
        
        $this->batchInsert('res_group', ['id','name','pr_sequence_id'], [
            [1,'ฝ่ายบริหาร',1],
            [2,'ฝ่ายบัญชี',1],
            [3,'ฝ่ายทะเบียนยา',1],
            [4,'ฝ่ายผลิต',1],
            [5,'ฝ่ายจัดซื้อ',1],
            [6,'ฝ่ายวิศวกรรม',2],
            [7,'ฝ่ายวิชาการ',1],
            [8,'ฝ่ายประกันคุณภาพ',1],
            [9,'ฝ่ายบุคคล',1],
            [10,'ฝ่ายต่างประเทศ',1],
            [11,'ฝ่ายควบคุมเอกสาร DC',1],
            [12,'ฝ่ายการตลาด',1],
            [13,'ฝ่ายการเงิน',1],
            [14,'ฝ่ายนำเข้า-ส่งออก',1],
            [15,'งานรักษาความปลอดภัย(จป.)',1],
            [16,'ฝ่าย QA&QC',1],
            [17,'ฝ่ายประสานงานขาย',1],
            [18,'ฝ่ายสารสนเทศ',8],
            [19,'แผนกคลังสินค้า',1],
            [20,'ฝ่ายเภสัชกร',1],
            [21,'ฝ่ายวิเคราะห์ผลิตภัณฑ์เคมี',1],
            [22,'ฝ่ายธุรการประสานงานโครงการ',1],
            [23,'ฝ่ายกราฟิคดีไซค์',1],
            [24,'ฝ่ายจัดส่งสินค้า',1],
            [25,'ฝ่ายควบคุมคุณภาพ',1]
        ]);

        // Resource User
        $this->createTable('res_users', [
            'id' => Schema::TYPE_PK,            
            'username' => $this->string(50)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string(),
            'verifymail_token' => $this->string(50),
            'email' => $this->string(100)->notNull(),
            'code'=>$this->string(30)->comment("รหัสพนักงาน"),
            'firstname' => $this->string(30),
            'lastname' => $this->string(30),
            'active' => $this->boolean(), 
            'discount'=> $this->integer(11)->comment('ส่วนลด'),
            'company_id' => $this->integer(),
            'default_section_id' => $this->integer(), // Default Sales Team
            'login_date'=>$this->dateTime()->comment("Login Date"),
            
             //
            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp()->defaultValue(null), // Created on
            'write_uid'=> $this->integer(), // Last Updated by
            'write_date' => $this->timestamp()->defaultValue(null), // Last Updated on
        ],$this->tableOptions());
        
        $password_hash = Yii::$app->security->generatePasswordHash('password');
        //$password_hash = Yii::$app->security->generatePasswordHash('password');

        $this->batchInsert('res_users',['id','username','auth_key','password_hash','email','firstname','lastname','create_uid'],[
            [1,'admin@admin.com','',$password_hash,'admin@admin.com','Mr. Admin','BICGROUP',1],
            [2,'user1@user.com','',$password_hash,'user1@user.com','Mr. User1','BICGROUP',1],
            [3,'user2@user.com','',$password_hash,'user2@user.com','Mr. User2','BICGROUP',1],
            [4,'purchasemanager@user.com','',$password_hash,'purchasemanager@user.com','ผจก. ผู้จัดการ','BICGROUP',1],
            [5,'purchaseofficer@user.com','',$password_hash,'purchaseofficer@user.com','พนง. นักจัดซื้อ','BICGROUP',1],
            [6,'ceo@user.com','',$password_hash,'ceo@user.com','Mr. ผู้บริหาร','BICGROUP',1],
            [7,'manager1@user.com','',$password_hash,'manager1@user.com',' ผจก. สมโชค','BICGROUP',1],
            [8,'employee1@user.com','',$password_hash,'employee1@user.com','พนง. ทุ่มเท','BICGROUP',1],
            [9,'employee2@user.com','',$password_hash,'employee2@user.com','พนง. สุขุม','BICGROUP',1],
            [10,'poweruser@user.com','',$password_hash,'poweruser@user.com','คุณ Power','User',1],
        ]);
        
        $this->batchInsert('res_users',['id','username','auth_key','password_hash','email','firstname','lastname','create_uid'],[
            [11,'employee3@user.com','',$password_hash,'employee3@user.com','พนง. คร้าว EN','BICGROUP',1],
            [12,'employee4@user.com','',$password_hash,'employee4@user.com','พนง. ควบตำแหน่ง','BICGROUP',1],
        ]);
        
        $this->createTable('res_users_group_rel',[
            'user_id' => $this->integer()->comment("comment res_users"),
            'group_id'=> $this->integer()->comment('res_groups')
        ]);
        $this->addForeignKey('fk-res_users_group_rel-user_id','res_users_group_rel','user_id','res_users','id','CASCADE');
        $this->addForeignKey('fk-res_users_group_rel-group_id','res_users_group_rel','group_id','res_group','id','CASCADE');
        $this->batchInsert('res_users_group_rel', ['user_id','group_id'], [
            [1,5],
            [1,6],
            [1,18],
            [11,6], //วิศวกรรม
            [12,5], // employee4,จัดซื้อ
            [12,6], // employee4,วิศวกรรม
        ]);
        
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-res_users_group_rel-user_id', 'res_users_group_rel');
        $this->dropForeignKey('fk-res_users_group_rel-group_id', 'res_users_group_rel');
        $this->dropTable('res_users');      
        $this->dropTable('res_group');
        $this->dropTable('res_users_group_rel');
    }
}
