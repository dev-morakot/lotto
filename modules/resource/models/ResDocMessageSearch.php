<?php

namespace app\modules\resource\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\resource\models\ResDocMessage;

/**
 *
 */
class ResDocMessageSearch extends ResDocMessage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ref_id'], 'integer'],
            [['name', 'message'], 'safe'],
            [['name','message'],'string'],
            [['create_date'],'datetime']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ResDocMessage::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>['defaultOrder'=>['create_date'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'ref_id' => $this->ref_id
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
