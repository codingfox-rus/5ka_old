<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Discount;

class TestController extends Controller
{
    public function actionMail()
    {
        Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['smtp']['username'])
            ->setTo('olegserebryakoff@mail.ru')
            ->setSubject('Teст')
            ->setTextBody('Тест нахуй')
            ->send();
    }

    public function actionTest()
    {
        echo Yii::$app->params['adminEmail'];
    }


}