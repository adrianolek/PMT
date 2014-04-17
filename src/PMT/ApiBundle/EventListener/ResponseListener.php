<?php

namespace PMT\ApiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseListener
{
  public function onKernelResponse(FilterResponseEvent $event)
  {
    $request = $event->getRequest();

    $event->getResponse()->headers->set('Access-Control-Allow-Origin', '*');
    $event->getResponse()->headers->set('Access-Control-Allow-Methods', '*');
    $event->getResponse()->headers->set('Access-Control-Allow-Headers', 'Accept, Content-Type, X-Auth-Token');
  }
}