<?php
namespace App\EventListener;

use App\Utils\JsonResponseFactory;
use App\Exception\InvalidRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class InvalidRequestExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof InvalidRequestException) {
            $response = JsonResponseFactory::error(
                [
                    'message' => $exception->getMessage(),
                    'errors' => $exception->getErrors()
                ],
                Response::HTTP_BAD_REQUEST
            );

            $event->setResponse($response);
        }
    }
}