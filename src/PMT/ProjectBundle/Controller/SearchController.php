<?php

namespace PMT\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SearchController extends Controller
{
    /**
     * @Route("/search", name="search")
     * @Template
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $results = $em->getRepository('PMTTaskBundle:Task')->search($request->get('term'), $request->get('page', 1), $this->get('router'));

        return new JsonResponse(array(
            'results' => $results,
            'more' => count($results) > 0,
        ));
    }
}
