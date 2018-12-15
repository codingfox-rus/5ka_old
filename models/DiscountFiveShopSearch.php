<?php
namespace app\models;

use yii\data\ActiveDataProvider;

class DiscountFiveShopSearch extends DiscountFiveShop
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [[
                'name',
            ], 'string']
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params) : ActiveDataProvider
    {
        $query = DiscountFiveShop::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}