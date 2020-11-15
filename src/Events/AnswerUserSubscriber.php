<?php

namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Answer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class AnswerUserSubscriber implements EventSubscriberInterface
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setUserForAnswer', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function setUserForAnswer(ViewEvent $event)
    {
        $answer = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($answer instanceof Answer && $method==="POST") {
            $user = $this->security->getUser();
            $answer->setUser($user);
        }
    }
}
