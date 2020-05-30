<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $activationCode
 * @property int $activationRequestAt
 * @property string $authKey
 * @property string $accessToken
 * @property int $status
 * @property int $createdAt
 * @property int $updatedAt
 *
 * @property bool $isActive
 * @property bool $isUnconfirmed
 */
class User extends ActiveRecord implements IdentityInterface
{
    public const STATUS_ACTIVE = 10;
    public const STATUS_UNCONFIRMED = 1;
    public const STATUS_DELETED = 0;

    /**
     * @return array
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_UNCONFIRMED => 'Не подтвержден',
            self::STATUS_DELETED => 'Удален',
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
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
            ],
        ];
    }

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'user';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['email'], 'required', 'message' => 'Поле обязательно для заполнения'],
            [['email'], 'email'],

            [[
                'name',
                'email',
                'password',
                'activationCode',
                'authKey',
                'accessToken',
            ], 'string', 'max' => 255],

            [[
                'activationRequestAt',
                'status',
                'createdAt',
                'updatedAt',
            ], 'integer'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'email' => 'Email',
            'password' => 'Пароль',
            'status' => 'Статус',
            'createdAt' => 'Создан',
            'updatedAt' => 'Обновлен',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        return null;
    }

    /**
     * @param $email
     * @return User|null
     */
    public static function findByEmail($email): ?User
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): string
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function getIsUnconfirmed(): bool
    {
        return $this->status === self::STATUS_UNCONFIRMED;
    }
}
