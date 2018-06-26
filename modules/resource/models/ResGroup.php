<?php

namespace app\modules\resource\models;

use Yii;
use app\modules\resource\models\ResDocSequence;
/**
 * This is the model class for table "res_group".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $pr_sequence_id
 * @property integer $create_uid
 * @property string $create_date
 * @property integer $write_uid
 * @property string $write_date
 *
 * @property ResGroupDocseqRel[] $resGroupDocseqRels
 * @property ResUsers[] $resUsers
 */
class ResGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'res_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pr_sequence_id','create_uid', 'write_uid'], 'integer'],
            [['create_date', 'write_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Group name'),
            'code' => Yii::t('app', 'Group code'),
            'create_uid' => Yii::t('app', 'Create Uid'),
            'create_date' => Yii::t('app', 'Create Date'),
            'write_uid' => Yii::t('app', 'Write Uid'),
            'write_date' => Yii::t('app', 'Write Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResUsers()
    {
        return $this->hasMany(ResUsers::className(), ['id' => 'user_id'])
                ->viaTable('res_users_group_rel', ['group_id'=>'id']);
    }
        
    public function getDocSequences(){
        return $this->hasMany(ResDocSequence::className(), ['id'=>'docseq_id'])
                ->viaTable('res_group_docseq_rel', ['group_id'=>'id']);
    }
    
    public function getSequence(){
        return $this->hasOne(ResDocSequence::className(), ['id'=>'pr_sequence_id']);
    }
    
    /**
     * @inheritdoc
     * @return ResGroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ResGroupQuery(get_called_class());
    }
}
