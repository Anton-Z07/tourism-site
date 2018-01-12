<?php
namespace v1;

use App\Controller\AbstractController,
    App\Model\Mapper\BaseMapper;

class UserServices extends AbstractController
{    
    public function getConfig()
    {
        return [
            'entity' => 'App\Entity\UserServicesEntity',
            'primary' => 'id',
            'fields' => [
                'id' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                 ],
                'user' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'resource' => 'v1\User',
                    'access' => BaseMapper::CONTROL_VISIBLE,
                 ],
                'login' => [
                    'validator'=>'String',
                    'options' => ['max' => 50],
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'modefire' => 'App\Modefire\Constrain'
                 ],
                'password' => [
                    'validator'=>'String',
                    'options' => ['min' => 0, 'max' => 40],
                    'access' => BaseMapper::CONTROL_ACL,
                    'modefire' => 'App\Modefire\Constrain'
                 ],
                'token' => [
                    'validator'=>'String',
                    'options' => ['max' => 255],
                    'access' => BaseMapper::CONTROL_ACL,
                    'modefire' => 'App\Modefire\Constrain'
                 ],
                'refresh_token' => [
                    'validator'=>'String',
                    'options' => ['max' => 255],
                    'access' => BaseMapper::CONTROL_ACL,
                    'modefire' => 'App\Modefire\Constrain'
                 ],
                'token_expire' => [
                    'validator'=>'DateTime',
                    'modefire' => 'App\Model\Modefire\DateTime',
                    'access' => BaseMapper::CONTROL_ACL
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

