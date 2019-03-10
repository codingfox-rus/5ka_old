<?php
namespace app\models;

class DiscountQuery extends \yii\db\ActiveQuery
{
    /**
     * @param string $market
     * @return DiscountQuery
     */
    public function market(string $market): DiscountQuery
    {
        return $this->andWhere([
            'market' => $market,
        ]);
    }

    /**
     * @return DiscountQuery
     */
    public function categorized(): DiscountQuery
    {
        return $this->active()->andWhere([
            'not', ['categoryId' => null]
        ]);
    }

    /**
     * @return DiscountQuery
     */
    public function noCategory(): DiscountQuery
    {
        return $this->active()->andWhere([
            'categoryId' => null,
        ]);
    }

    /**
     * Активные скидки
     * @return DiscountQuery
     */
    public function active(): DiscountQuery
    {
        return $this->andWhere([
            'status' => Discount::STATUS_ACTIVE
        ]);
    }

    /**
     * Архивированные скидки
     * @return DiscountQuery
     */
    public function archive(): DiscountQuery
    {
        return $this->andWhere([
            'status' => Discount::STATUS_ARCHIVE
        ]);
    }

    /**
     * Без превьюх
     * @return DiscountQuery
     */
    public function noPreviews(): DiscountQuery
    {
        return $this->andWhere([
            'or',
            ['previewSmall' => null],
            ['previewBig' => null]
        ]);
    }

    /**
     * @return DiscountQuery
     */
    public function actual() : DiscountQuery
    {
        return $this->andWhere([
            'and',
            ['<=', 'dateStart', time()],
            ['>=', 'dateEnd', time()],
        ]);
    }

    /**
     * {@inheritdoc}
     * @return Discount[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Discount|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
