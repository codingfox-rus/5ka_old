<?php
namespace app\models;

use yii\data\ActiveDataProvider;

class LocationSearch extends Location
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [[
                'id',
                'regionId',
            ], 'integer'],

            [['name'], 'string']
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Location::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'regionId' => $this->regionId,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}