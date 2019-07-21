<?php
namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "discount".
 *
 * @property int $id
 * @property int $locationId
 * @property int $productId
 * @property string $productName
 * @property string $regularPrice
 * @property string $specialPrice
 * @property string $discountPercent
 * @property int $dateStart
 * @property int $dateEnd
 * @property array $jsonData
 * @property int status
 * @property int $createdAt
 *
 * @property Location $location
 * @property Product $product
 * @property Stat $stat
 * @property string $smallPreview
 */
class Discount extends \yii\db\ActiveRecord
{
    public const STAT_PRICE_LIMIT = 49.99;

    const STATUS_ACTIVE = 1;
    const STATUS_ARCHIVE = 2;

    /**
     * @return array
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_ACTIVE => 'Актуально',
            self::STATUS_ARCHIVE => 'В архиве',
        ];
    }

    /**
     * @return string
     */
    public function formName(): string
    {
        return '';
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'createdAt',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'discount';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [[
                'locationId',
                'productId',
                'dateStart',
                'dateEnd',
                'status',
                'createdAt',
            ], 'integer'],

            ['status', 'default', 'value' => 1],

            [[
                'productName',
                'regularPrice',
                'specialPrice',
            ], 'required'],

            [[
                'regularPrice',
                'specialPrice',
                'discountPercent',
            ], 'number'],

            [['jsonData'], 'string'],

            [[
                'productName',
            ], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'locationId' => 'Локация',
            'productId' => 'Товара',
            'productName' => 'Наименование',
            'regularPrice' => 'Цена',
            'specialPrice' => 'Цена со скидкой',
            'discountPercent' => 'Процент скидки',
            'dateStart' => 'Дата начала',
            'dateEnd' => 'Дата окончания',
            'jsonData' => 'Данные в JSON',
            'status' => 'Статус',
            'createdAt' => 'Добавлена',
            'preview' => 'Превью',
        ];
    }

    /**
     * {@inheritdoc}
     * @return DiscountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DiscountQuery(get_called_class());
    }

    /**
     * Малое превью
     * @return string
     */
    public function getSmallPreview(): string
    {
        if ($this->product) {

            if ($this->product->previewSmall !== null) {

                return Yii::getAlias('@web') . $this->product->previewSmall;

            }

            return $this->product->imageSmall;
        }

        return '';
    }

    /**
     * @return ActiveQuery
     */
    public function getLocation(): ActiveQuery
    {
        return $this->hasOne(Location::class, ['id' => 'locationId']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProduct(): ActiveQuery
    {
        return $this->hasOne(Product::class, ['pId' => 'productId']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStat(): ActiveQuery
    {
        return $this->hasOne(Stat::class, [
            'locationId' => 'locationId',
            'productId' => 'productId',
        ]);
    }
}
