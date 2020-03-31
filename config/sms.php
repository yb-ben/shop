<?php


return [

    'driver' => 'aliyun',

    'aliyun' => [

        
        'key' => env('ALIYUN_KEY'),
        'secret' => env('ALIYUN_SECRET'),
        'regionId' => 'cn-hangzhou',

        'sendSms' => [
            'options' =>[

                'TemplateCode' => 'SMS_186946375',
                'SignName' => 'YUNA',
            ]
        ]


    ]

];
