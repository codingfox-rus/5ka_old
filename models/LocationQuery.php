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

    /**
     * @return LocationQuery
     */
    public function enabled(): LocationQuery
    {
        return $this->andWhere([
            'location.isEnabled' => 1
        ]);
    }

    /**
     * @param int|null $id
     * @return LocationQuery
     */
    public function region(int $id = null): LocationQuery
    {
        return $this->andWhere([
            'regionId' => $id
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
