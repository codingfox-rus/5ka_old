<?php
namespace app\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Product]].
 *
 * @see Product
 */
class ProductQuery extends ActiveQuery
{
    /**
     * Без превьюх
     * @return ProductQuery
     */
    public function noPreview(): ProductQuery
    {
        return $this->andWhere([
            'previewSmall' => null,
        ]);
    }

    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Product[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Product|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
