<?php

namespace app\modules\resource\models;

/**
 * This is the ActiveQuery class for [[ResCountry]].
 *
 * @see ResCountry
 */
class ResCountryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ResCountry[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ResCountry|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
