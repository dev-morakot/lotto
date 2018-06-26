<?php

namespace app\modules\resource\models;

/**
 * This is the ActiveQuery class for [[ResAttach]].
 *
 * @see ResAttach
 */
class ResAttachQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ResAttach[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ResAttach|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
