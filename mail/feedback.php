<?php
/* @var $model app\models\Feedback */

use yii\helpers\Html;
?>

<div>
    <p><strong>Имя:</strong> <?= Html::encode($model->name) ?></p>

    <p><strong>Email:</strong> <?= Html::encode($model->email) ?></p>

    <?php if ($model->subject) { ?>

        <p><strong>Тема:</strong> <?= Html::encode($model->subject) ?></p>

    <?php } ?>

    <p><strong>Сообщение:</strong></p>

    <p>
        <?= Html::encode($model->message) ?>
    </p>
</div>
