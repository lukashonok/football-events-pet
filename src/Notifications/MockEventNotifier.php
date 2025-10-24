<?php

namespace App\Notifications;

use App\Events\BaseEvent;

class MockEventNotifier implements EventNotifierInterface
{
    public array $sentEvents = [];
    private int $maxRetries;
    private int $retryCount = 0;

    public function __construct(int $maxRetries = 3)
    {
        $this->maxRetries = $maxRetries;
    }

    // Notify clients with retry on failure
    public function notifyClients(BaseEvent $event): void
    {
        for ($attempt = 0; $attempt <= $this->maxRetries; $attempt++) {
            try {
                $this->send($event);
                $this->sentEvents[] = $event; // store for test verification
                return;
            } catch (\Exception $e) {
                $this->retryCount = $attempt;
                usleep(100_000); // simulate delay before retry
            }
        }
        // Here we can try to reconnect again
        throw new \RuntimeException("Failed to notify clients after {$this->maxRetries} retries");
    }

    // Simulate sending event, may randomly fail
    private function send(BaseEvent $event): void
    {
        if (rand(0, 4) === 0) throw new \RuntimeException("Mock connection error");
        // otherwise event is sent
    }
}
