<?php

namespace common\models;

use JetBrains\PhpStorm\Language;
use Yii;

/**
 * This is the model class for table "theme".
 *
 * @property int $id
 * @property string $name
 * @property Language $language_id
 */
class Theme extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'theme';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['language_id'], 'int'],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::class, 'targetAttribute' => ['language_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название темы',
        ];
    }

    public function getQuestions(){
        return $this->hasMany(Question::class, ['theme_id' => 'id']);
    }
}
