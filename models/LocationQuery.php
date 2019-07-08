<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Location]].
 *
 * @see Location
 */
class LocationQuery extends \yii\db\ActiveQuery
{
    /**
     * @return LocationQuery
     */
    public function city(): LocationQuery
    {
        return $this->andWhere([
            'like', 'location.name', 'г. '
        ]);
    }

    public function enabled(): LocationQuery
    {
        return $this->andWhere([
            'location.isEnabled' => 1
        ]);
    }

    /**
     * {@inheritdoc}
     * @return Location[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Location|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
