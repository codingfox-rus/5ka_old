<?php
namespace app\commands;

use Yii;
use yii\base\Exception;
use yii\console\Controller;
use app\models\User;
use yii\rbac\DbManager;

/**
 * Class RbacController
 * @package app\commands
 * @property DbManager $auth
 */
class RbacController extends Controller
{
    public const ROLE_ADMIN = 'admin';
    public const ROLE_CLIENT = 'client';

    public $auth;

    /**
     *
     */
    public function init(): void
    {
        $this->auth = Yii::$app->authManager;
    }

    /**
     * @throws Exception
     */
    public function actionInit(): void
    {
        $admin = $this->auth->createRole(self::ROLE_ADMIN);
        $admin->description = 'Администратор';
        $this->auth->add($admin);

        $client = $this->auth->createRole(self::ROLE_CLIENT);
        $client->description = 'Клиент';
        $this->auth->add($client);

        $this->auth->addChild($admin, $client);
    }

    /**
     * @param $email
     * @param $password
     * @throws Exception
     */
    public function actionCreateUser($email, $password): void
    {
        $user = new User();

        $user->email = $email;

        $user->password = Yii::$app->security->generatePasswordHash($password);

        $user->status = User::STATUS_ACTIVE;

        if ($user->save(false)) {
            echo $user->id . PHP_EOL;
        }
    }

    /**
     * @param $roleName
     * @param $userId
     * @throws \Exception
     */
    public function actionAssignUser($roleName, $userId): void
    {
        $role = $this->auth->getRole($roleName);

        $this->auth->assign($role, $userId);
    }
}