<?php

namespace Iphp\TreeBundle\Admin;



class DatagridBuilder extends \Sonata\DoctrineORMAdminBundle\Builder\DatagridBuilder
{


    /**
         * @param \Sonata\AdminBundle\Admin\AdminInterface $admin
         * @param array $values
         * @return \Sonata\AdminBundle\Datagrid\DatagridInterface
         */
        public function getBaseDatagrid(AdminInterface $admin, array $values = array())
        {
            $pager = new Pager;
            $pager->setCountColumn($admin->getModelManager()->getIdentifierFieldNames($admin->getClass()));

            $formBuilder = $this->formFactory->createNamedBuilder('form', 'filter', array(), array('csrf_protection' => false));

            return new Datagrid($admin->createQuery(), $admin->getList(), $pager, $formBuilder, $values);
        }
}