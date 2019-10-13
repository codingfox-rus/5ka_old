<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\User;

class RbacController extends Controller
{
    public const ROLE_ADMIN = 'admin';
    public const ROLE_CLIENT = 'client';

    /** @var \yii\rbac\DbManager */
    public $auth;

    public function init()
    {
        $this->auth = Yii::$app->authManager;
    }

    public function actionInit()
    {
        $admin = $this->auth->createRole(self::ROLE_ADMIN);
        $admin->description = 'Администратор';
        $this->auth->add($admin);

        $client = $this->auth->createRole(self::ROLE_CLIENT);
        $client->description = 'Клиент';
        $this->auth->add($client);

        $this->auth->addChild($admin, $client);
    }

    public function actionCreateUser($email, $password)
    {
        $user = new User();

        $user->email = $email;

        $user->password = Yii::$app->security->generatePasswordHash($password);

        $user->status = User::STATUS_ACTIVE;

        if ($user->save(false)) {
            echo $user->id . PHP_EOL;
        }
    }

    public function actionAssignUser($roleName, $userId)
    {
        $role = $this->auth->getRole($roleName);

        $this->auth->assign($role, $userId);
    }
}