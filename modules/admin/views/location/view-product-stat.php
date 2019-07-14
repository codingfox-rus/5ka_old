<?php
/* @var $stat app\models\Stat */

use yii\helpers\Html;

$this->title = $stat->product->name;

if ($stat->location->region) {
    $this->params['breadcrumbs'][] = ['label' => 'Регион: '. $stat->location->region->name, 'url' => ['/admin/region/view/', 'id' => $stat->location->regionId]];
}
$this->params['breadcrumbs'][] = ['label' => $stat->location->name, 'url' => ['/admin/location/view-stat', 'id' => $stat->locationId]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="view-product-stat">
    <h4><?= $this->title ?></h4>
    <br>

    <table class="table table-striped table-condensed table-bordered">
        <tr>
            <th>
                Исходная цена
            </th>
            <th>
                Цена со скидкой
            </th>
            <th>
                Период
            </th>
        </tr>

        <?php foreach ($stat->data as $item) { ?>

            <tr>
                <td>
                    <span><i class="fa fa-rub"></i></span> <?= $item['regularPrice'] ?>
                </td>
                <td>
                    <span><i class="fa fa-rub"></i></span> <?= $item['specialPrice'] ?>
                </td>
                <td>
                    <?= date('d.m.Y', $item['dateStart']) ?> - <?= date('d.m.Y', $item['dateEnd']) ?>
                </td>
            </tr>

        <?php } ?>
    </table>
</div>
