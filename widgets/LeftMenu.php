<?php
namespace app\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use app\models\Location;

class LeftMenu extends Widget
{
    /** @var array */
    public $locations;

    public function init()
    {
        $this->locations = ArrayHelper::map(
            Location::find()->enabled()->asArray()->all(),
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