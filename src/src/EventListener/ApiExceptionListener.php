<?php
namespace App\EventListener;

use Psr\Log\LoggerInterface;
use App\Utils\JsonResponseFactory;
use App\Exception\UnauthorizedException;
use App\Exception\EntityNotFoundException;
use App\Utils\EnvironmentCheckerInterface;
use App\Exception\EmailAlreadyInUseException;
use App\Exception\InvalidCredentialsException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ApiExceptionListener
{
    public function __construct(
        private LoggerInterface $logger,
        private EnvironmentCheckerInterface $environmentChecker)
    {}

    public function onKernelException(ExceptionEvent $event): void
    {

    
        $exception = $event->getThrowable();

        $this->logger->error(sprintf(
            'Excepción capturada: %s en %s:%d - Trace: %s',
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        ));


        if ($exception instanceof EmailAlreadyInUseException) {

            $response = JsonResponseFactory::error(
                            $exception->getMessage(),
                            $exception->getStatusCode()
                        );
            $event->setResponse($response);
            return;
        }        
        
        if ($exception instanceof EntityNotFoundException) {

            $response = JsonResponseFactory::error(
                            $exception->getMessage(),
                            $exception->getStatusCode()
                        );
            $event->setResponse($response);
            return;
        } 

        if ($exception instanceof UnauthorizedException) {

            $response = JsonResponseFactory::error(
                            $exception->getMessage(),
                            $exception->getStatusCode()
                        );
            $event->setResponse($response);
            return;
        }

        if ($exception instanceof InvalidCredentialsException) {

            $response = JsonResponseFactory::error(
                            $exception->getMessage(),
                            $exception->getStatusCode()
                        );

            $event->setResponse($response);
            return;
        }

        if ($exception instanceof HttpExceptionInterface) {

            $statusCode = $exception->getStatusCode();
            $message = $exception->getMessage() ?: Response::$statusTexts[$statusCode];
            
        } else {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

            if ($this->environmentChecker->shouldShowDetails()) {
                $message = sprintf(
                    'Exception: %s in %s:%d',
                    $exception->getMessage(),
                    $exception->getFile(),
                    $exception->getLine()
                );
            } else {
                $message = 'Error interno del servidor';
            }
        }

        $response = JsonResponseFactory::error($message, $statusCode);
        $event->setResponse($response);
    }
    
}