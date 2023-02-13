<?php

?>

<div class="row col-12">
    <h2 class="text-center">Ваши результаты</h2>
    <br><br><br>
    <?php $counter = 1;
    foreach ($questions as $question) {
        $temp_variants = [];
        $isRight = false;
        foreach ($answers as $answer) {
            if ($answer['question_id'] == $question['id']) {
                foreach ($variants as $key => $v) {
                    foreach ($v as $variant) {
                        if ($variant['question_id'] == $question['id']) {
                            foreach ($right_answers as $right_answer) {
                                if ($variant['id'] == $answer['variant_id'] && $variant['is_right'] == 1) {
                                    $variant['is_right'] = true;
                                    $variant['is_chosen'] = true;
                                    $isRight = true;
                                } else if ($variant['id'] == $answer['variant_id'] && $variant['is_right'] != 1) {
                                    $variant['is_right'] = false;
                                    $variant['is_chosen'] = true;
                                } else if ($variant['is_right'] == 1) {
                                    $variant['is_right'] = true;
                                    $variant['is_chosen'] = false;
                                } else {
                                    $variant['is_right'] = false;
                                    $variant['is_chosen'] = false;
                                }
                                array_push($temp_variants, $variant);
                                continue 2;
                            }
                        }
                    }
                }
            }
        }

    ?>
        <div class="card-body" style="margin-bottom:50px;">

            <div class="card" style="width: 90%; margin-left: auto; margin-right:auto;">
                <div class="card-header <?= ($isRight) ? 'true' : 'false' ?>">
                    <h4><?= $question['title'] ?></h4>
                </div>
                <div class="card-body">
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <?php
                            foreach ($temp_variants as $variant) { ?>

                                <input type="radio" id="variant_id" name="variant_id_<?= $question['id'] ?>" value="<?= $variant['id'] ?>" <?= ($variant['is_chosen']) ? 'checked' : '' ?> disabled>
                                <label for="child" class="text-<?= ($variant['is_chosen'] && $variant['is_right']) ? 'success' : (($variant['is_chosen'] && !$variant['is_right']) ? 'danger' : ((!$variant['is_chosen'] && $variant['is_right']) ? 'success' : '')) ?>"><strong><?= $variant['title'] ?></strong></label><br>


                            <?php }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
        $counter++;
    }
    $count_questions = 0;
    $count_answers = 0;
    $right_answers = 0;
    foreach ($questions as $question) {
        if ($question['theme_id'] == $theme['id']) {
            $count_questions++;
            foreach ($answers as $answer) {
                if ($answer['question_id'] == $question['id']) {
                    if ($answer['is_right'] == 1) {
                        $right_answers++;
                    }
                    $count_answers++;
                }
            }
        }
    }

    $result = round($right_answers / $count_answers * 100, 1);
    ?>
    <div class="col-12">
        <h2>Общий результат: <div class="btn btn-<?= ($result < 50) ? 'warning' : 'primary' ?>"><?= $result ?>%</div>
        </h2>
    </div>

</div>