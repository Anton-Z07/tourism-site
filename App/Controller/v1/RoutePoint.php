<?php
namespace v1;

use App\Controller\AbstractController,
    App\Model\Mapper\BaseMapper;

class RoutePoint extends AbstractController
{    
    public function getConfig()
    {
        return [
            'entity' => 'App\Entity\RoutePointEntity',
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
                    'inject' => 'ru'
                 ],
                'latitude' => [
                    'validator'=>'Float',
                    'access' => BaseMapper::CONTROL_VISIBLE,
                 ],
                'longitude' => [
                    'validator'=>'Float',
                    'access' => BaseMapper::CONTROL_VISIBLE,
                 ],
                'day' => [
                    'options' => ['min'=>1],
                    'access' => BaseMapper::CONTROL_VISIBLE,
                 ],                
                'halt' => [
                    'validator'=>'Boolean',
                    'access' => BaseMapper::CONTROL_VISIBLE,
                 ],
                'haltpoint' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\RoutePoint',
                 ],
                'haltlesspoint' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\RoutePoint',
                 ],
                'landmark' => [
                    'relation' => BaseMapper::RELATION_BELONGSTOMANY,
                    'resource' => 'v1\Landmark',
                 ],
                'route' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'resource' => 'v1\Route',
                 ],
                'area' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'resource' => 'v1\Area',
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

