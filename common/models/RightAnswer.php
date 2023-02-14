<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "answer".
 *
 * @property int $id
 * @property int|null $question_id
 * @property array|null $variant_id
 *
 * @property Question $question
 * @property Variant $variant
 */
class RightAnswer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'right_answer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['question_id', 'variant_id'], 'integer'],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => Question::class, 'targetAttribute' => ['question_id' => 'id']],
            // [['variant_id'], 'exist', 'skipOnError' => true, 'targetClass' => Variant::class, 'targetAttribute' => ['variant_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question_id' => 'Question ID',
            'variant_id' => 'Variant ID',
        ];
    }
}
