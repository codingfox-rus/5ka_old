<?php
namespace app\models;

use yii\data\ActiveDataProvider;

class DiscountSearch extends Discount
{
    const DISCOUNT_PER_PAGE = 20;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [[
                'market',
                'categoryId',
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
        $query = Discount::find()
            ->where([
                'status' => Discount::STATUS_ACTIVE
            ])
            ->orderBy('id desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => self::DISCOUNT_PER_PAGE,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->productName]);

        return $dataProvider;
    }
}