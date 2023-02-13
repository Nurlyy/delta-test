<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Theme $model */

$this->title = 'Создать новую тему';
$this->params['breadcrumbs'][] = ['label' => 'Языки', 'url' => ['/languages']];
$this->params['breadcrumbs'][] = ['label' => $language->name, 'url' => ['/languages/view/'.$language->id]];
$this->params['breadcrumbs'][] = ['label' => 'Темы', 'url' => ['/languages/'.$language->id.'/theme']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="theme-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
