<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Variant $model */

$this->title = 'Update Variant: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Variants', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="variant-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
