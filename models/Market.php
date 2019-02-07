<?php
namespace app\models;

use Yii;

/**
 * This is the model class for table "market".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $logo
 */
class Market extends \yii\db\ActiveRecord
{
    const LOGO_PATH = 'img/markets-logos/';

    /** @var \yii\web\UploadedFile */
    public $logoImage;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'market';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
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
    public function attributeLabels()
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
    public static function find()
    {
        return new MarketQuery(get_called_class());
    }

    /**
     * @return bool
     */
    public function upload()
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
