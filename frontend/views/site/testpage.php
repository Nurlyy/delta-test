<?php

?>

<div class="row col-12">
    <p class='text-center'>Вопрос номер <?= $question_count + 1 ?>:</p>
    <div class="card" style="width: 90%; margin-left: auto; margin-right:auto;">
        <div class="card-header">
            <h4><?= $question->title ?></h4>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <?php foreach ($variants as $variant) { ?>
                    <input type="radio" onclick="document.getElementById('submitbutton').disabled=false;" id="variant_id" name="variant_id" value="<?= $variant->id ?>" required>
                    <label for="child"><?= $variant->title ?></label><br>
                <?php } ?>
            </div>
        </div>
    </div>
    <button disabled id="submitbutton" style="position:relative; text-align:center; width:20%; margin-left:auto; margin-right:auto; margin-top:30px;" class="btn btn-primary"><?= ($issecond == 'true') ? 'Следующий' : 'Завершить тест' ?></button>

</div>

<?php $this->registerJs("
    issecond = {$issecond};
    $('#submitbutton').click(function() {
        $.ajax({
            url: '/site/answer',
            type: 'POST',
            data: {
                variant_id: $('#variant_id:checked').val(),
                question_id: {$question->id},
                '" . Yii::$app->request->csrfParam . "': '" . Yii::$app->request->csrfToken . "'
            },
            success: function(data) {
                if (data == '1') {
                    console.log(data);
                    if(issecond){
                        window.location.href = '/site/test-page?theme_id=" . $theme->id . "&q_count=" . $question_count + 1 . "';
                    } else {
                        window.location.href = '/site/index';
                    }
                } else {
                    console.log(data);
                }
            }
        });
    });

    
") ?>