<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "market".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $logo
 */
class Market extends ActiveRecord
{
    public const LOGO_PATH = 'img/markets-logos/';

    /** @var UploadedFile */
    public $logoImage;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'market';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['code', 'name'], 'required', 'message' => 'Поле обязательно для заполнения'],
            [['code'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 64],
            [['logo'], 'string', 'max' => 255],
            [['logoImage'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'code' => 'Код',
            'name' => 'Имя',
            'logo' => 'Лого',
        ];
    }

    /**
     * {@inheritdoc}
     * @return MarketQuery the active query used by this AR class.
     */
    public static function find(): MarketQuery
    {
        return new MarketQuery(static::class);
    }

    /**
     * @return bool
     */
    public function upload(): bool
    {
        if ($this->validate()) {

            $path = self::LOGO_PATH . strtolower($this->code) .'.'. $this->logoImage->extension;

            if ($this->logoImage->saveAs($path)) {

                $this->logo = $path;
            }

            return true;
        }

        return false;
    }
}
