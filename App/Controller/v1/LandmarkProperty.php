<?php
namespace v1;

use App\Controller\AbstractController,
    App\Model\Mapper\BaseMapper;

class LandmarkProperty extends AbstractController
{
    public function getConfig()
    {
        return [
            'entity' => 'App\Entity\LandmarkPropertyEntity',
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
                'icon' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'File',
                    'resource' => 'v1\FileGroup'
                 ],
                'landmark' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Landmark',
                 ],
                'lower' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\LandmarkProperty',
                 ],
                'upper' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\LandmarkProperty',
                 ],
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
    }
}

