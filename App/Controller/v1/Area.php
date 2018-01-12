<?php
namespace v1;

use App\Controller\AbstractController,
    App\Model\Mapper\BaseMapper;

class Area extends AbstractController
{
    public function getConfig()
    {
        return [
            'entity' => 'App\Entity\AreaEntity',
            'primary' => 'id',
            'fields' => [
                'id' => [
                    'access' => BaseMapper::CONTROL_VISIBLE
                ],
                'name' => [
                    'validator'=>'String',
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'resource' => 'v1\SiteString',
                    'inject' => 'ru',
                    'foreignkey' => 'name_id'
                 ],
                'alias' => [
                    'validator'=>'String',
                    'options' => ['max' => 255],
                    'access' => BaseMapper::CONTROL_VISIBLE
                ],
                'hash' => [
                    'validator'=>'String',
                    'options' => ['max' => 8],
                    'access' => BaseMapper::CONTROL_VISIBLE
                ],
                'area_type' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'Constrain',
                    'options' => ['values' => [
                        0 => 'Страна',
                        1 => 'Регион',
                        2 => 'Город'
                    ]],
                    'modefire' => 'App\Modefire\Constrain'
                 ],
                'rating' => [
                    'access' => BaseMapper::CONTROL_VISIBLE
                 ],
                'detail' => [
                    'validator'=>'String',
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'resource' => 'v1\SiteString',
                    'inject' => 'ru',
                    'foreignkey' => 'detail_id'
                 ],
                'transport_text' => [
                    'validator'=>'String',
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'resource' => 'v1\SiteString',
                    'inject' => 'ru',
                    'foreignkey' => 'transport_id'
                 ],
                'history_text' => [
                    'validator'=>'String',
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'resource' => 'v1\SiteString',
                    'inject' => 'ru',
                    'foreignkey' => 'history_id'
                 ],
                'landmark_text' => [
                    'validator'=>'String',
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'resource' => 'v1\SiteString',
                    'inject' => 'ru',
                    'foreignkey' => 'landmark_id'
                 ],
                'kitchen_text' => [
                    'validator'=>'String',
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'resource' => 'v1\SiteString',
                    'inject' => 'ru',
                    'foreignkey' => 'kitchen_id'
                 ],
                'people_text' => [
                    'validator'=>'String',
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'resource' => 'v1\SiteString',
                    'inject' => 'ru',
                    'foreignkey' => 'people_id'
                 ],
                'event_text' => [
                    'validator'=>'String',
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'resource' => 'v1\SiteString',
                    'inject' => 'ru',
                    'foreignkey' => 'event_id'
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
                'like_count' => [],
                'look_count' => [],
                'landmark_count' => [],
                'people_count' => [],
                'kitchen_count' => [],
                'feature_count' => [],
                'vicinity_count' => [],
                'latitude' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'Float',
                    'options' => ['min' => -90, 'max' => 90]
                 ],
                'longitude' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'Float',
                    'options' => ['min' => -180, 'max' => 180]
                 ],
                'map' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'File',
                    'options' => [],
                    'resource' => 'v1\FileGroup'
                 ],
                'flag' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'File',
                    'resource' => 'v1\FileGroup'
                 ],
                'image' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'File',
                    'resource' => 'v1\FileGroup'
                 ],
                'mobile_image' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'File',
                    'resource' => 'v1\FileGroup'
                 ],
                'mobile_map_file' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'File',
                    'resource' => 'v1\FileGroup'
                 ],
                'empty_image' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'File',
                    'resource' => 'v1\FileGroup'
                 ],
                'popular' => [
                    'validator'=>'Boolean',
                 ],
                'capital' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'Boolean',
                    'pivot' => 'v1\Area'
                 ],
                'vicinity' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'Boolean',
                    'pivot' => 'v1\Landmark'
                 ],
                'distance' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'pivot' => 'v1\Landmark'
                 ],
                'system' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'Boolean',
                    'pivot' => ['v1\Landmark', 'v1\People', 'v1\Feature', 'v1\Kitchen', 'v1\Event']
                 ],
                'people' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\People',
                 ],
                'landmark' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Landmark',
                 ],
                'event' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Event',
                 ],
                'tour' => [
                    'relation' => BaseMapper::RELATION_HASMANY,
                    'resource' => 'v1\Tour',
                 ],
                'routepoint' => [
                    'relation' => BaseMapper::RELATION_HASMANY,
                    'resource' => 'v1\RoutePoint',
                 ],
                'feature' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Feature',
                 ],
                'plusandminus' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\PlusAndMinus',
                 ],
                'kitchen' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Kitchen',
                 ],
                'lower' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Area',
                 ],
                'upper' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Area',
                 ],
                'gallery' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\UserFileGroup',
                 ],
                'post' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Post',
                 ],
                'like' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Like',
                 ],
                'look' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Look',
                 ],
                'package' => [
                    'relation' => BaseMapper::RELATION_HASONE,
                    'access' => BaseMapper::CONTROL_ACL,
                    'resource' => 'v1\Package',
                    'foreignkey' => 'area_id'
                 ],
                'created_at' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'DateTime',
                    'modefire' => 'App\Model\Modefire\DateTime'
                 ],
                'updated_at' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'DateTime',
                    'modefire' => 'App\Model\Modefire\DateTime'
                 ],
            ],
        ];
    }
}

