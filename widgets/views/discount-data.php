<?php
/* @var $model app\models\Discount */
/* @var $siteUrl string */

use yii\widgets\DetailView;
use yii\helpers\Html;
?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'attribute' => 'preview',
            'format' => 'html',
            'value' => Html::img($model->smallPreview, ['class' => 'img-responsive'])
        ],
        'productName',
        'regularPrice',
        'specialPrice',
        'discountPercent',
    ],
]) ?>