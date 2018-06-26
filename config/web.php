<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name'=> 'Lotto App',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language'=>'th_TH',
    'timeZone'=>'UTC', // ต้อง set timezone DB เป็น Asia/Bangkok
    'aliases'=>require(__DIR__ . '/aliases.php'),
    'components' => [
        'formatter'=>[
            'class'=>'app\components\AppCustomFormatter',
            'dateFormat'=>'php:d/m/Y',
            'datetimeFormat'=>'php:d/m/Y H:i:s',
            'timeFormat'=>'php:H:i:s',
            'currencyCode'=>'฿',
            'decimalSeparator'=>'.',
            'thousandSeparator'=>',',
        ],
        'thaiFormatter'=>[
            'class'=>'dixonsatit\thaiYearFormatter\ThaiYearFormatter',
            //'nullDisplay'=>'-'
            'dateFormat'=>'php:d/m/Y',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => require(__DIR__ . '/cookie_validation_key.php'),
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'enableSession'=>true,
            'identityCookie' => [
                'name'=>'_identity',
                'httpOnly'=>true,
                //The timestamp defaults to 0, meaning "until the browser is closed"
                //'expire'=> 5
            ],
            'loginUrl'=>['/site/login'],
            'on afterLogin'=>function($event){
                Yii::$app->userlog->info("Login");
            }
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => require(__DIR__ . '/mailer.php'),
        'mailqueue'=>[
            'class'=>'nterms\mailqueue\MailQueue',
            'table'=>'{{%mail_queue}}'
        ],
        'log' => require(__DIR__.'/log.php'),
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            //'enableStrictParsing'=>true,
            //'showScriptName' => false,
            'rules' => require(__DIR__.'/url_rules.php'),
        ],
        'authManager' => [
            'class' => '\yii\rbac\DbManager',
            'db'=>$db,
        ],
        'pdf'=> require(__DIR__ . '/mpdf.php'),
        'userlog'=>[
            'class'=>'app\components\UserLogComponent',
            'db'=>  $db,
        ],
        'docmsg'=>[
            'class'=>'app\components\DocMsgComponent',
            'db'=>  $db,
        ],
        'docAttach'=>[
            'class'=>'app\components\DocAttachComponent',
            'db'=>  $db,
        ],
        'assetManager'=>[
            'class'=>'app\components\AppAssetManager',
           // 'buildVersion'=>$build_version,
            //'appendTimestamp'=>true, //เปิดแล้วมีปัญหากับ pjax 
            //'bundles'=>require(__DIR__.'/'.'assets-prod.php')
        ]
    ],
    'params' => $params,

    'as access' => [
        'class' => \yii\filters\AccessControl::className(),
        'rules' => [
            // allow guest users
            [
                'allow' => true,
                'actions' => ['login','reset-password','error'],
            ],
            // allow authenticated users
            [
                'allow' => true,
                'roles' => ['@'],
            ],
            // everything else is denied
        ],
//        'denyCallback' => function () {
//            return Yii::$app->response->redirect(['site/login']);
//        },
    ],

    'on afterRequest'=>function($event){
        $time_config = 60*60*3; //log user online at 3 hour
        $session = Yii::$app->session;
        if(!$session->has('_last_checkin')){
            $session['_last_checkin'] = time();
        }
        $last_checkin = $session['_last_checkin'];
        $current_checkin = time();
        $diff_time = $current_checkin - $last_checkin;
        //Yii::info('diff time (sec) '.$diff_time);
        if($diff_time>$time_config && !Yii::$app->user->isGuest){
            Yii::$app->userlog->info("Online");
            $session['_last_checkin'] = $current_checkin;
        }
    },

    'modules' => [
        // Module for rbac role management
        'rbac' => [
            'class' => 'johnitvn\rbacplus\Module',
            'beforeAction'=>function($action){
                Yii::info(['rbac beforeAction']);
                if(Yii::$app->user->can('admin/menu_main')){
                    return true;
                } else {
                    throw new \yii\web\ForbiddenHttpException("You are not allowed to access this page");
                    
                }
                return false;
            }
        ],
        'general'=>[
            'class'=> 'app\modules\general\General'
        ],
        'partner' => [
            'class' => 'app\modules\partner\Partner'
        ],       
        'resource'=>[
            'class' => 'app\modules\resource\Resource'
        ],      
        'admin'=>[
            'class'=>'app\modules\admin\AdminModule'
        ],
        'api'=>[
            'class'=>'app\modules\api\Api'
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        // enter optional module parameters below - only if you need to  
        // use your own export download action or custom translation 
        // message source
        // 'downloadAction' => 'gridview/export/download',
        // 'i18n' => []
        ],
    ]
    
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
    $config['components']['assetManager']['forceCopy'] = true;
    //$config['components']['assetManager']['appendTimestamp'] = true;
}

return $config;
