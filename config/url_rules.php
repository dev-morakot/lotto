<?php

$rules = [
//    ['class' => 'yii\rest\UrlRule', 'controller' => ['api/purchase-order'],'extraPatterns'=>['GET search'=>'search']],
//    ['class' => 'yii\rest\UrlRule', 'controller' => ['api/account-product-group'],'extraPatterns'=>['GET search'=>'search']],
//    ['class' => 'yii\rest\UrlRule', 'controller' => ['api/stock-picking-type'],'extraPatterns'=>['GET search'=>'search']],
//    ['class' => 'yii\rest\UrlRule', 'controller' => ['api/product-category'],'extraPatterns'=>['GET search'=>'search']],
//    ['class' => 'yii\rest\UrlRule', 'controller' => ['api/account-account'],'extraPatterns'=>['GET search'=>'search']],
//    ['class' => 'yii\rest\UrlRule', 'controller' => ['api/stock-move'],'extraPatterns'=>['GET search'=>'search']],
    ['class' => 'yii\rest\UrlRule', 
        'controller' => [
            'api/account-account',
            'api/account-invoice',
            'api/account-payment',
            'api/account-tax',
            'api/account-product-group',
            'api/product-category',
            'api/product-supplierinfo',
            'api/product-uom',
            'api/purchase-order',
            'api/res-address',
            'api/res-partner',
            'api/res-users',
            'api/sale-area',
            'api/sale-order',
            'api/sale-order-line',
            'api/stock-goods-import',
            'api/stock-move',
            'api/stock-picking-type',
            'api/stock-lot',
            'api/stock-location',
            'api/purchase-receipt-type',
            'api/purchase-receipt-line'
            ],
        'extraPatterns'=>['GET search'=>'search','GET find'=>'find']
    ],
    ['class' => 'yii\rest\UrlRule', 
        'controller' => [
            'api/product-product',
            ],
        'extraPatterns'=>['GET search'=>'search','GET uom-ids'=>'uom-ids']
    ],
];

return $rules;