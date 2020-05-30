<?php
namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "location".
 *
 * @property int $id
 * @property int $regionId
 * @property string $name
 * @property int $isEnabled
 * @property int $needToProcess
 * @property int $dataUpdatedAt
 *
 * @property Region $region
 */
class Location extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'location';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'regionId', 'name'], 'required'],

            [[
                'id',
                'regionId',
                'isEnabled',
                'needToProcess',
                'dataUpdatedAt',
            ], 'integer'],

            [['name'], 'string', 'max' => 255],
            [['id', 'regionId'], 'unique', 'targetAttribute' => ['id', 'regionId']],
            [['regionId'], 'exist', 'skipOnError' => true, 'targetClass' => Region::class, 'targetAttribute' => ['regionId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'regionId' => 'Регион',
            'name' => 'Наименование',
            'isEnabled' => 'Включен сбор статистики',
            'needToProcess' => 'Необходимость сбора статистики',
            'dataUpdatedAt' => 'Время обновления данных',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getRegion(): ActiveQuery
    {
        return $this->hasOne(Region::class, ['id' => 'regionId']);
    }

    /**
     * {@inheritdoc}
     * @return LocationQuery the active query used by this AR class.
     */
    public static function find(): LocationQuery
    {
        return new LocationQuery(static::class);
    }
}
