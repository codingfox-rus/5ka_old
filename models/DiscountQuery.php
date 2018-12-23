<?php
namespace app\models;

class DiscountQuery extends \yii\db\ActiveQuery
{
    /**
     * @return DiscountQuery
     */
    public function noCategory() : DiscountQuery
    {
        return $this->andWhere([
            'categoryId' => null,
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
     * @return DiscountQuery
     */
    public function active()
    {
        return $this->andWhere([
            'deletedAt' => null,
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
