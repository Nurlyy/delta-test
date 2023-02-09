<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use common\models\Question;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Theme $model */

$this->title = 'Изменить пользователя: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Темы', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Изменить '.$model->username;
?>
<div class="theme-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <br>

</div>