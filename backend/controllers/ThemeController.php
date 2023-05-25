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

    /**
     * Lists all Theme models.
     *
     * @return string
     */
    public function actionIndex($language_id)
    {
        $language = Languages::find()->where(['id' => $language_id])->one();
        $themes = Theme::find()->where(['language_id' => $language->id])->all();
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
            'themes' => $themes,
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

    public function actionQuestions($id, $language_id){
        $language = Languages::find()->where(['id' => $language_id])->one();
        $theme = Theme::find()->where(['id' => $id])->one();

        $questions = Question::find()->where(['theme_id' => $id])->all();

        return $this->render('questions', [
            'language' => $language,
            'theme' => $theme,
            'questions' => $questions,
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
        $theme = Theme::findOne(['id' => $id]);
        if ($this->request->isPost) {
            try {
                $transaction = \Yii::$app->db->beginTransaction();
                if ($question->load($this->request->post())) {
                    $question->code_text = htmlentities($question->code_text);
                    $question->theme_id = $theme->id;
                    if ($question->save()) {
                        foreach($_POST['Variant'] as $variant){
                            if($variant['is_right'] == 'false'){
                                $variant['is_right'] = 0;
                            } else if($variant['is_right'] == 'true'){
                                $variant['is_right'] = 1;
                            }
                            $temp = new Variant();
                            if(isset($variant['id'])){
                                $temp->id = $variant['id'];
                            }
                            $temp->question_id = $question->id;
                            $temp->title = $variant['title'];
                            $temp->is_right = $variant['is_right'];
                            if ($temp->validate()) {
                                if ($temp->save()) {
                                } else {
                                    throw new Exception('variants saving error');
                                }
                            } else {
                                throw new Exception('variants validating error');
                            }
                        }
                        $transaction->commit();
                        return $this->redirect(['languages/'.$language->id.'/theme/'.$theme->id.'/questions']);
                    } else {
                        throw new Exception('question saving error');
                    }
                }
            } catch (\Exception $e) {
                $transaction->rollback();
            }
        } else {
            $question->loadDefaultValues();
        }
        return $this->render('create_question', [
            'question' => $question,
            'theme' => $theme,
            'variants' => $variants,
            'language' => $language,
        ]);
    }


    public function actionUpdateQuestion($question_id, $language_id)
    {
        $language = Languages::find()->where(['id' => $language_id])->one();
        if ($question_id !== null) {
            $question = Question::find()->where(['id' => $question_id])->one();
            $theme = Theme::findOne(['id' => $question->theme_id]);
            $variants = Variant::find()->where(['question_id' => $question->id])->asArray()->all();
            if ($this->request->isPost) {
                try {
                    $transaction = \Yii::$app->db->beginTransaction();
                    if ($question->load($this->request->post())) {
                        if ($question->save()) {
                            foreach($_POST['Variant'] as $variant){
                                if($variant['is_right'] == 'false'){
                                    $variant['is_right'] = 0;
                                } else if($variant['is_right'] == 'true'){
                                    $variant['is_right'] = 1;
                                }
                                if(isset($variant['id'])){
                                    $temp = Variant::find()->where(['id' => $variant['id']])->one();
                                } else {
                                    $temp = new Variant();
                                }
                                $temp->question_id = $question->id;
                                $temp->title = htmlentities($variant['title']);
                                $temp->is_right = $variant['is_right'];
                                if ($temp->validate()) {
                                    if ($temp->save()) {
                                    } else {
                                        var_dump('error variant saving');exit;
                                        throw new Exception('variants saving error');
                                    }
                                } else {
                                    var_dump('error variant validating');exit;
                                    throw new Exception('variants validating error');
                                }
                            }
                            $transaction->commit();
                            return $this->redirect(['languages/'.$language->id.'/theme/'.$theme->id.'/questions/']);
                        } else {
                            var_dump('question error');
                            throw new Exception('question saving error');
                        }
                    }
                } catch (\Exception $e) {
                    $transaction->rollback();
                    var_dump($e->getMessage());exit;
                }
            }

            return $this->render('update_question', [
                'question' => $question,
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
        $theme = Theme::findOne(['id' => $question->theme_id]);
        $variants = Variant::findAll(['question_id' => $question->id]);
        $deleted = [];
        $transaction = \Yii::$app->db->beginTransaction();
        try {
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
