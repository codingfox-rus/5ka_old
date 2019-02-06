<?php
namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name
 * @property int $isPublic
 *
 * @property Discount[] $discounts
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],

            [['isPublic'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'isPublic' => 'Опубликовано',
        ];
    }

    /**
     * @return CategoryQuery|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return new CategoryQuery(static::class);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscounts()
    {
        return $this->hasMany(Discount::class, ['categoryId' => 'id']);
    }
}
