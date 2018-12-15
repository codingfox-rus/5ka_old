<?php
namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "discount".
 *
 * @property int $id
 * @property int $itemId
 * @property string $name
 * @property string $description
 * @property string $imageSmall
 * @property string $imageBig
 * @property int $paramId
 * @property string $specialPrice
 * @property string $regularPrice
 * @property string $discountPercent
 * @property int $dateStart
 * @property int $dateEnd
 * @property int $createdAt
 * @property int $updatedAt
 */
class DiscountFiveShop extends \yii\db\ActiveRecord
{
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
        return 'discount_five_shop';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['itemId', 'paramId', 'dateStart', 'dateEnd', 'createdAt', 'updatedAt'], 'integer'],
            [['description', 'imageSmall', 'imageBig'], 'string'],
            [['specialPrice', 'regularPrice', 'discountPercent'], 'number'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'itemId' => 'ID записи',
            'name' => 'Наименование',
            'description' => 'Описание',
            'imageSmall' => 'Картинка уменьшенная',
            'imageBig' => 'Картинка большая',
            'paramId' => 'ID параметра',
            'specialPrice' => 'Спец. цена',
            'regularPrice' => 'Обычная цена',
            'discountPercent' => 'Процент скидки',
            'dateStart' => 'Дата начала',
            'dateEnd' => 'Дата окончания',
            'createdAt' => 'Создано',
            'updatedAt' => 'Обновлено',
        ];
    }

    /**
     * {@inheritdoc}
     * @return DiscountFiveShopQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DiscountFiveShopQuery(get_called_class());
    }
}
