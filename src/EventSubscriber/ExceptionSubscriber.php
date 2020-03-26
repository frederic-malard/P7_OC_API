<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getException();

        $responseArray = [
            "code" => $exception->getStatusCode(),
            "message" => "ressource introuvable"
        ];

        $responseJson = new JsonResponse(
            $responseArray,
            $exception->getStatusCode(),
            [],
            false
        );

        $event->setResponse($responseJson);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
