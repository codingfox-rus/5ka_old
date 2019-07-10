<?php
/* @var $model app\models\Location */

use yii\helpers\Html;

$this->title = $model->name;

if ($model->region) {
    $this->params['breadcrumbs'][] = ['label' => 'Регион: '. $model->region->name, 'url' => ['/admin/region/view/', 'id' => $model->regionId]];
}
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="view-stat-index">
    <h4><?= $this->title ?></h4>
    <br>


</div>
