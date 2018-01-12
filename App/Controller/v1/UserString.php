<?php
namespace v1;

use App\Controller\AbstractController,
    App\Model\Mapper\BaseMapper;

class UserString extends AbstractController
{
    public function getConfig()
    {
        return [
            'entity' => 'App\Entity\UserStringEntity',
            'primary' => 'id',
            'fields' => [
                'id' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                 ],
                'resource_type' => [
                    'validator'=>'Constrain',
                    'options' => ['values' => ['none', 'test']],
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'modefire' => 'App\Modefire\Constrain'
                 ],
                'text' => [
                    'validator'=>'String',
                    'access' => BaseMapper::CONTROL_VISIBLE,
                 ],
                'status' => [
                    'validator'=>'Constrain',
                    'options' => ['values' => ['none', 'test']],
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'modefire' => 'App\Modefire\Constrain'
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

