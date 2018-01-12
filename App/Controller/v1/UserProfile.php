<?php
namespace v1;

use App\Controller\AbstractController,
    App\Model\Mapper\BaseMapper;

class UserProfile extends AbstractController
{
    public function getConfig()
    {
        return [
            'entity' => 'App\Entity\UserProfileEntity',
            'primary' => 'id',
            'fields' => [
                'id' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                 ],
                'user' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'resource' => 'v1\User',
                    'foreignkey' => 'user_id'
                 ],
                'name' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'String',
                    'options' => ['min'=>0, 'max'=>200]
                 ],
                'last_name' => [
                    'access' => BaseMapper::CONTROL_ACL,
                    'validator'=>'String',
                    'options' => ['min'=>0, 'max'=>200]
                 ],
                'born' => [
                    'validator'=>'DateTime',
                    'modefire' => 'App\Model\Modefire\DateTime'
                 ],
                'show_born' => [
                    'validator'=>'Boolean',
                    'options' => ['default'=>true],
                 ],
                'avatar' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'resource' => 'v1\UserFileGroup',
                    'foreignkey' => 'id',
                    'selfkey' => 'avatar_id'
                 ],
                'from_country' => [
                    'access' => BaseMapper::CONTROL_ACL,
                 ],
                'from_city' => [
                    'access' => BaseMapper::CONTROL_ACL,
                 ],
                'show_from' => [
                    'validator'=>'Boolean',
                    'options' => ['default'=>true],
                 ],
                'hobby' => [
                    'validator'=>'String',
                    'options' => ['max'=>255],
                    'access' => BaseMapper::CONTROL_ACL,
                 ],
                'show_hobby' => [
                    'validator'=>'Boolean',
                    'options' => ['default'=>true],
                 ],
                'place' => [
                    'validator'=>'String',
                    'options' => ['max'=>255],
                    'access' => BaseMapper::CONTROL_ACL,
                 ],
                'show_place' => [
                    'validator'=>'Boolean',
                    'options' => ['default'=>true],
                 ],
                'guide' => [
                    'validator'=>'Boolean',
                    'options' => ['default'=>false],
                 ],
                'phone' => [
                    'validator'=>'Phone',
                    'options' => ['max'=>50],
                    'access' => BaseMapper::CONTROL_ACL,
                 ],
                'email' => [
                    'validator'=>'Email',
                    'options' => ['max'=>255],
                    'access' => BaseMapper::CONTROL_ACL,
                 ],
                'show_email' => [
                    'validator'=>'Boolean',
                    'options' => ['default'=>false],
                 ],
                'show_posts' => [
                    'validator'=>'Boolean',
                    'options' => ['default'=>true],
                 ],
                'show_activity' => [  
                    'validator'=>'Boolean',
                    'options' => ['default'=>true],
                 ],
                'show_service' => [
                    'validator'=>'Boolean',
                    'options' => ['default'=>true],
                 ]
            ],
        ];
    }
}

