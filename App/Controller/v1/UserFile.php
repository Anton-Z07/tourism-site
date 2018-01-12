<?php
namespace v1;

use App\Controller\AbstractController,
    App\Model\Mapper\BaseMapper,
    App\Application;

class UserFile extends AbstractController
{
    public function getConfig()
    {
        return [
            'entity' => 'App\Entity\UserFileEntity',
            'primary' => 'id',
            'fields' => [
                'id' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                 ],
                'filegroup' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'resource' => 'v1\UserFileGroup',
                    'access' => BaseMapper::CONTROL_VISIBLE
                 ],
                'mimi_type' => [
                    'validator'=>'Constrain',
                    'options' => ['values' => Application::getUserUploadMimi()],
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'modefire' => 'App\Modefire\Constrain'
                 ],
                'path' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'String'
                 ],
                'width' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                 ],
                'height' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                 ],
                'size' => [
                    'validator'=>'Constrain',
                    'options' => ['values' => Application::getUserImageUploadSizes()],
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

