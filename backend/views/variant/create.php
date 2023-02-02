<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Variant $model */

$this->title = 'Create Variant';
$this->params['breadcrumbs'][] = ['label' => 'Variants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="variant-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
