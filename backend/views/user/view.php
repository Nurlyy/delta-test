<?php

$count_questions = [];
$count_answers = [];
$unfinished_themes = 0;
foreach ($themes as $theme) {
    $count_questions[$theme['id']] = 0;
    $count_answers[$theme['id']] = 0;
    foreach ($questions as $question) {
        if ($question['theme_id'] == $theme['id']) {
            $count_questions[$theme['id']] = isset($count_questions[$theme['id']]) ? $count_questions[$theme['id']] + 1 : 1;
            foreach ($answers as $answer) {
                if ($answer['question_id'] == $question['id'])
                    $count_answers[$theme['id']] = isset($count_answers[$theme['id']]) ? $count_answers[$theme['id']] + 1 : 1;
            }
        }
    }
    if ($count_answers[$theme['id']] >= 0 && $count_answers[$theme['id']] != $count_questions[$theme['id']]) {
        $unfinished_themes++;
    }
}

?>


<div class="row col-12">
    <h4 class="text-center">Пользователь: <?= $model->username ?></h4>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="card" style="width: 90%; margin-left: auto; margin-right:auto;">
                <div class="card-header">
                    <h4>Список непройденных тестов:</h4>
                </div>
                <ul class="list-group list-group-flush">
                    <?php
                    if ($unfinished_themes > 0) {
                        foreach ($themes as $theme) {
                            if ($count_answers[$theme['id']] != $count_questions[$theme['id']]) {
                    ?>
                                <li class="list-group-item">
                                    <div><?= $theme->name ?>
                                        <div style="float:right; position:relative; margin-right:30px;" class="btn btn-warning">
                                            <?= $count_answers[$theme['id']] ?>/<?= $count_questions[$theme['id']] ?>
                                        </div>
                                    </div>
                                </li>
                        <?php }
                        }
                    } else { ?>
                        <li class="list-group-item">
                            <div>Список пуст
                                <button style="float:right; position:relative;" class="button" disabled>
                                    <a style="text-decoration:none; color:white;">
                                        Список пуст
                                    </a>
                                </button>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <div class="card" style="width: 90%; margin-left: auto; margin-right:auto; margin-top:70px;">
                <div class="card-header">
                    <h4>Список пройденных тестов:</h4>
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($themes as $theme) {
                        if ($count_answers[$theme['id']] == $count_questions[$theme['id']]) {
                    ?>
                            <li class="list-group-item">
                                <div><?= $theme->name ?>
                                    <button style="float:right; position:relative;" class="button" disabled>
                                        <a style="text-decoration:none; color:white;" href="<?php if ($count_answers[$theme['id']] == $count_questions[$theme['id']]) {
                                                                                                echo '/backend/user/view-results?theme_id=' . $theme['id'] . '&user_id=' . $model->id;
                                                                                            } ?>">
                                            <?php if ($count_answers[$theme['id']] == $count_questions[$theme['id']]) {
                                                echo 'Посмотреть результаты';
                                            } ?>
                                        </a>
                                    </button>
                                    <div style="float:right; position:relative; margin-right:30px;" class="btn btn-warning">
                                        <?= $count_answers[$theme['id']] ?>/<?= $count_questions[$theme['id']] ?>
                                    </div>
                                </div>
                            </li>
                    <?php }
                    } ?>
                </ul>
            </div>
        </div>
    </div>

</div>