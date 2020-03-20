<?php

namespace app\modules\resource\models;

use Yii;
use yii\db\Expression;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class ResDocSequence extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'res_doc_sequence';
    }


}
