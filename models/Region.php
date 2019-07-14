<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "region".
 *
 * @property int $id
 * @property string $name
 * @property int $updatedAt
 *
 * @property Location[] $locations
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'region';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'name'], 'required'],

            [['id', 'updatedAt'], 'integer'],

            [['name'], 'string', 'max' => 255],

            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'updatedAt' => 'Данные обновлены',
        ];
    }

    /**
     * {@inheritdoc}
     * @return RegionQuery the active query used by this AR class.
     */
    public static function find(): RegionQuery
    {
        return new RegionQuery(static::class);
    }

    /**
     * @return ActiveQuery
     */
    public function getLocations(): ActiveQuery
    {
        return $this->hasMany(Location::class, ['regionId' => 'id'])
            ->orderBy(['dataUpdatedAt' => SORT_DESC]);
    }
}
