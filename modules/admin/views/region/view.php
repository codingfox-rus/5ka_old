<?php
/* @var $this yii\web\View */
/* @var $model app\models\Region */
/* @var $stat array */

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\web\YiiAsset;

YiiAsset::register($this); // todo: выяснить, зачем нужно

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Регионы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="region-view">
    <h4><?= Html::encode($this->title) ?></h4>

    <div class="row">
        <div class="col-md-6">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'name',
                ],
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="text-center">
                <h4>Локации</h4>
            </div>
            <div class="clearfix">
                <div class="pull-left">
                    <?= Html::a('Включить все', [
                        '/admin/region/enable-all-locations',
                        'id' => $model->id,
                    ], [
                        'class' => 'btn btn-success btn-xs',
                    ]) ?>
                </div>
                <div class="pull-right">
                    <?= Html::a('Отключить все', [
                        '/admin/region/disable-all-locations',
                        'id' => $model->id,
                    ], [
                        'class' => 'btn btn-danger btn-xs',
                    ]) ?>
                </div>
            </div>
            <br>

            <?= Html::beginForm([
                '/admin/region/toggle-location',
            ], 'post', [
                'class' => 'form-toggle-location',
            ]) ?>

            <?php if ($model->locations) { ?>

                <?php foreach ($model->locations as $location) { ?>

                    <div class="checkbox">
                        <label>
                            <?= Html::checkbox('locations[]', $location->isEnabled, [
                                'value' => $location->id,
                            ]) ?>
                            <?= $location->name ?> (ID <?= $location->id ?>)
                        </label>
                    </div>

                <?php } ?>

            <?php } else { ?>

                <p>Нет ни одной локации</p>

            <?php } ?>
        </div>
        <div class="col-md-6">
            <div class="text-center">
                <h4>Статистика</h4>
            </div>
            <br>

            <table class="table table-bordered table-striped table-condensed">
                <tr>
                    <th>Локация</th>
                    <th class="text-right">Кол-во товаров</th>
                </tr>
                <?php foreach ($stat as $id => $item) { ?>

                    <tr>
                        <td>
                            <?= Html::a($item['name'], [
                                '/admin/location/view-stat',
                                'id' => $id,
                            ], [
                                'target' => '_blank',
                                'title' => 'Статистика по товарам',
                            ]) ?>
                        </td>
                        <td>
                            <?= $item['total'] ?>
                        </td>
                    </tr>

                <?php } ?>
            </table>
        </div>
    </div>
</div>
