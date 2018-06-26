<?php

namespace app\modules\resource\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\resource\models\ResAttach;

/**
 * ResAttachModel represents the model behind the search form about `app\modules\resource\models\ResAttach`.
 */
class ResAttachModel extends ResAttach
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'revision', 'related_id', 'company_id', 'create_uid', 'write_uid'], 'integer'],
            [['name', 'date', 'description', 'file', 'hash', 'origin', 'related_model', 'create_date', 'write_date'], 'safe'],
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
        $query = ResAttach::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,
            'revision' => $this->revision,
            'related_id' => $this->related_id,
            'company_id' => $this->company_id,
            'create_uid' => $this->create_uid,
            'create_date' => $this->create_date,
            'write_uid' => $this->write_uid,
            'write_date' => $this->write_date,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['like', 'hash', $this->hash])
            ->andFilterWhere(['like', 'origin', $this->origin])
            ->andFilterWhere(['like', 'related_model', $this->related_model]);

        return $dataProvider;
    }
}
