<?php
namespace app\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use app\models\Location;

class CityChange extends Widget
{
    /** @var array */
    public $locations;

    public function init()
    {
        $this->locations = ArrayHelper::map(Location::find()->asArray()->all(), 'id', 'name');
    }

    /**
     * @return string
     */
    public function run(): string
    {
        return $this->render('city-change', [
            'locations' => $this->locations,
        ]);
    }
}