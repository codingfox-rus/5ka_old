<?php
namespace app\models;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "region".
 *
 * @property int $id
 * @property string $name
 * @property int $capitalLocationId
 * @property int $updatedAt
 *
 * @property Location[] $locations
 * @property Location $capitalLocation
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

            [[
                'id',
                'capitalLocationId',
                'updatedAt',
            ], 'integer'],

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
            'capitalLocationId' => 'Столица региона',
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

    /**
     * @return ActiveQuery
     */
    public function getCapitalLocation(): ActiveQuery
    {
        return $this->hasOne(Location::class, ['id' => 'capitalLocationId']);
    }
}
