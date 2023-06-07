<?php $count_questions = [];
$count_answers = [];
$right_answers = [];
$unfinished_themes = 0;
$finished_themes = 0;

$total_variants = [];
$tttotal_answers = [];
$total_right_answers = 0;

foreach ($themes as $theme) {
    foreach ($questions as $question) {
        if ($question['theme_id'] == $theme['id']) {
            foreach ($variants[$question['id']] as $variant) {
                if ($variant['is_right'] == 1) {
                    $total_variants[$question->id] = isset($total_variants[$question['id']]) ? $total_variants[$question['id']] + 1 : 1;
                }
            }
            foreach ($answers as $answer) {
                if ($answer['question_id'] == $question['id']) {
                    $tttotal_answers[$question['id']] = isset($tttotal_answers[$question['id']]) ? $tttotal_answers[$question['id']] + 1 : 1;
                }
            }
        }
    }
}

// var_dump($total_variants);
// exit;

foreach ($themes as $theme) {
    $count_questions[$theme['id']] = 0;
    $count_answers[$theme['id']] = 0;
    foreach ($questions as $question) {
        if ($question['theme_id'] == $theme['id']) {
            $count_questions[$theme['id']] = isset($count_questions[$theme['id']]) ? $count_questions[$theme['id']] + 1 : 1;
            foreach ($answers as $answer) {
                if ($answer['question_id'] == $question['id']) {
                    // var_dump($answer['is_right']);exit;
                    if ($answer['is_right'] == 1) {
                        if ($tttotal_answers[$question['id']] == $total_variants[$question['id']]) {
                            $total_right_answers += 1;
                            $right_answers[$theme['id']] = isset($right_answers[$theme['id']]) ? $right_answers[$theme['id']] + 1 : 1;
                        }
                    }
                    $count_answers[$theme['id']] = isset($count_answers[$theme['id']]) ? $count_answers[$theme['id']] + 1 : 1;
                    continue 2;
                }
            }
        }
    }
    // var_dump($count_answers);

    if ($count_answers[$theme['id']] >= 0 && $count_answers[$theme['id']] < $count_questions[$theme['id']]) {
        $unfinished_themes++;
    }
    if ($count_answers[$theme['id']] == $count_questions[$theme['id']]) {
        $finished_themes++;
    }
    // var_dump($right_answers);exit;
}

// var_dump($total_right_answers);
// exit;

?>
<div class="site-index">

    <div class="row col-12">
        <h1 class="title-text" style="margin-top:10px;margin-bottom:25px; font-size:60px; text-align:center; width:100%;">Пользователь: <?= $model->username ?></h1>
        <div class="panel panel-default">
            <div class="panel-body">
                <?php
                if ($unfinished_themes > 0) {
                ?>
                    <div class="card" style="width: 90%; margin-left: auto; margin-right:auto;">

                        <div class="card-header">
                            <h4>Список тем для теста:</h4>
                        </div>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($themes as $theme) {
                                if ($count_answers[$theme['id']] != $count_questions[$theme['id']]) {
                            ?>
                                    <li class="list-group-item">
                                        <div><?= $theme->name ?>
                                            <button style="float:right; position:relative; margin-top:3px; margin-left:15px;" class="btn btn-danger">
                                                <a style="text-decoration:none; color:white;" href="<?php 
                                                                                                        echo '/backend/user/delete-results?theme_id=' . $theme['id'] . '&user_id=' . $model->id;
                                                                                                    ?>">
                                                        Удалить результаты
                                                </a>
                                            </button>
                                            <button style="float:right; position:relative;" class="button" disabled>
                                                <a style="text-decoration:none; color:white;" href="<?php 
                                                                                                        echo '/backend/user/view-results?theme_id=' . $theme['id'] . "&user_id=" . $model->id;
                                                                                                     ?>">
                                                    Посмотреть результаты
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
                <?php }
                if ($finished_themes > 0) {
                ?>
                    <div class="card" style="width: 90%; margin-left: auto; margin-right:auto; <?= ($unfinished_themes > 0) ? ' margin-top:70px;' : '' ?>">
                        <div class="card-header">
                            <h4>Список пройденных тестов:</h4>
                        </div>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($themes as $theme) {
                                if ($count_answers[$theme['id']] == $count_questions[$theme['id']]) {
                            ?>
                                    <li class="list-group-item">
                                        <div><?= $theme->name ?>
                                            <button style="float:right; position:relative; margin-top:3px; margin-left:15px;" class="btn btn-danger">
                                                <a style="text-decoration:none; color:white;" href="<?php if ($count_answers[$theme['id']] == $count_questions[$theme['id']]) {
                                                                                                        echo '/backend/user/delete-results?theme_id=' . $theme['id'] . '&user_id=' . $model->id;
                                                                                                    } ?>">
                                                    <?php if ($count_answers[$theme['id']] == $count_questions[$theme['id']]) {
                                                        echo 'Удалить результаты';
                                                    } ?>
                                                </a>
                                            </button>
                                            <button style="float:right; position:relative;" class="button" disabled>
                                                <a style="text-decoration:none; color:white;" href="<?php if ($count_answers[$theme['id']] == $count_questions[$theme['id']]) {
                                                                                                        echo '/backend/user/view-results?theme_id=' . $theme['id'] . "&user_id=" . $model->id;
                                                                                                    } ?>">
                                                    <?php if ($count_answers[$theme['id']] == $count_questions[$theme['id']]) {
                                                        echo 'Посмотреть результаты';
                                                    } ?>
                                                </a>
                                            </button>
                                            <?php if (isset($right_answers[$theme['id']])) { ?>
                                                <div style="float:right; position:relative; margin-right:30px;" class="btn btn-success">
                                                    <?= $right_answers[$theme['id']] ?>/<?= $count_questions[$theme['id']] ?>
                                                </div>
                                                <div style="float:right; position:relative; margin-right:30px;" class="btn btn-warning">
                                                    <?= $count_answers[$theme['id']] ?>/<?= $count_questions[$theme['id']] ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </li>
                            <?php }
                            } ?>
                        </ul>
                    </div>
                    <?php

                    $total_total_right = 0;
                    $total_total_questions = 0;
                    foreach ($themes as $theme) {
                        if ($count_answers[$theme['id']] == $count_questions[$theme['id']]) {
                            $total_total_right += $right_answers[$theme['id']];
                        }
                    }

                    foreach ($count_questions as $c) {
                        $total_total_questions += $c;
                    }
                    $result = round($total_total_right / $total_total_questions * 100, 1);
                    ?>
                    <br>
                    <div class="card card-body" style="background-color:#017a87">
                        <h3 class="text-center text-white">Общий результат: <?= $total_total_right ?>/<?= $total_total_questions ?></h3>
                        <h3 class="text-center text-white">Общие баллы: <?= $result ?>%</h3>
                    </div>
                <?php } ?>

            </div>
        </div>


    </div>

</div>