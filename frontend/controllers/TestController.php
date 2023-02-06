<?php

namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\components\AccessRule;
use common\models\User;

/**
 * Site controller
 */
class TestController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => ['question'],
                'rules' => [
                    [
                        'actions' => ['question'],
                        'allow' => true,
                        'roles' => ['@', User::STATUS_PARTICIPANT],

                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->identity->isParticipant();
                        },
                        'denyCallback' => function ($rule, $action) {
                            return $this->redirect(["/site/index"]);
                        },
                    ],
                ],
            ],
        ];
    }

    public function actionQuestion(){
        return "Hello";
    }
}
