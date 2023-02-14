<?php

namespace backend\controllers;

use common\models\Question;
use common\models\RightAnswer;
use common\models\Theme;
use common\models\Variant;
use yii\filters\AccessControl;
use common\components\AccessRule;
use common\models\Answer;
use common\models\Languages;
use Exception;
use yii\bootstrap5\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use common\models\User;
use yii\base\Model;

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
    public function actionIndex($language_id)
    {
        $language = Languages::find()->where(['id' => $language_id])->one();
        $dataProvider = new ActiveDataProvider([
            'query' => Theme::find()->where(['language_id' => $language_id]),
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
            'language' => $language,
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
    public function actionCreate($language_id)
    {
        $language = Languages::find()->where(['id' => $language_id])->one();
        $model = new Theme();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->language_id = $language->id;
                if ($model->validate()) {
                    if ($model->save()) {
                        return $this->redirect(['index', 'language_id' => $language->id]);
                    }
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'language' => $language,
        ]);
    }

    /**
     * Updates an existing Theme model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $language_id)
    {
        $language = Languages::find()->where(['id' => $language_id])->one();
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['languages/'.$language->id.'/theme/update', 'id' => $model->id]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Question::find()->where(['theme_id' => $model->id]),
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
            'language' => $language,
        ]);
    }

    /**
     * Deletes an existing Theme model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $language_id)
    {
        $language = Languages::find()->where(['id' => $language_id])->one();
        $questions = Question::find()->where(['theme_id' => $id])->all();
        try{
            $transaction = \Yii::$app->db->beginTransaction();

            foreach($questions as $question){
                $answers = Answer::find()->where(['question_id' => $question->id])->all();
                $variants = Variant::find()->where(['question_id' => $question->id])->all();
                foreach($answers as $answer){
                    if(!$answer->delete()){
                        throw new Exception("answer deleting error");
                    }
                }
                foreach($variants as $variant){
                    if(!$variant->delete()){
                        throw new Exception("variant deleting error");
                    }
                }
                if(!$question->delete()){
                    throw new Exception("question deleting error");
                }
                
            }
            if(!$this->findModel($id)->delete()){
                throw new Exception('theme deleting error');
            }
            $transaction->commit();
            
        } catch (\Exception $e) {
            $transaction->rollback();
        }
        
        

        return $this->redirect(['languages/'.$language->id.'/theme']);
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


    public function actionCreateQuestion($id, $language_id)
    {
        $language = Languages::find()->where(['id' => $language_id])->one();
        $question = new Question();
        $variants = [];
        // $variant1 = new Variant();
        // $variant2 = new Variant();
        // $variant3 = new Variant();
        // $variant4 = new Variant();
        // $right_answer = new RightAnswer();
        $theme = Theme::findOne(['id' => $id]);

        if ($this->request->isPost) {
            // var_dump($_POST);exit;
            try {
                $transaction = \Yii::$app->db->beginTransaction();
                if ($question->load($this->request->post())) {
                    if ($question->save()) {
                        foreach($_POST['Variant'] as $variant){
                            // array_push($variants, $variant);
                            if ($variant->validate()) {
                                if ($variant->save()) {
                                } else {
                                    throw new Exception('variants saving error');
                                }
                            }
                        }
                        $transaction->commit();
                        return $this->redirect(['languages/'.$language->id.'/theme/update', 'id' => $theme->id]);

                        // $variant1->question_id = $question->id;
                        // $variant2->question_id = $question->id;
                        // $variant3->question_id = $question->id;
                        // $variant4->question_id = $question->id;
                        // $variant1->attributes = ($_POST['Variant'][1]);
                        // $variant2->attributes = ($_POST['Variant'][2]);
                        // $variant3->attributes = ($_POST['Variant'][3]);
                        // $variant4->attributes = ($_POST['Variant'][4]);
                        // if ($right_answer->load($this->request->post())) {
                        //     switch ($right_answer->variant_id) {
                        //         case 1:
                        //             $variant1->is_right = 1;
                        //             break;
                        //         case 2:
                        //             $variant2->is_right = 1;
                        //             break;
                        //         case 3:
                        //             $variant3->is_right = 1;
                        //             break;
                        //         case 4:
                        //             $variant4->is_right = 1;
                        //             break;
                        //     }
                            // var_dump($variant2->is_right);exit;
                        // }
                        // if ($variant1->validate() && $variant2->validate() && $variant3->validate() && $variant4->validate()) {

                        //     if ($variant1->save() && $variant2->save() && $variant3->save() && $variant4->save()) {
                        //         $transaction->commit();
                        //         return $this->redirect(['languages/'.$language->id.'/theme/update', 'id' => $theme->id]);
                        //     } else {
                        //         throw new Exception('variants saving error');
                        //     }
                        // }
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
            // 'variant1' => $variant1,
            // 'variant2' => $variant2,
            // 'variant3' => $variant3,
            // 'variant4' => $variant4,
            'variants' => $variants,
            // 'right_answer' => $right_answer, 
            'language' => $language,
        ]);
    }


    public function actionUpdateQuestion($language_id)
    {
        $language = Languages::find()->where(['id' => $language_id])->one();
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        // var_dump($id);
        if ($id !== null) {
            $question = Question::findOne(['id' => $id]);
            // var_dump($question);exit;
            $theme = Theme::findOne(['id' => $question->getThemeId()]);
            $variants = Variant::find()->where(['question_id' => $question->id])->asArray()->all();
            // $variant1 = $variants[0];
            // $variant2 = $variants[1];
            // $variant3 = $variants[2];
            // $variant4 = $variants[3];
            // $right_answer = new RightAnswer();
            // $right_answer->variant_id = ($variant1->is_right == 1) ? $variant1->id : (($variant2->is_right == 1) ? $variant2->id : (($variant3->is_right == 1) ? $variant3->id : (($variant4->is_right == 1) ? $variant4->id : null)));

            if ($this->request->isPost) {
                // var_dump($_POST);
                // exit;
                // $variant1->attributes = $_POST['Variant'][1];
                // $variant2->attributes = $_POST['Variant'][2];
                // $variant3->attributes = $_POST['Variant'][3];
                // $variant4->attributes = $_POST['Variant'][4];
                // $right_answer->attributes = $_POST['RightAnswer'];
                // var_dump($right_answer->variant_id);exit;
                // $variant1->is_right = 0;
                // $variant2->is_right = 0;
                // $variant3->is_right = 0;
                // $variant4->is_right = 0;
                // switch ($right_answer->variant_id) {
                //     case $variant1->id:
                //         $variant1->is_right = 1;
                //         break;
                //     case $variant2->id:
                //         $variant2->is_right = 1;
                //         break;
                //     case $variant3->id:
                //         $variant3->is_right = 1;
                //         break;
                //     case $variant4->id:
                //         $variant4->is_right = 1;
                //         break;
                // }
                // // var_dump($variant1);exit;
                // if (
                //     $question->load($this->request->post()) &&
                //     $variant1->validate() &&
                //     $variant2->validate() &&
                //     $variant3->validate() &&
                //     $variant4->validate()
                // ) {

                //     if (
                //         $question->save() &&
                //         $variant1->save() &&
                //         $variant2->save() &&
                //         $variant3->save() &&
                //         $variant4->save()
                //     ) {
                //         return $this->redirect(['languages/'.$language->id.'/theme/update', 'id' => $theme->id]);
                //     }
                // }
            }

            return $this->render('update_question', [
                'question' => $question,
                // 'variant1' => $variant1,
                // 'variant2' => $variant2,
                // 'variant3' => $variant3,
                // 'variant4' => $variant4,
                // 'right_answer' => $right_answer,
                'variants' => $variants,
                'theme' => $theme,
                'language' => $language,
            ]);
        }
    }

    public function actionDeleteQuestion($id, $language_id)
    {
        $language = Languages::find()->where(['id' => $language_id])->one();
        $question = Question::findOne($id);
        $theme = Theme::findOne(['id' => $question->getThemeId()]);
        $variants = Variant::findAll(['question_id' => $question->id]);
        // $right_answer = RightAnswer::findOne(['question_id' => $question->id]);
        // var_dump($right_answer);exit;
        $deleted = [];
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            // return $right_answer->delete();
            // if (!$right_answer->delete()) {
            //     throw new Exception('right answer deleting error');
            // } else {
            //     echo "right answer deleted successfully";
            // }
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
        return $this->redirect(['languages/'.$language->id.'/theme/update', 'id' => $theme->id]);
    }
}
