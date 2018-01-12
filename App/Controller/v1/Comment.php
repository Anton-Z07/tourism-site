<?php
namespace v1;

use App\Controller\AbstractController,
    App\Model\Mapper\BaseMapper;

class Comment extends AbstractController
{    
    public function getConfig()
    {
        return [
            'entity' => 'App\Entity\CommentEntity',
            'primary' => 'id',
            'fields' => [
                'id' => [
                    'access' => BaseMapper::CONTROL_VISIBLE
                 ],
                'user' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'resource' => 'v1\User',
                    'access' => BaseMapper::CONTROL_HIDDEN,
                    'foreign' => 'user_id'
                 ],
                'text' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'validator'=>'String',
                    'resource' => 'v1\UserString',
                    'access' => BaseMapper::CONTROL_ACL,
                    'inject' => 'text'
                 ],
                'resource_type' => [
                    'validator'=>'Constrain',
                    'options' => ['values' => [
                        0 => 'Неопределён',
                        1 => 'Достоприм.',
                        2 => 'Новости',
                        3 => 'Кухня',
                        4 => 'Фишки',
                        5 => 'Вел. Люди',
                        6 => 'Посты',
                        7 => 'Товары',
                        8 => 'Инфоблоки',
                        9 => 'Польз. файлы',
                        10 => 'События',
                        11 => 'Пути'
                    ]],
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'modefire' => 'App\Modefire\Constrain'
                 ],
                'like_count' => [],
                'view_count' => [],
                'like' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Like',
                    'foreign' => 'comment_id',
                    'otherkey' => 'like_id'
                 ],
                'post' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Post',
                    'foreign' => 'comment_id',
                    'otherkey' => 'post_id'
                 ],
                'people' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\People',
                    'foreign' => 'comment_id',
                    'otherkey' => 'people_id'
                 ],
                'landmark' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Landmark',
                    'foreign' => 'comment_id',
                    'otherkey' => 'landmark_id'
                 ],
                'news' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Landmark',
                    'foreign' => 'comment_id',
                    'otherkey' => 'news_id'
                 ],
                'feature' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Feature',
                    'foreign' => 'comment_id',
                    'otherkey' => 'feature_id'
                 ],
                'kitchen' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Kitchen',
                    'foreign' => 'comment_id',
                    'otherkey' => 'kitchen_id'
                 ],
                'goods' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Goods',
                    'foreign' => 'comment_id',
                    'otherkey' => 'goods_id'
                 ],
                'iblock' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Iblock',
                    'foreign' => 'comment_id',
                    'otherkey' => 'iblock_id'
                 ],
                'userfilegroup' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\UserFileGroup',
                    'foreign' => 'comment_id',
                    'otherkey' => 'user_file_id'
                 ],
                'event' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Event',
                    'foreign' => 'comment_id',
                    'otherkey' => 'event_id'
                 ],
                'route' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Route',
                    'foreign' => 'comment_id',
                    'otherkey' => 'route_id'
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

