<?php

return [
    'adminEmail' => 'admin@example.com',
    'class_model' => [
        'customer' => [
            'class' => 'common\models\Company',
            'primary_prefix' => 'C',
            'primary' => 'code',
        ],
        'supplier' => [
            'class' => 'common\models\Company',
            'primary_prefix' => 'S',
            'primary' => 'code',
        ],
        'manufacturer' => [
            'class' => 'common\models\Company',
            'primary_prefix' => 'M',
            'primary' => 'code',
        ],
        'product' => [
            'class' => 'common\models\Product',
            'primary_prefix' => '',
            'primary' => 'code',
        ],
        'itemalias' => [
            'class' => 'common\models\ItemAlias',
            'primary_prefix' => '',
            'primary' => 'id',
        ],
        'quotation' => [
            'class' => 'common\models\Bill',
            'primary_prefix' => 'QT',
            'primary' => 'code',
        ],
    ],
    'product_properties' => [
        'BOX' => ['od_w', 'od_l', 'od_h', 'id_w', 'id_l', 'id_h', 'weight', 'capacity'],
        'SPR' => ['od_w', 'od_l', 'od_h', 'weight', 'capacity'],
        'BIN' => ['od_w', 'od_l', 'od_h', 'weight', 'capacity'],
        'TAN' => ['od_w', 'od_l', 'od_h', 'diameter', 'weight', 'capacity'],
    ],
    'unit_properties' => [// 1=pc. , 3=mm. , 7=g. , 11=L.
        'od_w' => 3,
        'od_l' => 3,
        'od_h' => 3,
        'id_w' => 3,
        'id_l' => 3,
        'id_h' => 3,
        'weight' => 7,
        'capacity' => 11,
        'diameter' => 3,
    ],
    'color' => [// 1=pc. , 3=mm. , 7=g. , 11=L.
        'B' => ['solid'=>'#0036D9','text'=>'#FFFFFF'],
        'G' => ['solid'=>'#007700','text'=>'#FFFFFF'],
        'GY' => ['solid'=>'#888888','text'=>'#FFFFFF'],
        'R' => ['solid'=>'#FF0000','text'=>'#FFFFFF'],
        'Y' => ['solid'=>'#FFC926', 'text'=>'#FFFFFF'],
        'BL' => ['solid'=>'#000000','text'=>'#FFFFFF'],
        'BR' => ['solid'=>'#000000','text'=>'#FFFFFF'],
        'BS' => ['solid'=>'#000000','text'=>'#FFFFFF'],
        'O' => ['solid'=>'#000000','text'=>'#FFFFFF'],
        'W' => ['solid'=>'#FFFFFF','text'=>'#000000'],
    ],
];
