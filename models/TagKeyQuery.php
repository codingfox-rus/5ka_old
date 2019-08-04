<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TagKey]].
 *
 * @see TagKey
 */
class TagKeyQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TagKey[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TagKey|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
