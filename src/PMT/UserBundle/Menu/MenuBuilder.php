<?php
namespace PMT\UserBundle\Menu;

use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class MenuBuilder
{
    private $factory;
    private $securityContext;
    private $em;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, SecurityContext $securityContext, EntityManager $em)
    {
        $this->factory = $factory;
        $this->securityContext = $securityContext;
        $this->em = $em;
    }

    public function createMainMenu(Request $request)
    {
        /* @var $menu \Knp\Menu\ItemInterface */
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        if ($this->securityContext->isGranted('ROLE_USER')) {
            $projectsMenu = $menu->addChild('Projects', array('route' => 'projects'));
            $projectsMenu->setAttribute('dropdown', true);
            $projectsMenu->addChild('List', array('route' => 'projects'))
                ->setAttribute('divider_append', true);

            $projects = $this->em->getRepository('PMTProjectBundle:Project')->findFor(
                $this->securityContext->isGranted('ROLE_MANAGER'),
                $this->securityContext->getToken()->getUser()
            );

            foreach ($projects as $project) {
                $projectsMenu->addChild(
                    $project->getName(),
                    array('route' => 'project_tasks', 'routeParameters' => array('project_id' => $project->getId()))
                );
            }

            $menu->addChild('People', array('route' => 'people'));
            $menu->addChild(
                'Time Tracking',
                array(
                    'route' => 'user_tracking',
                    'routeParameters' => array('id' => $this->securityContext->getToken()->getUser()->getId()),
                )
            );
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
