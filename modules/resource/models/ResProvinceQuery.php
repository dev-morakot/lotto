<?php

namespace app\modules\resource\models;

/**
 * This is the ActiveQuery class for [[ResProvince]].
 *
 * @see ResProvince
 */
class ResProvinceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ResProvince[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ResProvince|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
