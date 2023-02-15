<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\data\ActiveDataProvider;
use common\models\User;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\AccessRule;
use common\models\Answer;
use common\models\Question;
use common\models\RightAnswer;
use common\models\Theme;
use common\models\UsersLanguages;
use common\models\Variant;
use Exception;

class UserController extends Controller
{

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'ruleConfig' => [
                        'class' => AccessRule::class,
                    ],
                    // 'only' => ['search'],
                    'rules' => [
                        [
                            'allow' => false,
                            'roles' => ['@', User::STATUS_PARTICIPANT],
                            'matchCallback' => function ($rule, $action) {
                                return \Yii::$app->user->identity->isParticipant();
                            },
                            'denyCallback' => function ($rule, $action) {
                                return $this->redirect(["/site/logout"]);
                            },
                        ],
                        [
                            'allow' => true,
                            'roles' => ['@', User::STATUS_ADMIN],
                            // 'roles' => ['@'],
                            
                        ],
                    ],
                ]
            ]
        );
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(['!=', 'status', 3]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new User();
        $model->status = 4;
        $users_languages = new UsersLanguages();
        if ($this->request->isPost) {
            // var_dump($_POST);exit;
            $users_languages->language_id = $_POST['UsersLanguages']['language_id'];
            // var_dump($users_languages);
            // exit;
            $model->email = $_POST['User']['email'];
            $model->username = $_POST['User']['username'];
            $model->password = $_POST['User']['password_hash'];
            try {
                $transaction = \Yii::$app->db->beginTransaction();
                if ($model->validate()) {
                    if ($model->save()) {
                        $users_languages->user_id = $model->id;
                        if ($users_languages->validate()) {
                            if ($users_languages->save()) {
                                $transaction->commit();
                                return $this->redirect('index');
                            } else {
                                throw new Exception('users languages saving error');
                            }
                        } else {
                            // var_dump($users_languages->errors);exit;
                            throw new Exception('users languages validating error');
                        }
                    }
                    else {
                        throw new Exception('user saving error');
                    }
                }
                else {
                    throw new Exception('user validating error');
                }
            } catch (\Exception $e) {
                $transaction->rollback();
                var_dump($e->getMessage());exit;

            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->render('create', [
            'model' => $model,
            'users_languages' => $users_languages,
        ]);
    }

    public function actionUpdate()
    {
        $user_id = $_GET['id'];
        $model = User::find()->where(['id' => $user_id])->one();
        $users_languages = UsersLanguages::find()->where(['user_id' => $model->id])->one();
        if ($this->request->isPost) {
            $users_languages->language_id = $_POST['UsersLanguages']['language_id'];
            $model->email = $_POST['User']['email'];
            $model->username = $_POST['User']['username'];
            if($model->password_hash != $_POST['User']['password_hash']){
                $model->password = $_POST['User']['password_hash'];
            }
            if ($model->validate() && $users_languages->validate()) {
                if ($model->save() && $users_languages->save()) {
                    return $this->redirect('index');
                }
            }
        }
        return $this->render('update', [
            'model' => $model,
            'users_languages' => $users_languages,
        ]);
    }

    public function actionDelete()
    {
        $user_id = $_GET['id'];
        $answers = Answer::find()->where(['user_id' => $user_id])->all();
        foreach ($answers as $answer) {
            $answer->delete();
        }
        $users_languages = UsersLanguages::find()->where(['user_id' => $user_id])->one()->delete();
        if (User::find()->where(['id' => $user_id])->one()->delete()) {
            return $this->redirect('index');
        }
    }

    public function actionView()
    {
        $user_id = $_GET['id'];
        $users_languages = UsersLanguages::find()->where(['user_id' => $user_id])->one();
        $model = User::find()->where(['id' => $user_id])->one();
        $themes = Theme::find()->where(['language_id' => $users_languages->language_id])->all();
        $questions = Question::find()->all();
        $answers = Answer::find()->where(['user_id' => $user_id])->all();
        return $this->render('view', [
            'model' => $model,
            'themes' => $themes,
            'questions' => $questions,
            'answers' => $answers,
            'users_languages' => $users_languages,
        ]);
    }


    // public function actionViewResults()
    // {
    //     $theme_id = $_GET['theme_id'];
    //     $theme = Theme::find()->where(['id' => $theme_id])->one();
    //     $user_id = $_GET['user_id'];
    //     $user = User::find()->where(['id' => $user_id])->one();
    //     $questions = Question::find()->where(['theme_id' => $theme_id])->asArray()->all();
    //     $answers = [];
    //     // $right_answers = [];
    //     $variants = [];
    //     foreach ($questions as $question) {
    //         // array_push($right_answers, RightAnswer::find()->where(['question_id' => $question['id']])->one());
    //         array_push($variants, Variant::find()->where(['question_id' => $question['id']])->asArray()->all());
    //         array_push($answers, Answer::find()->where(['user_id' => $user->id, 'question_id' => $question['id']])->asArray()->all()[0]);
    //         // var_dump($variants);
    //     }
    //     // var_dump($answers[0]);exit;
    //     return $this->render('view_results', [
    //         'theme' => $theme,
    //         'user' => $user,
    //         'questions' => $questions,
    //         'variants' => $variants,
    //         'answers' => $answers,
    //         // 'right_answers' => $right_answers,
    //     ]);
    // }

    public function actionViewResults()
    {
        $theme_id = $_GET['theme_id'];
        $theme = Theme::find()->where(['id' => $theme_id])->one();
        $user_id = $_GET['user_id'];
        $user = User::find()->where(['id' => $user_id])->one();
        $questions = Question::find()->where(['theme_id' => $theme_id])->asArray()->all();
        $answers = [];
        // $right_answers = [];
        $variants = [];
        foreach ($questions as $question) {
            // array_push($right_answers, RightAnswer::find()->where(['question_id'=>$question['id']])->one());
            array_push($answers, Answer::find()->where(['user_id' => $user->id])->andWhere(['question_id' => $question['id']])->asArray()->all());
            array_push($variants, Variant::find()->where(['question_id' => $question['id']])->asArray()->all());
            // var_dump($variants);
        }
        if ($answers[0] == null) {
            return $this->redirect('index');
        }
        return $this->render('view_results', [
            'theme_id' => $theme_id,
            'questions' => $questions,
            'answers' => $answers,
            'variants' => $variants,
            'theme' => $theme,
        ]);
    }

    public function actionDeleteResults()
    {
        $theme_id = $_GET['theme_id'];
        $theme = Theme::find()->where(['id' => $theme_id])->one();
        $user_id = $_GET['user_id'];
        // $user = User::find()->where(['id' => $user_id])->one();
        $questions = Question::find()->where(['theme_id' => $theme_id])->all();
        try {
            $transaction = \Yii::$app->db->beginTransaction();
            foreach($questions as $question){
                $answer = Answer::find()->where(['user_id' => $user_id])->andWhere(['question_id' => $question->id])->all();
                foreach($answer as $a){
                    if($a->delete()){

                    } else {
                        throw new \Exception('answer deleting error');
                    }
                }
                
            }
            $transaction->commit();
        }
        catch (Exception $e){
            $transaction->rollBack();
        }
        return $this->redirect(['user/view', 'id' => $user_id]);
    }
}
