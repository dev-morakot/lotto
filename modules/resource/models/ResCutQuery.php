<?php

namespace app\modules\resource\models;

use Yii;
use app\modules\resource\models\ResCut;
/**
 * This is the ActiveQuery class for [[ResCut]].
 *
 * @see ResCut
 */
class ResCutQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ResCut[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ResCut|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function current(){
        return $this->andWhere(['id' => Yii::$app->params['res_cut']]);
    }
}
