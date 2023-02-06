<?php
namespace backend\controllers;

use yii\web\Controller;
use yii\data\ActiveDataProvider;
use common\models\User;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\components\AccessRule;


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

    public function actionCreate(){
        $model = new User();
        
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
}
