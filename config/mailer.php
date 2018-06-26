<?php

return [
    'class' => 'yii\swiftmailer\Mailer',
    'viewPath' => '@app/mail',
    'useFileTransport' => false,
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp.gmail.com',
        'username' => 'erp@bicchemical.com',
        'password' => 'bic-7777',
        'port' => '465',
        'encryption' => 'ssl',
    ],
];
