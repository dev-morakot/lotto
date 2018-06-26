<?php

namespace app\models\base;

use Yii;
use yii\db\ActiveRecord;
use app\modules\general\models\AuditLog;
use yii\helpers\Json;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AppActiveRecord
 *
 * @author wisaruthk
 */
class AppBaseActiveRecord extends \yii\db\ActiveRecord {
    
    
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        
        // Log changed detail. (Audit Log)
        if(!empty($changedAttributes)){
            $nowattr = [];
            foreach ($changedAttributes as $name=>$val){
                $nowattr[$name] = $this[$name];
            }
            $key = implode(':', [
                self::tableName(),
                isset($this['id'])?$this['id']:"-",
                isset($this['name'])?$this['name']:"-",
            ]);
            
            $db_audit_log = @Yii::$app->params['db_audit_log'];
            
            if($db_audit_log && $db_audit_log=="yes"){
                try {
                    $auditLog = new AuditLog();
                    $auditLog->name = isset($this['name'])?$this['name']:"-";
                    $auditLog->ref_model = self::getTableSchema()->name;
                    $auditLog->ref_id = isset($this['id'])?$this['id']:"-";
                    $auditLog->old_val = Json::encode($changedAttributes);
                    $auditLog->new_val = Json::encode($nowattr);
                    $auditLog->method = "save";
                    $auditLog->save(false);
                } catch (\Exception $ex){
                    Yii::error("Save AuditLog Error Model $key",$ex);
                    // do nothing.
                }
            } else {
                Yii::trace([$key,
                    ['from'=>$changedAttributes],
                    ['to'=>$nowattr]
                ],'audit');
            }
            
        }
    }
    
    /**
     * This method is invoked after deleting a record.
     * The default implementation raises the [[EVENT_AFTER_DELETE]] event.
     * You may override this method to do postprocessing after the record is deleted.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    public function afterDelete()
    {
        parent::afterDelete();
        
        $db_audit_log = @Yii::$app->params['db_audit_log'];
        
        if($db_audit_log && $db_audit_log=="yes"){
            try {
                $auditLog = new AuditLog();
                $auditLog->name = isset($this['name'])?$this['name']:"-";
                $auditLog->ref_model = self::getTableSchema()->name;
                $auditLog->ref_id = isset($this['id'])?$this['id']:"-";
                $auditLog->method = "delete";
                $auditLog->save(false);
            } catch (\Exception $ex){
                Yii::error("Save AuditLog Error Model ",$ex);
                // do nothing.
            }
        } else {
            $key = implode(':', [
                self::tableName(),
                isset($this['id'])?$this['id']:"-",
                isset($this['name'])?$this['name']:"-",
            ]);
            
            Yii::trace([$key,'deleted'],'audit');
        }
    }
    
    /**
     * Get next record.
     * @return ActiveRecord
     */
    public function nextRecord($condition=null){
        $q = self::find()->where(['>','id',$this['id']]);
        if($condition){
            $q->andFilterWhere($condition);
        }
        $next = $q->one();
        if(!$next){
            return null;
        }
        return $next;
    }
    
    /**
     * Get previous record.
     * @return ActiveRecord
     */
    public function prevRecord($condition=null){
        $q = self::find()->where(['<','id',$this['id']])->orderBy('id desc');
        if($condition){
            $q->andFilterWhere($condition);
        }
        $prev = $q->one();
        if(!$prev){
            return null;
        }
        return $prev;
    }
    
}
