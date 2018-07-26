<?php

namespace app\modules\resource\models;

/**
 * This is the ActiveQuery class for [[ResRestraints]].
 *
 * @see ResRestraints
 */
class ResRestraintsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ResRestraints[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ResRestraints|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
