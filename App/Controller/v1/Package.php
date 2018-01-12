<?php
namespace v1;

use App\Controller\AbstractController,
    App\Model\Mapper\BaseMapper,
    App\Model\Utils\PackageProcessor;

class Package extends AbstractController
{    
    public function getConfig()
    {
        return [
            'entity' => 'App\Entity\PackageEntity',
            'primary' => 'id',
            'fields' => [
                'id' => [
                    'access' => BaseMapper::CONTROL_VISIBLE
                 ],
                'name' => [
                    'validator'=>'String',
                    'options' => ['max' => 32],
                    'access' => BaseMapper::CONTROL_HIDDEN
                ],
                'area' => [
                    'relation' => BaseMapper::RELATION_BELONGSTO,
                    'access' => BaseMapper::CONTROL_VISIBLE,
                    'resource' => 'v1\Area',
                    'foreignkey' => 'area_id',
                    'selfkey' => 'id'
                 ],
                'hash' => [
                    'validator'=>'String',
                    'options' => ['max' => 255],
                    'access' => BaseMapper::CONTROL_VISIBLE
                ],
                'path' => [
                    'validator'=>'String',
                    'options' => ['max' => 255],
                    'access' => BaseMapper::CONTROL_ACL
                ],
                'size' => [
                    'access' => BaseMapper::CONTROL_VISIBLE
                ],
                'created_at' => [
                    'validator'=>'DateTime',
                    'modefire' => 'App\Model\Modefire\DateTime',
                    'access' => BaseMapper::CONTROL_VISIBLE
                 ],
                'updated_at' => [
                    'validator'=>'DateTime',
                    'modefire' => 'App\Model\Modefire\DateTime',
                    'access' => BaseMapper::CONTROL_VISIBLE
                 ],
            ]
        ];
    }
    
    public function eventEnd($entity, $parent = null, $parentField = null)
    {
        (new PackageProcessor())->collectPackage($entity);
        $entity->save();
    }
}

