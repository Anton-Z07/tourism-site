<?php
namespace v1;

use App\Controller\AbstractController,
    App\Model\Mapper\BaseMapper;

class Landmark extends AbstractController
{
    public function getConfig()
    {

        $config =
        [
            'entity' => 'App\Entity\LandmarkEntity',
            'primary' => 'id',
            'fields' => [
                'id' => [
                    'access' => BaseMapper::CONTROL_VISIBLE
                ],
                'name' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'String',
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'resource' => 'v1\SiteString',
                    'inject' => 'ru',
                    'foreignkey' => 'name_id'
                 ],
                'original_name' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'String',
                    'resource' => 'v1\SiteString',
                    'inject' => 'ru',
                    'foreignkey' => 'original_name_id'
                 ],
                'text' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'String',
                    'resource' => 'v1\SiteString',
                    'inject' => 'ru',
                    'foreignkey' => 'text_id'
                 ],
                'full_text' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'String',
                    'resource' => 'v1\SiteString',
                    'inject' => 'ru',
                    'foreignkey' => 'full_text_id'
                 ],
                'alias' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'String',
                    'options' => ['max' => 255]
                ],
                'rating' => [
                    'access' => BaseMapper::CONTROL_VISIBLE
                 ],
                'status' => [
                    'validator'=>'Constrain',
                    'options' => ['values' => [
                        0 => 'Недоступен',
                        1 => 'Доступен'
                    ]],
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'modefire' => 'App\Modefire\Constrain'
                 ],
                'sticky' => [
                    'validator'=>'Boolean',
                ],
                'like_count' => [],
                'view_count' => [],
                'comment_count' => [],
                'build' => [
                    'validator'=>'DateTime',
                    'modefire' => 'App\Model\Modefire\DateTime'
                 ],
                'build_year' => [
                    'options'=>['min' => -3000, 'max' => 2100]
                 ],
                'build_abt' => [
                    'validator'=>'Boolean'
                 ],
                'adress' => [
                    'validator'=>'String',
                    'options' => ['max'=>250],
                 ],
//                'latitude' => [
//                    'validator'=>'Float',
//                    'options' => ['min' => -90, 'max' => 90],
//                    'access' => BaseMapper::CONTROL_VISIBLE,
//                 ],
//                'longitude' => [
//                    'validator'=>'Float',
//                    'options' => ['min' => -180, 'max' => 180],
//                    'access' => BaseMapper::CONTROL_VISIBLE,
//                 ],
                'regional' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'Boolean'
                 ],
                'image' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'File',
                    'resource' => 'v1\FileGroup'
                 ],
                'vicinity' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'Boolean',
                    'pivot' => 'v1\Area'
                 ],
                'distance' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'pivot' => 'v1\Area'
                 ],
                'system' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'Boolean',
                    'pivot' => 'v1\Area'
                 ],
                'gallery' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\UserFileGroup',
                 ],
                'property' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\LandmarkProperty',
                 ],
                'childiblock' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Iblock',
                 ],
                'parentiblock' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Iblock',
                 ],
                'routepoint' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\RoutePoint',
                 ],
                'route' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Route',
                 ],
                'area' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Area',
                 ],
                'comment' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Comment',
                 ],
                'like' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Like',
                 ],
                'look' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Look',
                 ],
                'closest_landmarks' => [],
                'created_at' => [
                    'validator'=>'DateTime',
                    'modefire' => 'App\Model\Modefire\DateTime'
                 ],
                'updated_at' => [
                    'validator'=>'DateTime',
                    'modefire' => 'App\Model\Modefire\DateTime'
                 ],
            ],
        ];

        $latitude = [
            'validator' => 'Float',
            'options' => ['min' => -90, 'max' => 90],
            'access' => BaseMapper::CONTROL_VISIBLE,
        ];

        $longitude = [
            'validator' => 'Float',
            'options' => ['min' => -180, 'max' => 180],
            'access' => BaseMapper::CONTROL_VISIBLE,
        ];

        if (1==1)
        {
            $config['fields']['latitude'] = $latitude;
            $config['fields']['longitude'] = $longitude;
        }

        return $config;
    }
}

