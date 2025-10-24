<?php

namespace App\Notifications;

use App\Events\BaseEvent;

/**
 * Interface for an event notification service.
 * 
 * Allows sending events to clients in a decoupled way.
 */
interface EventNotifierInterface
{
    /**
     * Notify all clients about a new event.
     *
     * @param BaseEvent $event The event object to broadcast.
     *
     * @return void
     */
    public function notifyClients(BaseEvent $event): void;
}
