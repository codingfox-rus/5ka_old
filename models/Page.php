<?php
namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "page".
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $content
 * @property int $createdAt
 * @property int $updatedAt
 */
class Page extends ActiveRecord
{
    public const INDEX      = 'index';
    public const LOGIN      = 'login';
    public const SIGNUP     = 'signup';
    public const FEEDBACK   = 'feedback';
    public const ABOUT      = 'about';

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
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'page';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'title', 'description'], 'required'],
            [['description', 'keywords', 'content'], 'string'],
            [['createdAt', 'updatedAt'], 'integer'],
            [['name', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'            => 'ID',
            'name'          => 'Имя (url)',
            'title'         => 'Заголовок',
            'description'   => 'Описание',
            'keywords'      => 'Ключевые слова',
            'content'       => 'Контент',
            'createdAt'     => 'Создана',
            'updatedAt'     => 'Обновлена',
        ];
    }

    /**
     * {@inheritdoc}
     * @return PageQuery the active query used by this AR class.
     */
    public static function find(): PageQuery
    {
        return new PageQuery(static::class);
    }
}
