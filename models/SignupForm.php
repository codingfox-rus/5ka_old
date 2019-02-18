<?php
namespace app\models;

use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    const TIME_FOR_ACTIVATION = 3600;

    public $email;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['email'], 'required', 'message' => 'Поле обязательно для заполнения'],
            [['email'], 'email'],
        ];
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function signup(): bool
    {
        if ($this->validate()) {

            $password = Yii::$app->security->generateRandomString(6);
            $activationCode = Yii::$app->security->generateRandomString(64);

            $user = new User;
            $user->email = $this->email;
            $user->password = Yii::$app->security->generatePasswordHash($password);
            $user->activationCode = $activationCode;
            $user->activationRequestAt = time();
            $user->status = User::STATUS_UNCONFIRMED;

            if ($user->save()) {

                $auth = Yii::$app->authManager;
                $clientRole = $auth->getRole('client');
                $auth->assign($clientRole, $user->id);

                $from = Yii::$app->params['smtp']['username'];

                Yii::$app->mailer
                    ->compose('user-activation', compact('password', 'activationCode'))
                    ->setFrom($from)
                    ->setTo($user->email)
                    ->setSubject('Подтверждение регистрации на сайте FollowSale')
                    ->send();

                return true;
            }

            Yii::error(implode('. ', $user->errors));
        }

        return false;
    }
}