<?php

namespace HappyR\LocationBundle\Services;

use Eastit\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

/**
 * The purpose with the UserLocationService is to identify where a user is located base on ones id.
 *
 */
 class UserLocationService
 {
     protected $em;
     protected $geoip;

     public function __construct($em, $geoip)
     {
         $this->em=$em;
         $this->geoip=$geoip;
     }

     /**
      * Finds where a user is located. If we already know, we don't do anything
      *
      *
      * @param User $user
      * @param string $ip
      *
      * @return bool
      */
     public function locateUser(User &$user, $ip)
     {
        $location=$user->getProfile()->getLocation();
        if($location->hasCoordinates()){
            return false;
        }

        $this->geoip->getLocation($ip,$location);
        $this->em->persist($user);
        $this->em->flush();

        return true;
     }
 }
