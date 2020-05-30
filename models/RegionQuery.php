<?php
namespace app\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Region]].
 *
 * @see Region
 */
class RegionQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Region[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Region|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
