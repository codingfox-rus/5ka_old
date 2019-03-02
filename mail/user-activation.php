<?php
/* @var $password string */
/* @var $activationCode string */

use yii\helpers\Url;
use yii\helpers\Html;

$confirmationUrl = Url::base(true) .'/activation?code='. $activationCode;
?>

<div>
    <h4>Подтверждение регистрации на FOLLOWSALE.RU</h4>

    <p>Пароль: <strong><?= $password ?></strong></p>

    <p>Для подтверждения регистрации, перейдите пожалуйста по ссылке:</p>

    <p><?= Html::a($confirmationUrl, $confirmationUrl) ?></p>
</div>