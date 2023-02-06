<?php

namespace backend\controllers;

use common\models\Question;
use common\models\RightAnswer;
use common\models\Theme;
use common\models\Variant;
use yii\filters\AccessControl;
use common\components\AccessRule;
use Exception;
use yii\bootstrap5\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use common\models\User;

/**
 * ThemeController implements the CRUD actions for Theme model.
 */
class ThemeController extends Controller
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

    /**
     * Lists all Theme models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Theme::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Theme model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Theme model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Theme();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Theme model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Question::find(['theme_id' => $model->id]),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('update', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing Theme model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Theme model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Theme the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Theme::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionCreateQuestion($id)
    {
        $question = new Question();
        $variant1 = new Variant();
        $variant2 = new Variant();
        $variant3 = new Variant();
        $variant4 = new Variant();
        $right_answer = new RightAnswer();
        $theme = Theme::findOne(['id' => $id]);

        if ($this->request->isPost) {
            // var_dump($_POST);exit;
            try {
                $transaction = \Yii::$app->db->beginTransaction();
                if ($question->load($this->request->post())) {
                    if ($question->save()) {
                        $variant1->question_id = $question->id;
                        $variant2->question_id = $question->id;
                        $variant3->question_id = $question->id;
                        $variant4->question_id = $question->id;
                        $variant1->attributes = ($_POST['Variant'][1]);
                        $variant2->attributes = ($_POST['Variant'][2]);
                        $variant3->attributes = ($_POST['Variant'][3]);
                        $variant4->attributes = ($_POST['Variant'][4]);
                        if ($variant1->validate() && $variant2->validate() && $variant3->validate() && $variant4->validate()) {
                            // var_dump($variant1);echo '<br>';var_dump($variant2);echo '<br>';var_dump($variant3);echo '<br>';var_dump($variant4);echo '<br>';exit;
                            if ($variant1->save() && $variant2->save() && $variant3->save() && $variant4->save()) {
                                if ($right_answer->load($this->request->post())) {
                                    switch ($right_answer->variant_id) {
                                        case 1:
                                            $right_answer->variant_id = $variant1->id;
                                            break;
                                        case 2:
                                            $right_answer->variant_id = $variant2->id;
                                            break;
                                        case 3:
                                            $right_answer->variant_id = $variant3->id;
                                            break;
                                        case 4:
                                            $right_answer->variant_id = $variant4->id;
                                            break;
                                    }
                                    $right_answer->question_id = $question->id;
                                    if ($right_answer->save()) {
                                        $transaction->commit();
                                        return $this->redirect(['update', 'id' => $theme->id]);
                                    } else {
                                        throw new Exception('right answer saving error');
                                    }
                                }
                            } else {
                                throw new Exception('variants saving error');
                            }
                        }
                    } else {
                        throw new Exception('question saving error');
                    }
                }
            } catch (\Exception $e) {
                $transaction->rollback();
            }
            // var_dump($_POST['Variant'][1]);exit;

        } else {
            $question->loadDefaultValues();
        }

        return $this->render('create_question', [
            'question' => $question,
            'theme' => $theme,
            'variant1' => $variant1,
            'variant2' => $variant2,
            'variant3' => $variant3,
            'variant4' => $variant4,
            'right_answer' => $right_answer,
        ]);
    }


    public function actionUpdateQuestion()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        // var_dump($id);
        if ($id !== null) {
            $question = Question::findOne(['id' => $id]);
            // var_dump($question);exit;
            $theme = Theme::findOne(['id' => $question->getThemeId()]);
            $variants = Variant::findAll(['question_id' => $question->id]);
            $variant1 = $variants[0];
            $variant2 = $variants[1];
            $variant3 = $variants[2];
            $variant4 = $variants[3];
            $right_answer = RightAnswer::findOne(['question_id' => $question->id]);

            if ($this->request->isPost) {
                //     var_dump($_POST);
                //     exit;
                $variant1->attributes = $_POST['Variant'][1];
                $variant2->attributes = $_POST['Variant'][2];
                $variant3->attributes = $_POST['Variant'][3];
                $variant4->attributes = $_POST['Variant'][4];
                $right_answer->attributes = $_POST['RightAnswer'];
                $right_answer->question_id = $question->id;
                if (
                    $question->load($this->request->post()) &&
                    $variant1->validate() &&
                    $variant2->validate() &&
                    $variant3->validate() &&
                    $variant4->validate() &&
                    $right_answer->validate()
                ) {

                    if (
                        $question->save() &&
                        $variant1->save() &&
                        $variant2->save() &&
                        $variant3->save() &&
                        $variant4->save() &&
                        $right_answer->save()
                    ) {
                        return $this->redirect(['update', 'id' => $theme->id]);
                    }
                }
            }

            return $this->render('update_question', [
                'question' => $question,
                'variant1' => $variant1,
                'variant2' => $variant2,
                'variant3' => $variant3,
                'variant4' => $variant4,
                'right_answer' => $right_answer,
                'theme' => $theme,
            ]);
        }
    }

    public function actionDeleteQuestion($id)
    {
        $question = Question::findOne($id);
        $theme = Theme::findOne(['id' => $question->getThemeId()]);
        $variants = Variant::findAll(['question_id' => $question->id]);
        $right_answer = RightAnswer::findOne(['question_id' => $question->id]);
        // var_dump($right_answer);exit;
        $deleted = [];
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            // return $right_answer->delete();
            if (!$right_answer->delete()) {
                throw new Exception('right answer deleting error');
            } else {
                echo "right answer deleted successfully";
            }
            foreach ($variants as $variant) {
                if (!$variant->delete()) {
                    throw new Exception("variant {$variant->title} deleting error");
                }
            }
            if (!$question->delete()) {
                throw new Exception('question deleting error');
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            return $e->getMessage();
        }
        return $this->redirect(['update', 'id' => $theme->id]);
    }
}
