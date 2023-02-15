<?php

use common\models\Theme;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Темы';
$this->params['breadcrumbs'][] = ['label' => 'Языки', 'url' => ['/languages']];
$this->params['breadcrumbs'][] = ['label' => $language->name, 'url' => ['/languages/view/'.$language->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="theme-index">

    <h1><?= Html::encode($this->title) ?> языка <?= Html::encode($language->name) ?></h1>

    <p>
        <?= Html::a('Создать новую тему', ['/languages/'.$language->id."/theme/".'create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            [
                'class' => ActionColumn::className(),
                'template' => '{update} {delete}',
                'urlCreator' => function ($action, Theme $model, $key, $index, $column) {
                    return Url::toRoute(['/languages/'.$model->language_id."/theme/".$model->id."/".$action]);
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
