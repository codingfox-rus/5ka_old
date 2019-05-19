<?php
/* @var $ip string */
/* @var $city string */
/* @var $locationId int */

use yii\helpers\Html;
?>

<div class="text-center">
    Ваш город:
    <?= Html::a($city, '#', [
        'class' => 'change-city',
        'data-toggle' => 'modal',
        'data-target' => '#modalSelectCity',
    ]) ?>
</div>
