<?php

namespace HappyR\LocationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("")
 *
 * @author tobias
 *
 */
class PublicController extends Controller
{
    /**
     * @Route("/city/{slug}", name="_public_city_show")
     * @Template()
     */
    public function showAction($slug)
    {
        //get the city
        $city=$this->get('darwin.location_manager')->getCityManager()->findOneBySlug($slug);

        if (!$city) {
            throw $this->createNotFoundException('City not found');
        }

        //get opuses
        $em=$this->getDoctrine()->getManager();
        $opuses=$em->getRepository('EastitLegoOpusBundle:Opus')->findByCity($city);

        $pagination=$this->get('knp_paginator')->paginate(
                $opuses,
                $this->get('request')->query->get('page', 1)/*page number*/,
                10/*limit per page*/
        );

        return array(
                'pagination' => $pagination,
                'city'=>$city,

                );
    }

    /**
     * @Route("/city/{slug}/feed", name="_public_city_feed", defaults={"_format" = "xml"})
     * @Template()
     */
    public function feedAction($slug)
    {
        //get the city
        $city=$this->get('darwin.location_manager')->getCityManager()->findOneBySlug($slug);

        if (!$city) {
            throw $this->createNotFoundException('City not found');
        }

        //get opuses
        $em=$this->getDoctrine()->getManager();
        $opuses=$em->getRepository('EastitLegoOpusBundle:Opus')->findByCity($city,20);

        return array(
                'opuses' => $opuses,
                'city'=>$city,
                'buildDate'=>time(),
                );
    }
}
