<?php
namespace PMT\UserBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class MenuBuilder
{
  private $factory;
  private $securityContext;

  /**
   * @param FactoryInterface $factory
   */
  public function __construct(FactoryInterface $factory, SecurityContext $securityContext)
  {
    $this->factory = $factory;
    $this->securityContext = $securityContext;
  }

  public function createMainMenu(Request $request)
  {
    /* @var $menu \Knp\Menu\ItemInterface */
    $menu = $this->factory->createItem('root');
    $menu->setChildrenAttribute('class', 'nav navbar-nav');

    if ($this->securityContext->isGranted('ROLE_USER')) {
        $menu->addChild('Projects', array('route' => 'projects'));
        $menu->addChild('People', array('route' => 'people'));
        $menu->addChild('Time Tracking', array(
            'route' => 'user_tracking',
            'routeParameters' => array('id' => $this->securityContext->getToken()->getUser()->getId()),
        ));
    }

    return $menu;
  }

  public function createRightMenu(Request $request)
  {
    /* @var $menu \Knp\Menu\ItemInterface */
    $menu = $this->factory->createItem('root');
    $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');

    if ($this->securityContext->isGranted('ROLE_USER')) {
      $menu->addChild('Log out', array('route' => 'logout'));
    }

    return $menu;
  }
}
