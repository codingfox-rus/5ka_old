<?php
namespace app\models;

use yii\db\ActiveQuery;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "stat".
 *
 * @property int $locationId
 * @property int $productId
 * @property array $data
 * @property int $nextTurnAt
 * @property int $createdAt
 * @property int $updatedAt
 *
 * @property Location $location
 * @property Product $product
 */
class Stat extends \yii\db\ActiveRecord
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'stat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['locationId', 'productId'], 'required'],
            [['locationId', 'productId', 'nextTurnAt', 'createdAt', 'updatedAt'], 'integer'],
            [['data'], 'safe'],
            [['locationId', 'productId'], 'unique', 'targetAttribute' => ['locationId', 'productId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'locationId' => 'Локация',
            'productId' => 'Продукт',
            'data' => 'Данные',
            'nextTurnAt' => 'Следующая проверка',
            'createdAt' => 'Инициировано',
            'updatedAt' => 'Обновлено',
        ];
    }

    /**
     * {@inheritdoc}
     * @return StatQuery the active query used by this AR class.
     */
    public static function find(): StatQuery
    {
        return new StatQuery(static::class);
    }

    /**
     * @return ActiveQuery
     */
    public function getLocation(): ActiveQuery
    {
        return $this->hasOne(Location::class, ['id' => 'locationId'])->from(Location::tableName() .' sl');
    }

    /**
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getRegion(): ActiveQuery
    {
        return $this->hasOne(Region::class, ['id' => 'regionId'])
            ->viaTable('location', ['id' => 'locationId']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProduct(): ActiveQuery
    {
        return $this->hasOne(Product::class, ['id' => 'productId']);
    }
}
