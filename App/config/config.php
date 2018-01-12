<?php

return [
    'version' => 1,
    'productionMode' => false,
    'refreshCache' => false,
    'displayErrors' => true,
    'errorReporting' => E_ALL,
    'paths' => [
        'base' => realpath('../'),
        'data' => realpath('../').'/data',
        'upload' => realpath('.').'/upload',
        'entity' => 'Entity'
    ],
    'markets' => [
        'android' => [
            'serviceAccount' => '742966716131-9cd1psiqrh2s6a2scnq7euu4c5klqn58@developer.gserviceaccount.com',
            //'serviceAccount' => '777741590236-5vvbu580ullanve0q38kme5g3td6vb92@developer.gserviceaccount.com',
            'keyFile' => realpath('../').'/data/key.p12'
        ],
        'ios' => [
            'password' => 'vdv4cg8N',
            'sandbox' => false
        ]
    ],
    'db' => [
        'driver' => 'mysql',
        //'username' => 'root',
        'username' => 'anton_home',
        'host' => '164.215.70.6',
        //'password' => '',
        'password' => 'zse4xsw2CDE^',
        'database' => 'cultturist',
        'charset' => 'utf8',
        'collation' => null
    ],
    'files' => [
        'imagesizes' => [
            0 => ['type'=>'small', 'width' => 60, 'height' => 40],
            1 => ['type'=>'thumbnail', 'width' => 120, 'height' => 120],
            2 => ['type'=>'medium', 'width' => 214],
            3 => ['type'=>'medium2', 'width' => 375, 'height' => 667],
            4 => ['type'=>'medium3', 'width' => 460],
            5 => ['type'=>'big', 'width' => 700],
            6 => ['type'=>'big2', 'width' => 768, 'height' => 1024],
            7 => ['type'=>'big3', 'width' => 768, 'height' => 1334],
            8 => ['type'=>'original'],
        ],
        'mimi'=>[
            0 => ['type' => 'image/jpeg', 'ext'=>'jpg', 'handling'=>'images'],
            1 => ['type' => 'image/png', 'ext'=>'png', 'handling'=>'images'],
            2 => ['type' => 'application/mbtiles', 'ext'=>'mbtiles', 'handling'=>'maps']
        ]
    ],
    'userfiles' => [
        'imagesizes' => [
            0 => ['type'=>'small', 'width' => 60, 'height' => 60],
            1 => ['type'=>'thumbnail', 'width' => 120, 'height' => 120],
            2 => ['type'=>'medium', 'width' => 214],
            3 => ['type'=>'medium2', 'width' => 240],
            4 => ['type'=>'big', 'width' => 560],
            5 => ['type'=>'big2', 'width' => 700, 'height' => 260],
            6 => ['type'=>'original'],
        ],
        'mimi'=>[
            0 => ['type' => 'image/jpeg', 'ext'=>'jpg', 'handling'=>'image'],
            1 => ['type' => 'image/png', 'ext'=>'png', 'handling'=>'image']
        ]
    ],
    'search' => [
        'words' =>[
            'рим'
        ]
    ],
    'controller' => [
        'Luracast\\Restler\\Resources' => null,
        'App\\Controller\\Admin\\Admin' =>'admin',
        'App\\Controller\\Admin\\Cron' =>'admin/cron',
        'User' =>'api/user',
        'UserServices' =>'api/userservices',
        'UserServices' =>'api/user/auth',
        'UserFile' => 'api/userfile',
        'UserFileGroup' =>'api/userfilegroup',
        'UserString' => 'api/userstring',
        'UserProfile' => 'api/userprofile',
        'SiteString' => 'api/sitestring',
        'Area' => 'api/area',
        'File' => 'api/file',
        'FileGroup' =>'api/filegroup',
        'Comment' =>'api/comment',
        'Look' =>'api/look',
        'Like' =>'api/like',
        'Landmark' =>'api/landmark',
        'LandmarkProperty' =>'api/landmarkproperty',
        'People' =>'api/people',
        'Feature' =>'api/feature',
        'Kitchen' =>'api/kitchen',
        'Event' =>'api/event',
        'PlusAndMinus' =>'api/plusandminus',
        'Route' =>'api/route',
        'RoutePoint' =>'api/routepoint',
        'Package' =>'api/package',
        'Market' =>'api/market',
        'Search' =>'api/search',
        'Test' =>'api/test'
    ]
];
?>
