<?php
namespace v1;

use App\Controller\AbstractController,
    App\Model\Mapper\BaseMapper;

class Like extends AbstractController
{
    
    public function getConfig()
    {
        return [
            'entity' => 'App\Entity\LikeEntity',
            'primary' => 'id',
            'fields' => [
                'id' => [
                    'access' => BaseMapper::CONTROL_VISIBLE
                 ],
                'user' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'resource' => 'v1\User',
                    'foreignkey' => 'user_id',
                    'selfkey' => 'id'
                 ],
                'resource_type' => [
                    'validator'=>'Constrain',
                    'options' => ['values' => [
                        0 => 'Неопределён',
                        1 => 'Посты',
                        2 => 'Комментарии',
                        3 => 'Вел. Люди',
                        4 => 'Гео объекты',
                        5 => 'Достоприм.',
                        6 => 'Новости',
                        7 => 'Фишки',
                        8 => 'Кухня',
                        9 => 'Товары',
                        10 => 'Инфоблоки',
                        11 => 'Конкурсы',
                        12 => 'События',
                        13 => 'Пути'
                    ]],
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'modefire' => 'App\Modefire\Constrain'
                 ],
                'area' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Area',
                    'foreignkey' => 'like_id',
                    'selfkey' => 'area_id'
                 ],
                'comment' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Comment',
                    'foreignkey' => 'like_id',
                    'selfkey' => 'comment_id'
                 ],
                'post' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Post',
                    'foreignkey' => 'like_id',
                    'selfkey' => 'post_id'
                 ],
                'people' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\People',
                    'foreignkey' => 'like_id',
                    'selfkey' => 'people_id'
                 ],
                'landmark' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Landmark',
                    'foreignkey' => 'like_id',
                    'selfkey' => 'landmark_id'
                 ],
                'news' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Landmark',
                    'foreignkey' => 'like_id',
                    'selfkey' => 'news_id'
                 ],
                'feature' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Feature',
                    'foreignkey' => 'like_id',
                    'selfkey' => 'feature_id'
                 ],
                'kitchen' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Kitchen',
                    'foreignkey' => 'like_id',
                    'selfkey' => 'kitchen_id'
                 ],
                'goods' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Goods',
                    'foreignkey' => 'like_id',
                    'selfkey' => 'goods_id'
                 ],
                'iblock' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Iblock',
                    'foreignkey' => 'like_id',
                    'selfkey' => 'iblock_id'
                 ],
                'contests' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Contests',
                    'foreignkey' => 'like_id',
                    'selfkey' => 'contest_id'
                 ],
                'event' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Event',
                    'foreignkey' => 'like_id',
                    'selfkey' => 'event_id'
                 ],
                'route' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Route',
                    'foreignkey' => 'like_id',
                    'selfkey' => 'route_id'
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

