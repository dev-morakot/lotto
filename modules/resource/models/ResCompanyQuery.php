<?php

namespace app\modules\resource\models;
use yii;
/**
 * This is the ActiveQuery class for [[ResCompany]].
 *
 * @see ResCompany
 */
class ResCompanyQuery extends \yii\db\ActiveQuery
{
    public function current()
    {
        return $this->andWhere(['id'=>Yii::$app->params['company_id']]);
    }

    /**
     * @inheritdoc
     * @return ResCompany[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ResCompany|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
