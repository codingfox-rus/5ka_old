<?php
/* @var $this yii\web\View */
/* @var $model app\models\Region */

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
                            <?= $location->name ?>
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


        </div>
    </div>
</div>
