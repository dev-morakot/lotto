<?php

namespace app\modules\resource\models;

use Yii;
use app\modules\resource\models\ResLottoDefault;
/**
 * This is the ActiveQuery class for [[ResLottoDefault]].
 *
 * @see ResLottoDefault
 */
class ResLottoDefaultQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ResLottoDefault[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ResLottoDefault|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function current(){
        return $this->andWhere(['id' => Yii::$app->params['res_lotto_default']]);
    }
}
