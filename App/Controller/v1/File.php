<?php
namespace v1;

use App\Controller\AbstractController,
    App\Model\Mapper\BaseMapper,
    App\Application;

class File extends AbstractController
{
    public function getConfig()
    {
        return [
            'entity' => 'App\Entity\FileEntity',
            'primary' => 'id',
            'fields' => [
                'id' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                 ],
                'group' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'resource' => 'v1\FileGroup',
                    'access' => BaseMapper::CONTROL_VISIBLE
                 ],
                'mimi_type' => [
                    'validator'=>'Constrain',
                    'options' => ['values' => Application::getUploadMimi()],
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'modefire' => 'App\Modefire\Constrain'
                 ],
                'path' => [
                    'validator'=>'String',
                    'access' => BaseMapper::CONTROL_VISIBLE,
                 ],
                'width' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                 ],
                'height' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                 ],
                'size' => [
                    'validator'=>'Constrain',
                    'options' => ['values' => Application::getImageUploadSizes()],
                    'control' => BaseMapper::CONTROL_VISIBLE,
                    'resource' => 'App\Modefire\Constrain'
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

