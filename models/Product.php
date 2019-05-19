<?php
namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property int $pId
 * @property string $name
 * @property string $imageSmall
 * @property string $imageBig
 * @property string $previewSmall
 * @property string $previewBig
 * @property int $createdAt
 * @property int $updatedAt
 *
 * @property Discount[] $discounts
 */
class Product extends \yii\db\ActiveRecord
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
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pId', 'name'], 'required'],
            [['pId', 'createdAt', 'updatedAt'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['imageSmall', 'imageBig', 'previewSmall', 'previewBig'], 'string', 'max' => 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pId' => 'ID продукта',
            'name' => 'Наименование',
            'imageSmall' => 'Картинка малая',
            'imageBig' => 'Картинка большая',
            'previewSmall' => 'Превью малое',
            'previewBig' => 'Превью большое',
            'createdAt' => 'Добавлен',
            'updatedAt' => 'Обновлен',
        ];
    }

    /**
     * {@inheritdoc}
     * @return ProductQuery the active query used by this AR class.
     */
    public static function find(): ProductQuery
    {
        return new ProductQuery(static::class);
    }

    /**
     * @return ActiveQuery
     */
    public function getDiscounts(): ActiveQuery
    {
        return $this->hasMany(Discount::class, ['productId' => 'pId']);
    }
}