<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests/codeception');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');


$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'session',
        'log'
        ],
    'controllerNamespace' => 'app\commands',
    'language'=>'th_TH',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'thaiFormatter'=>[
            'class'=>'dixonsatit\thaiYearFormatter\ThaiYearFormatter',
            //'nullDisplay'=>'-'
            //'dateFormat'=>'php:d/m/Y',
        ],
        'formatter'=>[
            'class'=>'yii\i18n\Formatter',
            'dateFormat'=>'php:d/m/Y',
            'datetimeFormat'=>'php:d/m/Y H:i:s',
            'timeFormat'=>'php:H:i:s',
            'currencyCode'=>'฿',
            'decimalSeparator'=>'.',
            'thousandSeparator'=>',',
        ],
        'user' => [
            'class'=>'yii\web\User',
            'identityClass' => 'app\models\User',
        ],
        'session'=>[
            'class'=>'yii\web\Session'
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info','error', 'warning'],
                    'logFile'=>'@runtime/logs/console.log',
                    'logVars' => [],
                    // except db and web log
                    'except' => [
                        'yii\db*',
                        'yii\web*'
                    ]
                ],
//                [
//                    'class'=>'yii\log\DbTarget',
//                    'levels' => ['info'],
//                    'categories' => ['userlog'],
//                    'db' => require(__DIR__ . '/db.php'),
//                ],
            ],
        ],
        'mailer' => require(__DIR__ . '/mailer.php'),
        'mailqueue'=>[
            'class'=>'nterms\mailqueue\MailQueue',
            'table'=>'{{%mail_queue}}'
        ],
        'db' => $db,
       
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'db'=>require(__DIR__.'/db.php'),
        ],
        'userlog'=>[
            'class'=>'app\components\UserLogComponent',
            'db'=>  require(__DIR__.'/db.php'),
        ],
        'docmsg'=>[
            'class'=>'app\components\DocMsgComponent',
            'db'=>  require(__DIR__.'/db.php'),
        ],
    ],
    'params' => $params,
    
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
