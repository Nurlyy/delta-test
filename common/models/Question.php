<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "question".
 *
 * @property int $id
 * @property string $title
 * @property string $code_text
 * @property int $theme_id
 * @property Answer[] $answers
 * @property Variant[] $variants
 */
class Question extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'question';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'theme_id'], 'required'],
            [['title', 'code_text'], 'string'],
            [['theme_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Текст вопроса',
            'theme_id' => 'Название темы',
            'code_text' => 'Код для вопроса',
        ];
    }


    public function getThemeId(){
        return $this->theme_id;
    }

    /**
     * Gets query for [[Answers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::class, ['question_id' => 'id']);
    }

    /**
     * Gets query for [[Variants]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVariants()
    {
        return $this->hasMany(Variant::class, ['question_id' => 'id']);
    }
}
