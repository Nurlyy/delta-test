<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'languages/view/<id:[\w]+>' => 'languages/view',
                'languages/<language_id:[\w]+>/theme' => 'theme',
                'languages/<language_id:[\w]+>/theme/<id:[\w]+>' => 'theme/update',
                'languages/<language_id:[\w]+>/theme/<id:[\w]+>/update' => 'theme/update',
                'languages/<language_id:[\w]+>/theme/<id:[\w]+>/create' => 'theme/create',
                'languages/<language_id:[\w]+>/theme/<id:[\w]+>/delete' => 'theme/delete',
                'languages/<language_id:[\w]+>/theme/<id:[\w]+>/create-question/<question_id:[\w]+>' => 'theme/create-question',
                'languages/<language_id:[\w]+>/theme/<id:[\w]+>/update-question/<question_id:[\w]+>' => 'theme/update-question',
                'languages/<language_id:[\w]+>/theme/<id:[\w]+>/delete-question/<question_id:[\w]+>' => 'theme/delete-question',

            ],
        ],
        
    ],
    'params' => $params,
];
