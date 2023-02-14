<?php

?>

<div class="row col-12">
    <h2 class="text-center">Ваши результаты</h2>
    <br><br><br>
    <?php $counter = 1;
    foreach ($questions as $question) {
        $temp_variants = [];
        $is_right_count = 0;
        $is_chosen_count = 0;
        $is_chosen_right = false;
        foreach ($variants as $v) {
            foreach ($v as $variant) {
                if ($variant['question_id'] == $question['id']) {
                    if ($variant['is_right'] == 1) {
                        $is_right_count += 1;
                    }
                    $variant['is_chosen'] = false;
                    foreach ($answers as $a) {
                        foreach ($a as $answer) {
                            if ($answer['variant_id'] == $variant['id']) {

                                $variant['is_chosen'] = true;

                                if($variant['is_right'] == 1) {
                                    $is_chosen_right = true;
                                }

                                $is_chosen_count += 1;
                            }
                        }
                    }
                    array_push($temp_variants, $variant);
                }
            }
        }
    ?>
        <div class="card-body" style="margin-bottom:50px;">
            <div class="card" style="width: 90%; margin-left: auto; margin-right:auto;">
                <div class="card-header <?= ($is_right_count == $is_chosen_count && $is_chosen_right) ? 'true' : 'false' ?>">
                    <h4><?= $question['title'] ?></h4>
                </div>
                <div class="card-body">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php
                            foreach ($temp_variants as $variant) { ?>
                                <input <?= $is_right_count > 1 ? "class='form-check-input' type='checkbox'" : 'type="radio"' ?> id="variant_id" name="variant_id_<?= $question['id'] ?>" value="<?= $variant['id'] ?>" <?= ($variant['is_chosen']) ? 'checked' : '' ?> disabled>
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
    $is_right_count = 0;
    $is_chosen_count = 0;
    foreach ($questions as $question) {
        $count_questions++;
        foreach ($variants as $v) {
            foreach ($v as $variant) {
                if ($variant['question_id'] == $question['id']) {
                    if ($variant['is_right'] == 1) {
                        $is_right_count += 1;
                        foreach ($answers as $a) {
                            foreach ($a as $answer) {
                                if ($answer['variant_id'] == $variant['id']) {
                                    $is_chosen_count += 1;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    $result = round($is_chosen_count / $is_right_count * 100, 1);
    ?>
    <div class="col-12">
        <h2>Общий результат: <div class="btn btn-<?= ($result < 50) ? 'warning' : 'primary' ?>"><?= $result ?>%</div>
        </h2>
    </div>

</div>