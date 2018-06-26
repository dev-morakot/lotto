<?php

namespace app\modules\resource\models;

/**
 * This is the ActiveQuery class for [[ResDistrict]].
 *
 * @see ResDistrict
 */
class ResDistrictQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ResDistrict[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ResDistrict|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
