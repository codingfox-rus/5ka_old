<?php
namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "discount".
 *
 * @property int $id
 * @property string $market
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
 * @property int $updatedAt
 *
 * @property Location $location
 * @property Product $product
 * @property Stat $stat
 * @property string $smallPreview
 * @property string $bigPreview
 */
class Discount extends \yii\db\ActiveRecord
{
    public const STAT_PRICE_LIMIT = 49.99;

    const FIVE_SHOP = 'five_shop';
    const MAGNIT = 'magnit';
    const BRISTOL = 'bristol';

    /**
     * @return array
     */
    public static function getMarkets()
    {
        return [
            self::FIVE_SHOP => 'Пятерочка',
            self::MAGNIT => 'Магнит',
            self::BRISTOL => 'Бристоль',
        ];
    }

    /**
     * @return array
     */
    public static function getMarketUrls()
    {
        return [
            self::FIVE_SHOP => 'https://5ka.ru',
            self::MAGNIT => 'http://magnit-info.ru',
            self::BRISTOL => 'https://bristol.ru',
        ];
    }

    /**
     * @return array
     */
    public static function getMarketClasses(): array
    {
        return [
            self::FIVE_SHOP => \app\components\markets\FiveShop::class,
            //self::BRISTOL => \app\components\markets\Bristol::class,
        ];
    }

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
    /*public function formName(): string
    {
        return '';
    }*/

    /**
     * @return array
     */
    public static function getDataColumns()
    {
        return [
            'market',
            'locationId',
            'productId',
            'productName',
            'regularPrice',
            'specialPrice',
            'discountPercent',
            'dateStart',
            'dateEnd',
            'jsonData',
            'status',
            'createdAt',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
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
    public static function tableName()
    {
        return 'discount';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'locationId',
                'productId',
                'dateStart',
                'dateEnd',
                'status',
                'createdAt',
                'updatedAt',
            ], 'integer'],

            ['status', 'default', 'value' => 1],

            [[
                'market',
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

            [['market'], 'string', 'max' => 32],

            [[
                'productName',
            ], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'market' => 'Поставщик',
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
            'updatedAt' => 'Обновлена',
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
     * Крупное превью
     * @return string
     */
    public function getBigPreview(): string
    {
        //$siteUrl = self::getMarketUrls()[$this->market];

        if ($this->product) {

            if ($this->product->previewBig !== null) {

                return Yii::getAlias('@web') . $this->product->previewBig;
            }

            return $this->product->imageBig;
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
