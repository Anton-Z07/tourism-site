<?php
namespace v1;

use App\Controller\AbstractController,
    App\Model\Mapper\BaseMapper;

class User extends AbstractController
{    
    public function getConfig()
    {
        return [
            'entity' => 'App\Entity\UserEntity',
            'primary' => 'id',
            'fields' => [
                'id' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                 ],
                'user_status' => [
                    'validator'=>'Constrain',
                    'options' => ['values' => [
                        0 => 'Не активирован',
                        1 => 'Активирован',
                        2 => 'Забанен'
                    ]],
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'modefire' => 'App\Modefire\Constrain'
                 ],
                'points' => [
                    'access' => BaseMapper::CONTROL_ACL,
                 ],
                'system_user' => [
                    'validator'=>'Boolean',
                 ],
                'profile' => [
                    'relation' => BaseMapper::RELATION_HASONE,
                    'resource' => 'v1\UserProfile',
                    'foreignkey' => 'user_id',
                    'selfkey' => 'id'
                 ],
                'services' => [
                    'relation' => BaseMapper::RELATION_HASMANY,
                    'resource' => 'v1\UserServices',
                    'foreignkey' => 'user_id',
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

