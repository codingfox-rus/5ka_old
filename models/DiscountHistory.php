<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "discount_history".
 *
 * @property int $id
 * @property int $locationId
 * @property int $productId
 * @property string $productName
 * @property string $regularPrice
 * @property string $specialPrice
 * @property string $discountPercent
 * @property int $dateStart
 * @property int $dateEnd
 * @property string $jsonData
 * @property int $status
 * @property int $createdAt
 */
class DiscountHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'discount_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['locationId', 'productId', 'dateStart', 'dateEnd', 'status', 'createdAt'], 'integer'],
            [['regularPrice', 'specialPrice', 'discountPercent'], 'number'],
            [['jsonData'], 'string'],
            [['productName'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'locationId' => 'Location ID',
            'productId' => 'Product ID',
            'productName' => 'Product Name',
            'regularPrice' => 'Regular Price',
            'specialPrice' => 'Special Price',
            'discountPercent' => 'Discount Percent',
            'dateStart' => 'Date Start',
            'dateEnd' => 'Date End',
            'jsonData' => 'Json Data',
            'status' => 'Status',
            'createdAt' => 'Created At',
        ];
    }
}
