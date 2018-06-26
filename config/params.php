<?php

return [
    'company_id'=>1, //default company_id for use all settings
    'companyLabel'=>"ระบบเจ้ามือหวย",
    'adminEmail' => 'admin@example.com',
    'supportEmail'=>'erp@bicchemical.com', // email สำหรับส่ง reset password
    'user.passwordResetTokenExpire' => 3600,
    'mailSenderName'=>'BIC[PHT] App',
    //Limit to require a second approval
    'prLimitAmount' => 300000.000,
    'prForceSecondApprove'=>true, //บังคับให้อนุมัติขั้น2 แม้จะไม่ถึง limit
    'prLastApproveMail'=>['wisaruthk@feedpro.co.th'], //email หาเมื่อหัวหน้างานอนุมัติ
    'prLastApprove'=>['admin@admin.com','ceo@user.com'],
    // Config for PO
    'poForceApprove'=>true, //บังคับให้ approve PO ทุกรายการ ไม่มี auto 
    'poLimitAmount' => 5000,
    'poLastApproveMail'=>['wisaruthk@feedpro.co.th'],
    'poLastApprove'=>['admin@admin.com','ceo@user.com'],
    
    // Stock Count
    'stockCountLocationAdjustId'=>4,
    
    // Stock Goods Import
    'goodsImportLocationSrcId'=>5, //5 = ผู้จัดจำหน่าย
    'goodsImportLocationQcId'=>12, // 12 บางบัวทอง QC
    'goodsImportLocationReceiveId'=>9, // 9 คลัง บางบัวทอง
    'goodsImportLocationQcNotPassId'=>9, // 9 คลัง บางบัวทอง
    'goodsImportLocationQcUseId'=>18, // 18 ตำแหน่งใช้สินค้าเพื่อทดสอบ
    
    // Stock Goods Import
    'fgImportLocationSrcId'=>11, //11 = ผลิต
    'fgImportLocationQcId'=>14, // 13 ราชบุรี QC
    'fgImportLocationReceiveId'=>10, // 10 คลัง ราชบุรี
    'fgImportLocationQcNotPassId'=>16, // 16 คลังราชบุรี Reject
    'fgImportLocationQcUseId'=>18, // 18 ตำแหน่งใช้สินค้าเพื่อทดสอบ
    'fgImportToAccStockPickingId'=>1, // ใบรับสินค้าสำเร็จรูปจากการผลิต
    
    // Stock Product Return
    'prodReturnLocationSrcId'=>6, // ลูกค้า
    'prodReturnLocationQcId'=>14, // 13 ราชบุรี QC
    'prodReturnLocationReceiveId'=>10, // 10 คลัง ราชบุรี
    'prodReturnLocationQcNotPassId'=>16, // 16 คลังราชบุรี Reject
    'prodReturnLocationQcUseId'=>18, // 18 ตำแหน่งใช้สินค้าเพื่อทดสอบ
    
    // บัญชี AccountStock Type -> Stock Picking
    'out_matl'=>6,
    'in_fg'=>7,



    // email to ฝ่ายบัญชี
    'soApproveMail' => ['sangnarin@bicchemical.com'],
    // Cc meail ฝ่ายบริหาร
    'soCcManagerMail' => ['wipada@bicchemical.com'],

    // Cc อีเมล์แจ้งขออนุมัติ
    'soCcManager' => [
        'taweelarp@bicchemical.com',
        'anchalee@bicchemical.com',
        'prasanee@bicchemical.com',
        'sangnarin@bicchemical.com',
        'nichanun@bicchemical.com',
        'wipada@bicchemical.com'
    ]
];
