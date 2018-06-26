<?php

namespace app\modules\resource\models;

/**
 * This is the ActiveQuery class for [[ResAddress]].
 *
 * @see ResAddress
 */
class ResAddressQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ResAddress[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ResAddress|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
