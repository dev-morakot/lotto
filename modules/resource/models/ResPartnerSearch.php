<?php

namespace app\modules\resource\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\resource\models\ResPartner;

/**
 * ResPartnerSearch represents the model behind the search form about `app\modules\resource\models\ResPartner`.
 */
class ResPartnerSearch extends ResPartner
{
    public $company;
    public $province_id;
    public $address1;
    public $address2;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'supplier', 'customer', 'is_company', 'employee', 'active','province_id'], 'integer'],
            [['name','code', 'display_name', 'comment', 'email', 'tax_no', 'phone', 'mobile', 'type', 'function'], 'safe'],
            [['company'],'safe'],
            [['address1','address2'],'safe']
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
        $query = ResPartner::find()->alias('a');

        $query->joinWith(['company']);
        $query->joinWith(['defaultAddress as b']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>[
                'defaultOrder'=>['code'=>SORT_ASC],
                'attributes'=>[
                    'code',
                    'name',
                    'company' => [
                        'asc' =>['company.name'=>SORT_ASC],
                        'desc'=>['company.name'=>SORT_DESC]
                        ],
                    'tax_no',
                    'address1',
                    'address2',
                    'active',
                    'customer',
                    'supplier'
                    ]
                    
                ]
                
        ]);

//        $dataProvider->setSort([
//            'attributes'=>[
//                'name',
//                'company' => [
//                    'asc' =>['company.name'=>SORT_ASC],
//                    'desc'=>['company.name'=>SORT_DESC]
//                ]
//            ]
//        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'a.id' => $this->id,
            'a.parent_id' => $this->parent_id,
            'a.supplier' => $this->supplier,
            'a.customer' => $this->customer,
            'a.is_company' => $this->is_company,
            'a.employee' => $this->employee,
            'a.active' => $this->active,
            'b.province_id'=>$this->province_id
        ]);

        $query->andFilterWhere(['like', 'a.name', $this->name])
            ->andFilterWhere(['like', 'a.code', $this->code])
            ->andFilterWhere(['like', 'a.display_name', $this->display_name])
            ->andFilterWhere(['like', 'a.comment', $this->comment])
            ->andFilterWhere(['like', 'b.address1', $this->address1])
            ->andFilterWhere(['like', 'b.address2', $this->address2])
            ->andFilterWhere(['like', 'a.email', $this->email])
            ->andFilterWhere(['like', 'a.tax_no', $this->tax_no])
            ->andFilterWhere(['like', 'a.phone', $this->phone])
            ->andFilterWhere(['like', 'a.mobile', $this->mobile])
            ->andFilterWhere(['like', 'a.type', $this->type])
            ->andFilterWhere(['like', 'a.function', $this->function])
            ->andFilterWhere(['like','company.name',$this->company]);

        return $dataProvider;
    }
}
