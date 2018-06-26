<?php

namespace app\modules\resource\models;

/**
 * This is the ActiveQuery class for [[ResDocMessage]].
 *
 * @see ResDocMessage
 */
class ResDocMessageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ResDocMessage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ResDocMessage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
