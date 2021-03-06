<?php

namespace Iphp\FileStoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Iphp\FileStoreBundle\DependencyInjection\Compiler\FormPass;

class IphpFileStoreBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new FormPass());
    }

}
