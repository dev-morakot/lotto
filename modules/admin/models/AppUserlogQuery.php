<?php

namespace app\modules\admin\models;

/**
 * This is the ActiveQuery class for [[AppUserlog]].
 *
 * @see AppUserlog
 */
class AppUserlogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AppUserlog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AppUserlog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
