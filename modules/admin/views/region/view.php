<?php
/* @var $this yii\web\View */
/* @var $model app\models\Region */
/* @var $stat array */
/* @var $totalDiscounts array */

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
        <div class="col-md-7">
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

                <table class="table table-bordered table-striped">
                    <tr>
                        <th>Локация</th>
                        <th>Обновлено</th>
                        <th>Всего скидок</th>
                    </tr>

                    <?php foreach ($model->locations as $location) { ?>

                        <tr>
                            <td>
                                <div class="checkbox">
                                    <label>
                                        <?= Html::checkbox('locations[]', $location->isEnabled, [
                                            'value' => $location->id,
                                        ]) ?>
                                        <?= $location->name ?> (ID <?= $location->id ?>)
                                    </label>
                                </div>
                            </td>
                            <td>
                                <?= $location->dataUpdatedAt ? date('d.m.Y H:i:s', $location->dataUpdatedAt) : '-' ?>
                            </td>
                            <td>
                                <?php $totalLocationDiscounts = $totalDiscounts[$location->id] ?? null ?>

                                <?php if ($totalLocationDiscounts) { ?>
                                    <?= Html::a($totalLocationDiscounts, [
                                        '/admin/discount/index',
                                        'locationId' => $location->id
                                    ], [
                                        'title' => 'Скидки в локации',
                                    ]) ?>
                                <?php } ?>
                            </td>
                        </tr>

                    <?php } ?>
                </table>

            <?php } else { ?>

                <p>Нет ни одной локации</p>

            <?php } ?>

            <?= Html::endForm() ?>
        </div>

        <div class="col-md-5">
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
