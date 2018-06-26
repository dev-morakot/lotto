<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;
use Yii;
use \DateTime;
use app\modules\resource\models\ResDocSequence;
use app\models\User;
/**
 * Description of DateHelper
 *
 * @author wisaruthk
 */
class DocSequenceHelper {
    
    const PR_DOC_NO = "pr_doc_no";
    const PO_DOC_NO = "po_doc_no";
    const PO_INTL_DOC_NO = "po_intl_doc_no";
    const SO_DOC_NO = "so_doc_no";
    const IN_INVOICE_DOC_NO = "rr_doc_no";
    const OUT_INVOICE_DOC_NO = 'out_iv_doc_no';
    const WHIN_DOC_NO = "whin_doc_no";
    const STOCK_GOODS_IMPORT_DOC_NO = "stock_goods_import_doc_no";
    const RM_DOC_NO = "rm_doc_no";
    const RO_DOC_NO = "ro_doc_no";
    const FG_DOC_NO = "fg_doc_no";
    const INV_CUST_DOC_NO = "inv_cust";
    const TYPE_PR = "purchase.request";
    const STOCK_COUNT = "stock_count_doc_no";
    
    public static function genDocNo($code){
        $resDocSeq = ResDocSequence::findOne(['code'=>$code]);
        $doc_no = DocSequenceHelper::generateDocNo($resDocSeq);
        return $doc_no;
    }
    
    public static function genDocNoByCodePreview($code){
        $resDocSeq = ResDocSequence::findOne(['code'=>$code]);
        $doc_no = DocSequenceHelper::generateDocNo($resDocSeq,false);
        return $doc_no;
    }
    
    public static function genDocNoById($id){
        $resDocSeq = ResDocSequence::findOne(['id'=>$id]);
        $doc_no = DocSequenceHelper::generateDocNo($resDocSeq);
        return $doc_no;
    }
    
    public static function genDocNoPreview($doctype){
        $resDocSeq = ResDocSequence::findOne(['name'=>$doctype]);
        $doc_no = DocSequenceHelper::generateDocNo($resDocSeq,false);
        return $doc_no;
    }
    
    public static function genDocNoByIdPreview($id){
        $resDocSeq = ResDocSequence::findOne(['id'=>$id]);
        $doc_no = DocSequenceHelper::generateDocNo($resDocSeq,false);
        return $doc_no;
    }
    
    public static function listDocSeq($doc_type,$group_id=null){
        return ResDocSequence::find()->byType($doc_type,$group_id)->asArray()->all();
    }
    
    public static function listDocSeqForUser($doc_type,$user_id){
        return ResDocSequence::find()->byTypeForUser($doc_type, $user_id)->asArray()->all();
    }
    
    public static function listPrDocSeqForUser($user_id){
        return ResDocSequence::find()->byTypePrForUser($user_id)->asArray()->all();
    }
    
    public static function getSequenceByCode($code){
        return ResDocSequence::findOne(['code'=>$code]);
    }
    
    public static function getSequenceByType($type){
        $seq = ResDocSequence::findOne(['type'=>$type]);
        if(!$seq){
            throw new \Exception("Sequence type=".$type." Not found");
        }
        return DocSequenceHelper::generateDocNo($seq);
    }
    
    private static function generateDocNo(&$resDocSeq,$save=true){
        if(!$resDocSeq){
            throw new \Exception('ไม่พบรหัสเอกสาร โปรดตั้งค่ารหัสเอกสาร');
        }
        $prefix = $resDocSeq->prefix;
        $t = new \DateTime('now');
        if($resDocSeq->era && $resDocSeq->era == 'be'){
            $t->setDate($t->format('Y')+543, $t->format('m'), $t->format('d'));
        }
        $date_include = $t->format($resDocSeq->date_format);
        $running_length = $resDocSeq->running_length;
        $counter = $resDocSeq->counter;
        $suffix = str_pad($counter, $running_length, "0",STR_PAD_LEFT);
        $doc_no = $prefix.$date_include.$suffix;
        $resDocSeq->counter += 1;
        if($save){
            $resDocSeq->save(false);
        }
        return $doc_no;
    }
}
