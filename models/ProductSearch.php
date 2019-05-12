<?php
namespace app\models;

use yii\data\ActiveDataProvider;

class ProductSearch extends Product
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [[
                'id',
            ], 'integer'],

            [[
                'name',
            ], 'string']
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Product::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}