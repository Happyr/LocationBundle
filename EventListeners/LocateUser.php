<?php


namespace HappyR\LocationBundle\EventListeners;

use Eastit\UserBundle\Entity\User;
use Eastit\UserBundle\Events\UserEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use HappyR\LocationBundle\Services\UserLocationService;


/**
 * 
 * Locate user when he registers or logs in
 */
class LocateUser 
{
    /**
     * @var \HappyR\LocationBundle\Services\UserLocationService userLocationService
     *
     *
     */
    protected $userLocationService;

    /**
     * @param UserLocationService $userLocationService
     */
    function __construct(UserLocationService$userLocationService)
    {
        $this->userLocationService = $userLocationService;
    }


    /**
     *
     *
     * @param User &$user
     * @param string $ip
     *
     */
    protected  function locateUser(User &$user, $ip)
    {
        $this->userLocationService->locateUser($user,$ip);
    }

    /**
     * Try to locate a user on login
     *
     * @param InteractiveLoginEvent $event
     *
     */
    public function loginListener(InteractiveLoginEvent $event)
    {
        $user=$event->getAuthenticationToken()->getUser();
        $ip=$event->getRequest()->getClientIp();
        $this->locateUser($user, $ip);
    }

    /**
     * Locate a user when he register
     *
     * @param UserEvent $event
     *
     */
    public function registrationListener(UserEvent $event)
    {
        $user=$event->getUser();
        $ip=$event->getIp();
        $this->locateUser($user, $ip);
    }
}