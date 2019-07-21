<?php
namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property int $apiVersion
 * @property int $updatedAt
 */
class Settings extends \yii\db\ActiveRecord
{
    public const API_V1 = 1;
    public const API_V2 = 2;

    /**
     * @return array
     */
    public static function getApiVersions(): array
    {
        return [
            self::API_V1 => 'V1',
            self::API_V2 => 'V2',
        ];
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'updatedAt',
                'updatedAtAttribute' => 'updatedAt',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['apiVersion', 'updatedAt'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'apiVersion' => 'Версия API',
            'updatedAt' => 'Обновлено',
        ];
    }


}
