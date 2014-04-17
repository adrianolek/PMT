<?php

namespace PMT\ApiBundle;

use PMT\ApiBundle\DependencyInjection\Security\Factory\ApiFactory;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PMTApiBundle extends Bundle
{

  public function build(ContainerBuilder $container)
  {
    parent::build($container);

    $extension = $container->getExtension('security');
    $extension->addSecurityListenerFactory(new ApiFactory());
  }

}
