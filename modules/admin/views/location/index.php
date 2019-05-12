<?php
/* @var $searchModel app\models\LocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $regions array */

use yii\grid\GridView;
use app\models\Location;

$this->title = 'Локации';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="location-index">

    <?= GridView::widget([
        'filterModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'regionId',
                'filter' => $regions,
                'value' => function (Location $model) use ($regions) {
                    return $regions[$model->regionId] ?? '-';
                }
            ],
            'id',
            'name',
        ],
    ]) ?>
</div>
