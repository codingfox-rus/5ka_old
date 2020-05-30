<?php
namespace app\commands;

use Yii;
use app\models\User;
use yii\base\Exception;
use yii\console\Controller;

class UserController extends Controller
{
    /**
     * @param string $email
     * @param string $password
     * @return bool
     * @throws Exception
     */
    public function actionSetPassword(string $email, string $password): bool
    {
        $user = User::findOne(['email' => $email]);

        if ($user) {
            $user->password = Yii::$app->security->generatePasswordHash($password);
            if ($user->save(false)) {
                echo 'Пароль успешно установлен'. PHP_EOL;
                return true;
            }
        }

        echo 'Пользователь не найден'. PHP_EOL;
        return false;
    }
}