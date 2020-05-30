<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "tag".
 *
 * @property int $id
 * @property string $name
 * @property int $updatedAt
 */
class Tag extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['updatedAt'], 'integer'],
            [['name'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'updatedAt' => 'Обновлено',
        ];
    }
}
