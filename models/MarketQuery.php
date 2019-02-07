<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Market]].
 *
 * @see Market
 */
class MarketQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Market[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Market|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
