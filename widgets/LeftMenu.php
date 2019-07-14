<?php
namespace app\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use app\models\Location;

class LeftMenu extends Widget
{
    public const DEFAULT_REGION_ID = 35; //Чувашляндия

    /** @var array */
    public $locations;

    public function init()
    {
        $this->locations = ArrayHelper::map(
            Location::find()->region(self::DEFAULT_REGION_ID)->asArray()->all(),
            'id',
            'name'
        );
    }

    /**
     * @return string
     */
    public function run(): string
    {
        return $this->render('left-menu', [
            'locations' => $this->locations,
        ]);
    }
}