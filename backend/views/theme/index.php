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

    

    <div class="col-12 row">
        <div class="card">

            <div class="text-center mt-4 mb-4">
                <h1><?= Html::encode($this->title) ?> языка <?= Html::encode($language->name) ?></h1>
            </div>
            <div class="card-body">
                <div class="card mb-3">
                    <div class="card-body create-language text-center">
                        <h3>
                        <?= Html::a('<strong>Создать новую тему</strong>', ['/languages/'.$language->id."/theme/".'create'], ['class' => 'btn']) ?>
                        </h3>
                    </div>
                </div>

                <?php foreach ($themes as $theme) { ?>
                    <div class="card language-item mb-3">
                        <div class="card-header" style="position:relative;">
                            <?= $theme->name ?>
                            <div style="float:right; position:relative;">
                                <div class="btn btn-primary " style="float:right; position:relative;"><a class="btn-a" href="/backend/languages/<?= $language->id ?>/theme/<?= $theme->id ?>/questions">Вопросы</a></div>
                                <div class="btn btn-warning " style="float:right; position:relative;margin-right:50px;"><a class="btn-a" href="/backend/languages/<?= $language->id ?>/theme/<?= $theme->id ?>/update">Изменить</a></div>
                                <div class="btn btn-danger " style="float:right; position:relative;margin-right:50px;"><a class="btn-a" href="/backend/languages/<?= $language->id ?>/theme/<?= $theme->id ?>/delete">Удалить</a></div>
                            </div>
                            
                        </div>

                    </div>
                <?php } ?>
            </div>
        </div>

    </div>


</div>
