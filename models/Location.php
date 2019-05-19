<?php
namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "location".
 *
 * @property int $id
 * @property int $regionId
 * @property string $name
 * @property int $dataUpdatedAt
 * @property int $dataHandledAt
 *
 * @property Region $region
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'location';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'regionId', 'name'], 'required'],

            [[
                'id',
                'regionId',
                'dataUpdatedAt',
                'dataHandledAt',
            ], 'integer'],

            [['name'], 'string', 'max' => 255],
            [['id', 'regionId'], 'unique', 'targetAttribute' => ['id', 'regionId']],
            [['regionId'], 'exist', 'skipOnError' => true, 'targetClass' => Region::className(), 'targetAttribute' => ['regionId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'regionId' => 'Регион',
            'name' => 'Наименование',
            'dataUpdatedAt' => 'Данные обновлены',
            'dataHandledAt' => 'Данные обработаны',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion(): ActiveQuery
    {
        return $this->hasOne(Region::className(), ['id' => 'regionId']);
    }

    /**
     * {@inheritdoc}
     * @return LocationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LocationQuery(get_called_class());
    }
}