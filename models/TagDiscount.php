<?php
namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tag_discount".
 *
 * @property int $tagId
 * @property int $discountId
 *
 * @property Discount $discount
 * @property Tag $tag
 */
class TagDiscount extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'tag_discount';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['tagId', 'discountId'], 'required'],
            [['tagId', 'discountId'], 'integer'],
            [['tagId', 'discountId'], 'unique', 'targetAttribute' => ['tagId', 'discountId']],
            [['discountId'], 'exist', 'skipOnError' => true, 'targetClass' => Discount::className(), 'targetAttribute' => ['discountId' => 'id']],
            [['tagId'], 'exist', 'skipOnError' => true, 'targetClass' => Tag::className(), 'targetAttribute' => ['tagId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'tagId' => 'Tag ID',
            'discountId' => 'Discount ID',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getDiscount(): ActiveQuery
    {
        return $this->hasOne(Discount::class, ['id' => 'discountId']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTag(): ActiveQuery
    {
        return $this->hasOne(Tag::class, ['id' => 'tagId']);
    }
}
