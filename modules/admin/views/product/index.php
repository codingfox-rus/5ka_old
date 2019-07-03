<?php
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Product;

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-index">
    <h4><?= $this->title ?></h4>
    <br>

    <?= GridView::widget([
        'filterModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'label' => 'Превью',
                'format' => 'html',
                'content' => function (Product $model) {
                    if ($model->preview) {
                        return Html::img($model->preview, [
                            'width' => '200',
                        ]);
                    }

                    return '-';
                }
            ],
            'id',
            'name',
        ]
    ]) ?>
</div>
