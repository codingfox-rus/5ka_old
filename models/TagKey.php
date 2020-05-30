<?php
namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tag_key".
 *
 * @property int $id
 * @property int $tagId
 * @property string $key
 * @property int $updatedAt
 */
class TagKey extends ActiveRecord
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => 'updatedAt'
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'tag_key';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['tagId', 'key'], 'required'],
            [['tagId', 'updatedAt'], 'integer'],
            [['key'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'tagId' => 'Тег',
            'key' => 'Ключ',
            'updatedAt' => 'Обновлено',
        ];
    }

    /**
     * {@inheritdoc}
     * @return TagKeyQuery the active query used by this AR class.
     */
    public static function find(): TagKeyQuery
    {
        return new TagKeyQuery(static::class);
    }
}
