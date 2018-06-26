<?php

namespace app\modules\resource\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\resource\models\ResDocSequence;

/**
 * ResDocSequenceSearch represents the model behind the search form about `app\modules\resource\models\ResDocSequence`.
 */
class ResDocSequenceSearch extends ResDocSequence
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'running_length', 'counter'], 'integer'],
            [['name', 'prefix', 'date_format','type'], 'safe'],
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
        $query = ResDocSequence::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'running_length' => $this->running_length,
            'counter' => $this->counter,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'prefix', $this->prefix])
            ->andFilterWhere(['like', 'date_format', $this->date_format])
            ->andFilterWhere(['like','type',$this->type]);

        return $dataProvider;
    }
}
