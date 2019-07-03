<?php
namespace app\models;

use yii\data\ActiveDataProvider;

class DiscountSearch extends Discount
{
    const DISCOUNT_PER_PAGE = 20;

    const SORTING_PRICE_ASC = 'price-asc';
    const SORTING_PRICE_DESC = 'price-desc';
    const SORTING_PERCENT_DESC = 'percent-desc';

    /** @var array */
    public $markets;

    /**
     * @return array
     */
    public static function getSortingOptions(): array
    {
        return [
            self::SORTING_PRICE_ASC => 'Сначала дешевые',
            self::SORTING_PRICE_DESC => 'Сначала дорогие',
            self::SORTING_PERCENT_DESC => 'Наибольший % скидки',
        ];
    }

    public $sortingOrder;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [[
                'market',
                'productName',
                'sortingOrder',
            ], 'string'],

            [[
                'markets',
            ], 'safe'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params) : ActiveDataProvider
    {
        $query = Discount::find()
            ->active()
            ->orderBy([
                'regularPrice' => SORT_DESC,
                'discountPercent' => SORT_DESC,
            ]);

        $query->joinWith('location');

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

        $query->andFilterWhere([
            'market' => $this->market,
        ]);

        $query->andFilterWhere(['like', 'productName', $this->productName]);

        if ($this->sortingOrder) {
            $query = $this->applySortingOrder($query);
        }

        if ($this->markets) {
            $query->markets($this->markets);
        }

        return $dataProvider;
    }

    /**
     * @param DiscountQuery $query
     * @return DiscountQuery
     */
    public function applySortingOrder(DiscountQuery $query): DiscountQuery
    {
        if ($this->sortingOrder === self::SORTING_PRICE_ASC) {

            $query->orderBy('regularPrice asc');

        } else if ($this->sortingOrder === self::SORTING_PRICE_DESC) {

            $query->orderBy('regularPrice desc');

        } else if ($this->sortingOrder === self::SORTING_PERCENT_DESC) {

            $query->orderBy('discountPercent desc');
        }

        return $query;
    }
}