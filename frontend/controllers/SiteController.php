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
use common\models\Answer;
use common\models\User;
use common\models\Question;
use common\models\RightAnswer;
use common\models\Theme;
use common\models\UsersLanguages;
use common\models\Variant;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        // var_dump(Yii::$app->user->identity->status);exit;
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'ruleConfig' => [
                'class' => '\common\components\AccessRule',
            ],
            // 'only' => ['logout', 'signup', 'index'],
            'rules' => [
                [
                    'actions' => ['signup', 'login'],
                    'allow' => true,
                    'roles' => ['?'],
                ],
                [
                    'actions' => ['logout'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
                [
                    'allow' => false,
                    'roles' => ['@', User::STATUS_ADMIN],
                    'matchCallback' => function ($rule, $action) {
                        return Yii::$app->user->identity->isAdmin();
                    },
                    'denyCallback' => function ($rule, $action) {
                        return $this->redirect(["/backend/site/index"]);
                    },
                ],
                [
                    'allow' => true,
                    'roles' => ['@', User::STATUS_PARTICIPANT],
                    // 'roles' => ['@'],

                ],
            ],
        ];
        return $behaviors;
    }
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $users_language = UsersLanguages::find()->where(['user_id' => Yii::$app->user->id])->one();
        $themes = Theme::find()->where(['language_id' => $users_language->language_id])->all();
        $questions = Question::find()->all();
        $answers = Answer::find()->where(['user_id' => Yii::$app->user->id])->all();
        $variants = [];
        foreach ($questions as $question) {
            $v = Variant::find()->where(['question_id' => $question->id])->all();
            $variants[$question->id] = $v;
        }
        $this->layout = 'main';
        return $this->render('index', ['themes' => $themes, 'questions' => $questions, 'answers' => $answers, 'variants' => $variants]);
    }



    public function actionTestPage()
    {
        $theme_id = isset($_GET['theme_id']) ? htmlentities($_GET['theme_id']) : null;
        $q_count = isset($_GET['q_count']) ? $_GET['q_count'] : null;
        $users_language = UsersLanguages::find()->where(['user_id' => Yii::$app->user->id])->one();
        $themes = Theme::find()->where(['language_id' => $users_language->language_id])->all();
        if (isset($theme_id) && isset($q_count)) {
            $theme = Theme::find()->where(['id' => $theme_id])->one();
            if ($theme == null && !in_array($theme, $themes)) {
                return $this->redirect("/");
            }
        } else {
            return $this->redirect("/");
        }
        $questions_count = Question::find()->where(['theme_id' => $theme_id])->asArray()->all();
        $temp = [];
        foreach ($questions_count as $quest_c) {
            array_push($temp, $quest_c['id']);
        }
        $answers = Answer::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['in', 'question_id',  $temp])->asArray()->all();
        // var_dump($answers);exit;
        // $temp = [];
        // foreach ($answers as $answer) {
        //     array_push($temp, $answer['question_id']);
        // }
        // $answers = $temp;

        $q = null;
        $sounter = 1;

        $variant_answers = [];
        foreach ($answers as $answer) {
            $variant_answers[$answer['variant_id']] = $answer;
        }

        $temp = [];
        foreach ($answers as $a) {
            $temp[$a['question_id']] = $a;
        }
        $answers = $temp;
        $unanswered = null;
        $unanswereds = [];
        // var_dump($q_count);exit;
        // var_dump(count($questions_count));exit;
        foreach ($questions_count as $quest_c) {
            if ($q_count == $sounter) {
                $q = $quest_c;
                // var_dump("tr");exit;
            }
            
            if (!isset($answers[$quest_c['id']])) {
                if($unanswered == null){
                    $unanswered = $sounter;
                }
                array_push($unanswereds, $quest_c);
                // var_dump(!isset($answers[$quest_c['id']]));exit;
                // var_dump($unanswered);
            }
            $sounter++;
        }

        if(count($unanswereds) == 0){
            return $this->redirect('/');
        }

        // var_dump($unanswereds);exit;


        // exit;

        if ($q_count > count($questions_count) || $q_count <= 0) {
            // return false;
            if($unanswered == null){
                return $this->redirect("/");
            }
            else{
                return $this->redirect("/site/test-page?theme_id={$theme->id}&q_count={$unanswered}");
            }
        }
        $question = Question::find()->where(['theme_id' => $theme->id, 'id' => $q['id']])->one();
        // $question = Question::find()->where(['theme_id' => $theme->id])->andWhere(['not in', 'id', $answers])->one();
        // if (isset($question)) {
        //     if(Answer::find()->where(['question_id' => $question->id, 'user_id' => Yii::$app->user->id])->one()!=null){
        //         // var_dump(Answer::find()->where(['question_id' => $question->id, 'user_id' => Yii::$app->user->id])->one());exit;
        //         return $this->redirect('index');
        //     }
        // }
        if ($question == null) {
            return $this->redirect('index');
        }
        // $temp = [];
        // foreach($answers as $answer){
        //     foreach($questions_count as $q){
        //         if($answer['question_id'] == $q['id']){
        //             // $q_count++;
        //             array_push($temp, $answer);
        //             continue 2;
        //         }
        //     }
        // }
        // $answers = $temp;
        // var_dump($answers);exit;


        // $issecond = ((count($answers) + 1) === count($questions_count)) ? 'false' : 'true';
        $variants = Variant::find()->where(['question_id' => $question->id])->all();
        $is_last = count($unanswereds) === 1 ? 'true' : 'false';
        if(Answer::find()->where(['question_id' => $question->id, 'user_id' => Yii::$app->user->id])->one()!=null){
            $is_last = 'false';
        }
        $this->layout = 'main';
        return $this->render('testpage', [
            'answers' => $answers,
            'theme' => $theme,
            'question' => $question,
            'variants' => $variants,
            'question_count' => $q_count,
            'is_last' => $is_last,
            // 'issecond' => $issecond,
            'questions' => $questions_count,
            'variant_answers' => $variant_answers,
        ]);
    }


    public function actionAnswer()
    {
        if ($this->request->isPost) {
            $question_id = isset($_POST['question_id']) ? $_POST['question_id'] : null;
            $variants_id = isset($_POST['variants_id']) ? $_POST['variants_id'] : null;
            $is_last = isset($_POST['is_last'])? $_POST['is_last'] : null;
            // var_dump($variants_id);exit;
            // if(Answer::find()->where(['question_id' => $question_id, 'user_id' => Yii::$app->user->id])->one()!=null){
            //     return $this->redirect('/');
            // }
            try {
                $transaction = Yii::$app->db->beginTransaction();
                $answers = Answer::find()->where(['question_id' => htmlentities($question_id), 'user_id' => Yii::$app->user->id])->all();
                foreach ($answers as $answer) {
                    $answer->delete();
                }
                foreach ($variants_id as $variant_id) {
                    $variant = Variant::find()->where(['id' => htmlentities($variant_id)])->one();
                    // $answer = Answer::find()->where(['question_id' => $question_id, 'user_id' => Yii::$app->user->id])->one();
                    // if ($answer == null) {
                    $answer = new Answer();
                    // }
                    $answer->question_id = htmlentities($question_id);
                    $answer->variant_id = $variant_id;
                    $answer->user_id = Yii::$app->user->id;
                    $answer->is_right = $variant->is_right;
                    if ($answer->validate()) {
                        if ($answer->save()) {
                        } else {
                            throw new \Exception('answer saving error');
                        }
                    } else {
                        throw new \Exception('answer validation error');
                    }
                }
                $transaction->commit();
                if($is_last == 'false'){
                    return true;
                }
                else{
                    return $this->redirect('index');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
            }
        }
    }

    public function actionViewResults()
    {
        $theme_id = isset($_GET['theme_id']) ? htmlentities($_GET['theme_id']) : null;
        $theme = Theme::find()->where(['id' => $theme_id])->one();
        if ($theme == null) {
            return $this->redirect('/');
        }
        $questions = Question::find()->where(['theme_id' => $theme_id])->asArray()->all();
        $answers = [];
        // $right_answers = [];
        $variants = [];
        foreach ($questions as $question) {
            // array_push($right_answers, RightAnswer::find()->where(['question_id'=>$question['id']])->one());
            $answers[$question['id']] = Answer::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['question_id' => $question['id']])->asArray()->all();
            $variants[$question['id']] = Variant::find()->where(['question_id' => $question['id']])->asArray()->all();
            // var_dump($variants);
        }
        if (empty($answers)) {
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

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
