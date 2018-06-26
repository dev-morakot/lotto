<?php

namespace app\modules\resource\models;

use Yii;

/**
 * This is the model class for table "res_report_text".
 *
 * @property integer $id
 * @property string $name
 * @property string $doc_type
 * @property string $content
 */
class ResReportText extends \yii\db\ActiveRecord
{
    const PO = "po";
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'res_report_text';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['name'], 'string', 'max' => 30],
            [['doc_type'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Text name'),
            'doc_type' => Yii::t('app', 'Text for document type [po,so]'),
            'content' => Yii::t('app', 'Content'),
        ];
    }

    /**
     * @inheritdoc
     * @return ResReportTextQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ResReportTextQuery(get_called_class());
    }
}
