<?php
namespace app\models;

use yii\data\ActiveDataProvider;

class DiscountSearch extends Discount
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [[
                'productName',
            ], 'string']
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params) : ActiveDataProvider
    {
        $query = Discount::find()->orderBy('id desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->productName]);

        return $dataProvider;
    }
}