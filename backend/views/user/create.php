<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Theme $model */

$this->title = 'Создать нового пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Themes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="theme-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>