<?php
namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

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
class Page extends \yii\db\ActiveRecord
{
    const INDEX = 'index';
    const LOGIN = 'login';
    const SIGNUP = 'signup';
    const FEEDBACK = 'feedback';
    const ABOUT = 'about';

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
        return 'page';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя (url)',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'keywords' => 'Ключевые слова',
            'content' => 'Контент',
            'createdAt' => 'Создана',
            'updatedAt' => 'Обновлена',
        ];
    }

    /**
     * {@inheritdoc}
     * @return PageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PageQuery(get_called_class());
    }
}
