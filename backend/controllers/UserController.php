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
use common\models\Variant;

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
                            'actions' => ['search'],
                            'allow' => true,
                            'roles' => ['@', User::STATUS_ACTIVE],

                            'matchCallback' => function ($rule, $action) {
                                return !\Yii::$app->user->identity->isAdmin();
                            },
                            'denyCallback' => function ($rule, $action) {
                                return $this->redirect(["/site/index"]);
                            },

                        ],
                        [
                            'allow' => true,
                            'roles' => ['@', User::STATUS_ADMIN],
                            // 'roles' => ['@'],
                            'matchCallback' => function ($rule, $action) {
                                return \Yii::$app->user->identity->isAdmin();
                            },
                            'denyCallback' => function ($rule, $action) {
                                return $this->redirect(["/site/index"]);
                            },
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
        if ($this->request->isPost) {
            $model->email = $_POST['User']['email'];
            $model->username = $_POST['User']['username'];
            $model->password = $_POST['User']['password_hash'];
            if ($model->validate()) {
                if ($model->save()) {
                    return $this->redirect('index');
                }
            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate()
    {
        $user_id = $_GET['id'];
        $model = User::find()->where(['id' => $user_id])->one();
        if ($this->request->isPost) {
            $model->email = $_POST['User']['email'];
            $model->username = $_POST['User']['username'];
            $model->password = $_POST['User']['password_hash'];
            if ($model->validate()) {
                if ($model->save()) {
                    return $this->redirect('index');
                }
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete()
    {
        $user_id = $_GET['id'];
        if (User::find()->where(['id' => $user_id])->one()->delete()) {
            return $this->redirect('index');
        }
    }

    public function actionView()
    {
        $user_id = $_GET['id'];
        $model = User::find()->where(['id' => $user_id])->one();
        $themes = Theme::find()->all();
        $questions = Question::find()->all();
        $answers = Answer::find()->where(['user_id' => $user_id])->all();
        return $this->render('view', [
            'model' => $model,
            'themes' => $themes,
            'questions' => $questions,
            'answers' => $answers,
        ]);
    }


    public function actionViewResults()
    {
        $theme_id = $_GET['theme_id'];
        $theme = Theme::find()->where(['id' => $theme_id])->one();
        $user_id = $_GET['user_id'];
        $user = User::find()->where(['id' => $user_id])->one();
        $questions = Question::find()->where(['theme_id'=>$theme_id])->asArray()->all();
        $answers = [];
        $right_answers = [];
        $variants = [];
        foreach($questions as $question){
            array_push($right_answers, RightAnswer::find()->where(['question_id'=>$question['id']])->one());
            array_push($variants, Variant::find()->where(['question_id'=>$question['id']])->asArray()->all());
            array_push($answers, Answer::find()->where(['user_id'=>$user->id, 'question_id' => $question['id']])->asArray()->all()[0]);
            // var_dump($variants);
        }
        // var_dump($answers[0]);exit;
        return $this->render('view_results', [
            'theme' => $theme,
            'user' => $user,
            'questions' => $questions,
            'variants' => $variants,
            'answers' => $answers,
            'right_answers' => $right_answers,
        ]);
    }
}
