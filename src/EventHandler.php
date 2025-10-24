<?php

namespace App;

use App\Events\FoulEvent;
use App\Events\GoalEvent;
use App\Notifications\EventNotifierInterface;
use App\Notifications\MockEventNotifier;

use function PHPSTORM_META\map;

class EventHandler
{
    private FileStorage $storage;
    private StatisticsManager $statisticsManager;
    private EventNotifierInterface $eventNotifier;
    
    public function __construct(string $storagePath, ?StatisticsManager $statisticsManager = null, ?MockEventNotifier $eventNotifier = null)
    {
        $this->storage = new FileStorage($storagePath);
        $this->statisticsManager = $statisticsManager ?? new StatisticsManager(__DIR__ . '/../storage/statistics.txt');
        $this->eventNotifier = $eventNotifier ?? new MockEventNotifier();
    }
    
    public function handleEvent(array $data): array
    {
        if (!isset($data['type'])) {
            throw new \InvalidArgumentException('Event type is required');
        }

        $type = $data['type'];
        $event = match ($type) {
            'foul' => new FoulEvent($data),
            'goal' => new GoalEvent($data),
            default => throw new \InvalidArgumentException("Unexpected event type {$type}")
        };


        $event->validate();

        $this->storage->appendEvent($event);

        $this->statisticsManager->updateTeamStatistics($event);

        $this->eventNotifier->notifyClients($event);

        return [
            'status' => 'success',
            'message' => 'Event saved successfully',
            'event' => $event->getStatsForSave()
        ];
    }
}