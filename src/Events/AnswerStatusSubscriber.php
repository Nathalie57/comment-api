<?php

namespace App\Events;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Answer;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AnswerStatusSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setStatusForAnswer', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function setStatusForAnswer(ViewEvent $event)
    {
        $answer = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($answer instanceof Answer && $method==="POST") {
            $answer->setStatus("DISPLAYED");
        }
    }
}
