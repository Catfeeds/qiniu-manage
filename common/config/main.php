<?php
return [
    'language' => 'zh-CN',
    'timeZone'=>'Asia/Chongqing',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\redis\Cache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '.html',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['127.0.0.1', '::1', '192.168.10.*'],
            'generators' => [
                'crud' => [
                    'class' => 'common\gii\crud\Generator',
                    'templates' => [
                        'banshan' => '@common/gii/crud/banshan',
                    ]
                ],
                'model' => [
                    'class' => 'common\gii\model\Generator',
                    'templates' => [
                        'banshan' => '@common/gii/model/banshan',
                    ]
                ]
            ],
        ]
    ],
];
