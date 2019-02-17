<?php
namespace app\commands;

use Yii;
use yii\web\Controller;

class RbacController extends Controller
{
    /** @var \yii\rbac\DbManager */
    public $auth;

    public function init()
    {
        $this->auth = Yii::$app->authManager;
    }

    public function actionInit()
    {

    }
}