<?php
namespace app\widgets;

use yii\base\Widget;

class LeftMenu extends Widget
{
    /**
     * @return string
     */
    public function run()
    {
        return $this->render('left-menu');
    }
}