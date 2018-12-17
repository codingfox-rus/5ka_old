<?php
namespace app\models;

/**
 * This is the ActiveQuery class for [[DiscountFiveShop]].
 *
 * @see DiscountFiveShop
 */
class DiscountFiveShopQuery extends \yii\db\ActiveQuery
{
    /**
     * @return DiscountFiveShopQuery
     */
    public function noCategory() : DiscountFiveShopQuery
    {
        return $this->andWhere([
            'categoryId' => null,
        ]);
    }

    /**
     * @return DiscountFiveShopQuery
     */
    public function actual() : DiscountFiveShopQuery
    {
        return $this->andWhere([
            'and',
            ['<=', 'dateStart', time()],
            ['>=', 'dateEnd', time()],
        ]);
    }

    /**
     * {@inheritdoc}
     * @return DiscountFiveShop[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return DiscountFiveShop|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
