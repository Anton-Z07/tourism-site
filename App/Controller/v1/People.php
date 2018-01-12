<?php
namespace v1;

use App\Controller\AbstractController,
    App\Model\Mapper\BaseMapper;

class People extends AbstractController
{    
    public function getConfig()
    {
        return [
            'entity' => 'App\Entity\PeopleEntity',
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
                'detail' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'String',
                    'resource' => 'v1\SiteString',
                    'inject' => 'ru',
                    'foreignkey' => 'detail_id'
                 ],
                'alias' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'String',
                    'options' => ['max' => 255]
                 ],
                'born' => [
                    'validator'=>'DateTime',
                    'modefire' => 'App\Model\Modefire\DateTime'
                 ],
                'born_year' => [
                    'options'=>['min' => -3000, 'max' => 2100]
                 ],
                'born_abt' => [
                    'validator'=>'Boolean',
                 ],
                'death' => [
                    'validator'=>'DateTime',
                    'modefire' => 'App\Model\Modefire\DateTime'
                 ],
                'death_year' => [
                    'options'=>['min' => -3000, 'max' => 2100]
                 ],
                'death_abt' => [
                    'validator'=>'Boolean',
                 ],
                'alive' => [
                    'validator'=>'Boolean',
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
                'system' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'Boolean',
                    'pivot' => 'v1\Area'
                 ],
                'image' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'File',
                    'resource' => 'v1\FileGroup'
                 ],
                'web_image' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'File',
                    'resource' => 'v1\FileGroup'
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

