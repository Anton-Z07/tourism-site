<?php
namespace v1;

use App\Controller\AbstractController,
    App\Model\Mapper\BaseMapper;

class Route extends AbstractController
{    
    public function getConfig()
    {
        return [
            'entity' => 'App\Entity\RouteEntity',
            'primary' => 'id',
            'fields' => [
                'id' => [
                    'access' => BaseMapper::CONTROL_VISIBLE
                 ],
                'name' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'String',
                    'resource' => 'v1\SiteString',
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'inject' => 'ru'
                 ],
                'text' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'String',
                    'resource' => 'v1\SiteString',
                    'inject' => 'ru'
                 ],
                'full_text' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'String',
                    'resource' => 'v1\SiteString',
                    'inject' => 'ru'
                 ],
                'helpful_text' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'String',
                    'resource' => 'v1\SiteString',
                    'inject' => 'ru'
                 ],
                'alias' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'String',
                    'options' => ['max' => 200]
                 ],
                'type' => [
                    'validator'=>'Constrain',
                    'options' => ['values' => ['none', 'test']],
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'modefire' => 'App\Modefire\Constrain'
                 ],
                'like_count' => [],
                'view_count' => [],
                'comment_count' => [],
                'points' => [
                    'relation' => BaseMapper::RELATION_HASMANY,
                    'resource' => 'v1\RoutePoint'
                 ],
                'iblock' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Iblock',
                 ],
                'landmark' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Landmark',
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
                'created_at' => [
                    'validator'=>'DateTime',
                    'modefire' => 'App\Model\Modefire\DateTime'
                 ],
                'updated_at' => [
                    'validator'=>'DateTime',
                    'modefire' => 'App\Model\Modefire\DateTime'
                 ],
            ]
        ];
    }
}

