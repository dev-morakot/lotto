<?php

namespace app\modules\resource\models;
use yii\db\Query;
/**
 * This is the ActiveQuery class for [[ResDocSequence]].
 *
 * @see ResDocSequence
 */
class ResDocSequenceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ResDocSequence[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ResDocSequence|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    
    public function byType($type,$usergroup_id=null){
        $query = $this;
        $query->leftJoin('res_group_docseq_rel b','b.docseq_id=id')
                ->andWhere(['type'=>$type]);
        if($usergroup_id){
            $query->andWhere(['b.group_id'=>$usergroup_id]);
        }
        return $query->distinct();
    }
    
    public function byTypeForUser($type,$user_id){
        $query = $this;
        $query->leftJoin('res_group_docseq_rel b','b.docseq_id=id')
                ->leftJoin('res_users_group_rel c','b.group_id=c.group_id')
                ->andWhere(['type'=>$type]);
        if($user_id){
            $query->andWhere(['c.user_id'=>$user_id]);
        }
        return $query->distinct();
    }
    
    /**
     * ทุก sequence ของ user
     * @param type $user_id
     * @return type
     */
    public function byTypePrForUser($user_id){
        $query = $this->alias('c');
        $query->leftJoin('res_group a','a.pr_sequence_id = c.id')
                ->leftJoin('res_users_group_rel b','b.group_id = a.id')
                ->andWhere(['type'=>'purchase.request'])
                ->andWhere(['b.user_id'=>$user_id]);
        return $query->distinct();
    }
}
