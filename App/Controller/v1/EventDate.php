<?php
namespace v1;

use App\Controller\AbstractController,
    App\Model\Mapper\BaseMapper;

class EventDate extends AbstractController
{    
    public function getConfig()
    {
        return [
            'entity' => 'App\Entity\EventDateEntity',
            'primary' => 'id',
            'fields' => [
                'id' => [
                    'access' => BaseMapper::CONTROL_VISIBLE
                 ],
                'event' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'resource' => 'v1\Event',
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'foreignkey' => 'event_id'
                 ],
                'start' => [
                    'validator'=>'DateTime',
                    'modefire' => 'App\Model\Modefire\DateTime',
                    'access' => BaseMapper::CONTROL_VISIBLE
                 ],
                'end' => [
                    'validator'=>'DateTime',
                    'modefire' => 'App\Model\Modefire\DateTime',
                    'access' => BaseMapper::CONTROL_VISIBLE
                 ],
                'created_at' => [
                    'validator'=>'DateTime',
                    'modefire' => 'App\Model\Modefire\DateTime'
                 ],
                'updated_at' => [
                    'validator'=>'DateTime',
                    'modefire' => 'App\Model\Modefire\DateTime'
                 ]
            ],
        ];
    }
}

