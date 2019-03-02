<?php
namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "feedback".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $subject
 * @property string $message
 * @property int $createdAt
 * @property int $updatedAt
 * @property int $sentAt
 */
class Feedback extends \yii\db\ActiveRecord
{
    public $verifyCode;

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
        return 'feedback';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'email', 'message'], 'required'],

            [['email'], 'email'],

            [['message'], 'string'],

            [[
                'createdAt',
                'updatedAt',
                'sentAt',
            ], 'integer'],

            [['name', 'email', 'subject'], 'string', 'max' => 255],

            ['verifyCode', 'captcha'],
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
            'email' => 'Email',
            'subject' => 'Тема',
            'message' => 'Сообщение',
            'createdAt' => 'Добавлено',
            'updatedAt' => 'Обновлено',
            'sentAt' => 'Отправлено админу',
            'verifyCode' => 'Проверочный код',
        ];
    }
}
