<?php

namespace app\modules\resource\models;

/**
 * This is the ActiveQuery class for [[ResDocLotto]].
 *
 * @see ResDocLotto
 */
class ResDocLottoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ResDocLotto[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ResDocLotto|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
