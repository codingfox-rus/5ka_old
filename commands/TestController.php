<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

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
}