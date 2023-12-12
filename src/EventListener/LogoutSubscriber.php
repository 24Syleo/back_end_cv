<?php 
// src/EventListener/LogoutSubscriber.php
namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\EventListener\CsrfTokenClearingLogoutListener;

class LogoutSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [CsrfTokenClearingLogoutListener::class => 'onLogout'];
    }

    public function onLogout(CsrfTokenClearingLogoutListener $event): void
    {
        dump($event);
    }
}

?>