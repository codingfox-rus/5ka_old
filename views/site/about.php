<?php
/* @var $this yii\web\View */
/* @var $content string */

use yii\helpers\Html;

if (empty($this->title)) {
    $this->title = 'О проекте';
}
?>

<div class="site-about">
    <?= Html::encode($content) ?>
</div>
