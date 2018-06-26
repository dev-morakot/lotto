<?php
// Log Configuration
return [
    'traceLevel' => YII_DEBUG ? 3 : 0,
    'targets' => [
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['info', 'warning', 'error'],
            'prefix' => function ($message) {
                $user = Yii::$app->has('user', true) ? Yii::$app->get('user') : null;
                $userID = $user ? $user->getId(false) : '-';
                return "[$userID]";
            },
            // Log server context
            //'logVars'=>['_SERVER','_GET','_POST','_FILES','_COOKIE','_SESSION'],
            'logVars' => [],
            // except db and web log
            'except' => [
                'yii\db*',
                'yii\web*'
            ]
        ],
        [
            'class' => 'yii\log\DbTarget',
            'levels' => ['info'],
            'categories' => ['userlog'],
            'db' => require(__DIR__ . '/db.php'),
            'prefix' => function ($message) {
                $user = Yii::$app->user;
                $userID = $user ? $user->identity->id : '-';
                $username = $user ? $user->identity->username : '-';
                $request = Yii::$app->request;
                $userIP = $request->userIP;
                return "[$userID][$username][$userIP]";
            },
            'logVars' => [],
            'except' => [
                'yii\db*',
                'yii\web*',
            ]
        ],
        [
            'class' => 'yii\log\FileTarget',
            'logFile'=>'@runtime/logs/audit.log',
            'levels' => ['trace'],
            'categories'=>['audit'],
            'prefix' => function ($message) {
                $user = Yii::$app->has('user', true) ? Yii::$app->get('user') : null;
                $userID = $user ? $user->getId(false) : '-';
                return "[$userID]";
            },
            // Log server context
            //'logVars'=>['_SERVER','_GET','_POST','_FILES','_COOKIE','_SESSION'],
            'logVars' => [],
            // except db and web log
            'except' => [
                'yii\db*',
                'yii\web*'
            ]
        ],
    ],
];
