<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[DiscountFiveShop]].
 *
 * @see DiscountFiveShop
 */
class DiscountFiveShopQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

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
