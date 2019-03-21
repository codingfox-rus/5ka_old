<?php
namespace app\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use app\models\Market;

class LeftMenu extends Widget
{
    /** @var array */
    public $markets;

    public function init()
    {
        $this->markets = ArrayHelper::map(Market::find()->asArray()->all(), 'code', 'name');
    }

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('left-menu', [
            'markets' => $this->markets,
        ]);
    }
}