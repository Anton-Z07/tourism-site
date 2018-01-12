<?php
namespace v1;

use App\Controller\AbstractController,
    App\Model\Mapper\BaseMapper;

class SiteString extends AbstractController
{
    public function getConfig()
    {
        return [
            'entity' => 'App\Entity\SiteStringEntity',
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
                'ru' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'String'
                 ],
                'en' => [
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'validator'=>'String'
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

