<?php

use yii\db\Migration;

class m180601_153801_res extends Migration {

    public function tableOptions() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        return $tableOptions;
    }

    public function up() {
        // Resource Company
        $this->createTable('res_company', [
            'id'=>$this->primaryKey(),
            'name'=>$this->string(),
            'partner_id'=>$this->integer(),

            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp()->defaultValue(null), // Created on
            'write_uid'=> $this->integer(), // Last Updated by
            'write_date' => $this->timestamp()->defaultValue(null), // Last Updated on
        ],$this->tableOptions());
        $this->batchInsert('res_company', ['id','name','partner_id'], [
            [1,'บริษัทฟาร์มาเทคจำกัด',1]
        ]);
        
        
        $this->createTable('res_doc_sequence', [
            'id' => $this->primaryKey(),
            'code'=>$this->string(64)->comment('refference name')->notNull()->unique(),
            'name' => $this->string(100)->comment('name'),
            'prefix' => $this->string(10)->comment('Prefix'),
            'date_format' => $this->string(10)->comment('date include in doc no (php format)'),
            'running_length' => $this->integer()->comment('Running number length'),
            'counter' => $this->integer()->comment("Current counter for document"),
            'type'=>$this->string(64)->comment('type'),

            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp()->defaultValue(null), // Created on
            'write_uid'=> $this->integer(), // Last Updated by
            'write_date' => $this->timestamp()->defaultValue(null), // Last Updated on
                ], $this->tableOptions());
        $this->batchInsert('res_doc_sequence', ['id', 'code','name', 'prefix', 'date_format', 'running_length', 'counter','type'], [
            [1, 'pr_doc_no','เลขเอกสารจัดซื้อ(PR)', 'PR', 'y', 5, 1,'purchase.request'],
            [2, 'pr-en_docno','เลขเอกสารวิศวกรรม(EN)','EN','y',5,1,'purchase.request'],
            [3, 'po_doc_no','เลขเอกสารจัดซื้อ(PO)', 'PO', 'y', 5, 1,'purchase.order'],            
            [4, 'rr_doc_no','เลข RR', 'RR', 'y', 5, 1,null],
            [5, 'whin_doc_no','เลขเอกสาร WH/IN', 'WH/IN-', 'y', 5, 1,'stock.picking.type'],
            [6, 'whout_doc_no','เลขเอกสาร WH/OUT', 'WH/OT-', 'y', 5, 1,'stock.picking.type'],
            [7, 'whint_doc_no','เลขเอกสาร WH/INTERNAL', 'WH/INT-', 'y', 5, 1,'stock.picking.type'],
            [8, 'pr-it_docno','เลขเอกสารIT(IT)','IT','y',5,1,'purchase.request'],
            [9, 'so_doc_no','เลขเอกสาร SO','SO','y',5,1,'sale.order'],
            [10,'jr_doc_no1','Purchase Journal','UV','y',5,1,'account.journal'],
            [11,'jr_doc_no2','Sale Journal','SV','y',5,1,'account.journal'],
            [12,'jr_doc_no3','Misc Journal','MISC','y',5,1,'account.journal'],
            [13,'jr_doc_no4','BNK Journal','BNK','y',5,1,'account.journal'],
            [14,'jr_doc_no5','Open Journal','OPEJ','y',5,1,'account.journal'],
            [15,'jr_doc_no6','Sales Refund Journal','SCNJ','y',5,1,'account.journal'],
            [16,'jr_doc_no7','Purchase Refund Journal','ECNJ','y',5,1,'account.journal'],
            [17,'jr_doc_no8','Stock Journal','STJ','y',5,1,'account.journal'],
            [18, 'whint_doc_no1','เลขเบิกเพื่อผลิต PD/INT', 'PD/INT-', 'y', 5, 1,'stock.picking.type'],
            [19, 'whint_doc_no2','เลขโอนสินค้าสำเร็จรูป FG/INT', 'FG/INT-', 'y', 5, 1,'stock.picking.type'],
            [20, 'whout_doc_no1','เลขจ่ายสินค้าสำเร็จรูป EX/OUT', 'EX/OUT-', 'y', 5, 1,'stock.picking.type'],
            [21, 'whin_doc_no1','เลขใบรับโอนเข้าคลัง IM/IN', 'IM/IN-', 'y', 5, 1,'stock.picking.type'],
            [22, 'payment_out','เลขจ่าย PAY/OUT', 'PAY/OUT-', 'y', 5, 1,'account.payment'],
            [23,'jr_doc_no9','Pay In Journal','RV','y',5,1,'account.journal'],
            [24,'jr_doc_no10','Pay Out Journal','PV','y',5,1,'account.journal'],
            [25,'inv_cust','Customer Invoice','IV','y',5,1,'account.invoice'],
            [26,'inv_supp','Supplier Invoice','RR','y',5,1,'account.invoice'],
            [27,'inv_cust_cn','Customer Credit Note','SR','y',5,1,'account.invoice'],
            [28,'inv_supp_cn','Supplier Credit Note','GR','y',5,1,'account.invoice'],
            [29,'inv_cust_dn','Customer Debit Note','DN','y',5,1,'account.invoice'],
            [30,'inv_supp_dn','Supplier Debit Note','DN','y',5,1,'account.invoice'],
            [31,'stock_goods_import_doc_no','Stock Good Import','WH/IN','y',5,1,'stock.goods.import'],
            [32,'rm_doc_no','ใบจ่ายวัตถุดิบ/วัสดุบรรจุ','RM','y',5,1,'stock.misc.issue'],
            [33,'ro_doc_no','ใบคืนวัตถุดิบ/วัสดุบรรจุ','RO','y',5,1, 'stock.misc.issue'],
            [34,'fg_doc_no','รายการโอนสินค้าสำเร็จรูป','FG','y',5,1, 'stock.fg.import'],
            [35,'fg_out_doc_no','รายการจ่ายสินค้าสำเร็จรูป','FG/OUT','y',5,1, 'stock.fg.issue'],
            [36,'acc_stock_matl','ใบจ่ายวัตถุดิบเพื่อผลิต','PD','y',5,1,'account.stock'],
            [37,'acc_stock_rl','โอนย้ายระหว่างคลัง','RL','y',5,1,'account.stock'],
            [38,'acc_stock_fg','รับสินค้าสำเร็จรูปจากการผลิต','PI','y',5,1,'account.stock'],
            [39,'jr_doc_pd','สมุดรายวันเบิกเพื่อผลิต','PD','ym',5,1,'account.journal'],
            [40,'jr_doc_pk','สมุดรายวันเบิก packaging','PK','ym',5,1,'account.journal'],
            [41,'jr_doc_pi','สมุดรายวันเบิก packaging','PI','ym',5,1,'account.journal'],
            [42,'jr_doc_xs','สมุดรายวันเบิก ตัวอย่าง','XS','ym',5,1,'account.journal'],
            [43, 'payment_in','เลขรับ RE', 'RE', 'ym', 5, 1,'account.payment'],
            [44, 'qc_sequence','เลข QC', 'QC', 'ym', 5, 1,'qc.inspection'],
            [45, 'prod_return','เลข คืนสินค้า','FR','ym',5,1,'stock.prod.return'],
            [46, 'acc.pi','PI','PI','ym',5,1,'account.stock.picking'],
            [47, 'acc.pd','PD','PD','ym',5,1,'account.stock.picking'],
            [48, 'acc.rl','RL','RL','ym',5,1,'account.stock.picking']
        ]);
        
      
        $this->createTable('res_doc_message', [
            'id'=>$this->bigPrimaryKey(),
            'name'=>$this->string(20)->comment('Doc Name'),
            'message'=>$this->text(),
            'ref_id'=>$this->integer()->comment('Reference ID'),
            'ref_model'=>$this->string(64)->comment('Reference Type [pr,po]'),
            //'prev_state'=>$this->string(50)->comment("Previouse document state"),
            //'to_state'=>$this->string(50)->comment("Current State"),
            'user_id'=>$this->integer()->comment("User"),
            //
            'company_id'=>$this->integer(),
            'create_uid' => $this->integer(), // Created by
            'create_date' =>$this->timestamp()->defaultValue(null), // Created on
            'write_uid' => $this->integer(), // Last Updated by
            'write_date' => $this->timestamp()->defaultValue(null), // Last Updated on
        ]);
    }

    public function down() {
       
        $this->dropTable('res_doc_sequence');
        $this->dropTable('res_company');
        
        $this->dropTable('res_doc_message');
       
        return true;
    }

}
