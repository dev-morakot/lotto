<?php

namespace app\modules\resource\models;

/**
 * This is the ActiveQuery class for [[ResCurrency]].
 *
 * @see ResCurrency
 */
class ResCurrencyQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ResCurrency[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ResCurrency|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
