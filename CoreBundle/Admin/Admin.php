<?php

namespace Iphp\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin as BaseAdmin;

class Admin extends BaseAdmin
{
    public function prePersist($entity)
    {
        if (method_exists($entity, 'setUpdatedBy')) $entity->setUpdatedBy($this->getCurrentUser());
        if (method_exists($entity, 'setCreatedBy')) $entity->setCreatedBy($this->getCurrentUser());
        if (method_exists($entity, 'setCreatedAt')) $entity->setCreatedAt(new \DateTime);
        if (method_exists($entity, 'setUpdatedAt')) $entity->setUpdatedAt(new \DateTime);
    }


    public function preUpdate($entity)
    {
        if (method_exists($entity, 'setUpdatedBy')) $entity->setUpdatedBy($this->getCurrentUser());
        if (method_exists($entity, 'setUpdatedAt')) $entity->setUpdatedAt(new \DateTime);
    }

    protected function getCurrentUser()
    {
        return $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();
    }

}