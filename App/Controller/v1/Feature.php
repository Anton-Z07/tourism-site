<?php
namespace v1;

use App\Controller\AbstractController,
    App\Model\Mapper\BaseMapper;

class Feature extends AbstractController
{    
    public function getConfig()
    {
        return [
            'entity' => 'App\Entity\FeatureEntity',
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
                'text' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'String',
                    'resource' => 'v1\SiteString',
                    'inject' => 'ru',
                    'foreignkey' => 'text_id'
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

