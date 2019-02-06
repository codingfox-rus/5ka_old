<?php
namespace app\models;


class CategoryQuery extends \yii\db\ActiveQuery
{
    /**
     * @return CategoryQuery
     */
    public function published(): CategoryQuery
    {
        return $this->andWhere([
            'isPublic' => 1
        ]);
    }
}