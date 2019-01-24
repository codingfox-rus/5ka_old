<?php
namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "discount".
 *
 * @property int $id
 * @property int $categoryId
 * @property string $market
 * @property string $productName
 * @property string $description
 * @property string $condition
 * @property string $imageSmall
 * @property string $imageBig
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
 * @property string $preview
 */
class Discount extends \yii\db\ActiveRecord
{
    const FIVE_SHOP = 'FIVE_SHOP';
    const MAGNIT = 'MAGNIT';

    /**
     * @return array
     */
    public static function getMarkets()
    {
        return [
            self::FIVE_SHOP => 'Пятерочка',
            self::MAGNIT => 'Магнит',
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
        ];
    }

    /**
     * @return array
     */
    public static function getMarketClasses()
    {
        return [
            self::FIVE_SHOP => \app\components\markets\FiveShop::class,
        ];
    }

    const STATUS_ACTIVE = 1;
    const STATUS_ARCHIVE = 2;

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'Актуально',
            self::STATUS_ARCHIVE => 'В архиве',
        ];
    }

    /**
     * @return array
     */
    public static function getDataColumns()
    {
        return [
            'market',
            'productName',
            'description',
            'imageSmall',
            'imageBig',
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

            [['productName'], 'string', 'max' => 255],

            [[
                'imageSmall',
                'imageBig',
            ], 'string', 'max' => 512],

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
            'productName' => 'Наименование',
            'description' => 'Описание',
            'condition' => 'Условия скидки',
            'imageSmall' => 'Превью малое',
            'imageBig' => 'Превью крупное',
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
    public function getCategory()
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
     * @return string
     */
    public function getPreview() : string
    {
        $siteUrl = self::getMarketUrls()[$this->market];

        return $siteUrl . $this->imageSmall;
    }
}
