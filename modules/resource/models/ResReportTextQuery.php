<?php

namespace app\modules\resource\models;

/**
 * This is the ActiveQuery class for [[ResReportText]].
 *
 * @see ResReportText
 */
class ResReportTextQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['active'=>true]);
    }

    /**
     * @inheritdoc
     * @return ResReportText[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ResReportText|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
