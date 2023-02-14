<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Question $question */
/** @var yii\widgets\ActiveForm $form */

// if (isset($variant1->id) && isset($variant2->id) && isset($variant3->id) && isset($variant4->id)) {
//     $variants = [];
//     $variants = [$variant1->id => '1', $variant2->id => '2', $variant3->id => '3', $variant4->id => '4'];
// } else {
//     $variants = [1 => "1", 2 => "2", 3 => "3", 4 => "4"];
// }

// var_dump($variants);exit;

?>


<div class="question-form">

    <div class="question-create">



        <div class="question-form">

            
                <input type="hidden" name="_csrf-backend" value="<?= Yii::$app->request->csrfToken ?>">
                <div class="mb-3 field-question-title required">
                    <label class="form-label" for="question-title">Текст вопроса</label>
                    <textarea id="question-title" class="form-control" name="Question[title]" rows="6"  aria-required="true"><?= $question['title'] ?></textarea>

                    <div class="invalid-feedback"></div>
                </div> <br>
                <div class="mb-3 field-question-theme_id required">
                    <label class="form-label" for="question-theme_id">Название темы</label>
                    <select id="question-theme_id" class="form-select" name="Question[theme_id]" aria-required="true">
                        <option value="8">&#xFEFF;<?= $theme->name ?></option>
                    </select>

                    <div class="invalid-feedback"></div>
                </div> <br>
                <h3>Варианты ответа</h3>
                <div class="variants">

                </div>

                <br>
                <div class="mb-3" style="padding:0px !important;">
                    <div class="btn btn-success" id="add_variant" style="padding:10px !important;">
                        <p style="padding:0px !important;margin:0px !important;">+ Добавить вариант</p>
                    </div>
                </div>
                <!-- <div class="mb-3 field-rightanswer-variant_id">
                    <label class="form-label" for="rightanswer-variant_id">Правильный ответ:</label>
                    <select id="rightanswer-variant_id" class="form-select" name="RightAnswer[variant_id]" required="">
                        <option value="-1" disabled="true" selected="">Выберите правильный вариант</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>

                    <div class="invalid-feedback"></div>
                </div> -->

                <br>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" id="btn_save">Save</button>
                </div>

            
        </div>
    </div>



</div>

<?php $this->registerJs("

    

    $(document).ready(function(){
        
        counter = 0;
        variants = " . json_encode($variants) . ";
        console.log(variants);

        on_create(variants);

        

        

        console.log(variants);
        
        $('#btn_save').click(function(){
            send_data = {};
            send_data['Variant'] = [];
            for (variant in variants) {
                send_data['Variant'][variant] = variants[variant];
            }
            send_data['Question'] = ".json_encode($question).";
            send_data['Question']['title'] = $('#question-title').val();
            console.log(send_data);
            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: {'Variant': send_data['Variant'], 'Question': send_data['Question'], '_csrf-backend': '".Yii::$app->request->csrfToken."'},
                success: function(data) {
                    console.log(data);
                    console.log('success');
                }
            });
        });
        

    });

    
") ?>