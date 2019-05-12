<?php
namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "discount".
 *
 * @property int $id
 * @property int $categoryId
 * @property string $market
 * @property int $locationId
 * @property int $productId
 * @property string $productName
 * @property string $url
 * @property string $description
 * @property string $condition
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
 * @property Category $category
 * @property Location $location
 * @property Product $product
 * @property string $smallPreview
 * @property string $bigPreview
 */
class Discount extends \yii\db\ActiveRecord
{
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
            'url',
            'description',
            'regularPrice',
            'specialPrice',
            'discountPercent',
            'dateStart',
            'dateEnd',
            //'jsonData',
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
                'categoryId',
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
                'description',
                'condition',
            ], 'string'],

            [[
                'regularPrice',
                'specialPrice',
                'discountPercent',
            ], 'number'],

            [['jsonData'], 'safe'],

            [['market'], 'string', 'max' => 32],

            [[
                'productName',
                'url',
            ], 'string', 'max' => 255],

            [['categoryId'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['categoryId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categoryId' => 'Категория',
            'market' => 'Поставщик',
            'locationId' => 'Локация',
            'productId' => 'Товара',
            'productName' => 'Наименование',
            'url' => 'Url',
            'description' => 'Описание',
            'condition' => 'Условия скидки',
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
     * @return \yii\db\ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'categoryId']);
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
        //$siteUrl = self::getMarketUrls()[$this->market];

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
}
