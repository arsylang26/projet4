<?php

namespace AppBundle\EventListener;

use AppBundle\Exception\BookingNotFoundException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

class ExceptionListener
{
    /** @var Router */
    private $router;

    /**
     * @var Session
     */
    private $session;

    public function __construct(Router $router, Session $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if ($exception instanceof BookingNotFoundException) {
            $this->session->getFlashBag()->add('warning', 'Oups ! problème, vous allez être redirigé vers l\'accueil');
            $event->setResponse(new RedirectResponse($this->router->generate('homepage')));
        }
    }

}
