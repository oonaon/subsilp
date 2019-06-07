<?php
return [
    'adminEmail' => 'admin@example.com',
    
    'class_model'=>[
        'cus'=>[
            'class'=>'common\models\Company',
            'primary_prefix'=>'C',
            'primary'=>'code',
        ],
        'sup'=>[
            'class'=>'common\models\Company',
            'primary_prefix'=>'S',
            'primary'=>'code',
        ],
        'man'=>[
            'class'=>'common\models\Company',
            'primary_prefix'=>'M',
            'primary'=>'code',
        ],
        
    ],
];
