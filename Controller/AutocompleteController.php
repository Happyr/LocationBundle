<?php

namespace HappyR\LocationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * In this controller we only serve the autocomplate for the Location bundle
 *
 *
 * @Route("/public/location/autocomplete")
 *
 *
 * @author tobias
 *
 */
class AutocompleteController extends Controller
{

    protected function genericAction($type)
    {
        $searchTerm=$this->get('request')->query->get('term','');

        $data=array();

        if (strlen($searchTerm)>=3) {
            $em=$this->getDoctrine()->getManager();
            $objects = $em->getRepository($type)->searchByName($searchTerm);

            /*
             * Prepare data
             *
             */
             foreach ($objects as $o) {
                 $data[]=$o->getName();
             }

        }

         return $data;

    }

    /**
     * Create a json response with $data as data
     *
     * @param array $data
     */
    protected function createJsonResponse(array $data)
    {
        return new Response(json_encode($data),200,array(
                'Content-Type'=>'application/json')
         );
    }

    /**
     * @Route("/city", name="_public_location_autocomplete_city")
     * @Template()
     */
    public function cityAction()
    {
        $data= $this->genericAction('EastitDarwinLocationBundle:City');

        return $this->createJsonResponse($data);
    }

    /**
     * @Route("/municipality", name="_public_location_autocomplete_municipality")
     * @Template()
     */
    public function municipalityAction()
    {
        $data= $this->genericAction('EastitDarwinLocationBundle:Municipality');

        return $this->createJsonResponse($data);
    }

    /**
     * @Route("/region", name="_public_location_autocomplete_region")
     * @Template()
     */
    public function regionAction()
    {
        $data= $this->genericAction('EastitDarwinLocationBundle:Region');

        return $this->createJsonResponse($data);
    }

}
