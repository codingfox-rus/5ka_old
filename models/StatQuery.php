<?php
namespace app\models;

use yii\db\ActiveQuery;

/**
 * Class StatQuery
 * @package app\models
 */
class StatQuery extends ActiveQuery
{
    /**
     * @param int $id
     * @return StatQuery
     */
    public function location(int $id): StatQuery
    {
        return $this->andWhere([
            'locationId' => $id
        ]);
    }

    /**
     * @return StatQuery
     */
    public function withData(): StatQuery
    {
        return $this->andWhere([
            'not', ['data' => null]
        ]);
    }

    /**
     * {@inheritdoc}
     * @return Stat[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Stat|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
