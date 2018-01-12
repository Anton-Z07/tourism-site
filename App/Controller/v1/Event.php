<?php
namespace v1;

use App\Controller\AbstractController,
    App\Model\Mapper\BaseMapper;

class Event extends AbstractController
{    
    public function getConfig()
    {
        return [
            'entity' => 'App\Entity\EventEntity',
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
                'original_name' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'String',
                    'resource' => 'v1\SiteString',
                    'inject' => 'ru'
                 ],
                'text' => [
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
                'view_count' => [],
                'comment_count' => [],
                'repeat' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'Boolean',
                 ],
                'date' => [
                    'relation' => BaseMapper::RELATION_HASMANY,
                    'resource' => 'v1\EventDate',
                    'access' => BaseMapper::CONTROL_HIDDEN,
                 ],
                'system' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'Boolean',
                    'pivot' => 'v1\Area'
                 ],
                'area' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Area',
                 ],
                'iblock' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Iblock',
                 ],
                'comment' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Comment',
                 ],
                'like' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Like',
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

