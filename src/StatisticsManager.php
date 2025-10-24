<?php

namespace App;

use App\Events\BaseEvent;

class StatisticsManager
{
    private FileStorage $storage;
    
    public function __construct(string $statsFile = '../storage/statistics.txt')
    {
        $this->storage = new FileStorage($statsFile);
        
        $directory = dirname($statsFile);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
    }
    
    public function updateTeamStatistics(BaseEvent $event): void
    {
        $stats = $this->getStatistics();
        
        $stats[$event->matchId] ??= [];
        $stats[$event->matchId][$event->teamId] ??= [];

        foreach ($event->getStatsForUpdate() as $stat => $valueToIncrement) {
            $stats[$event->matchId][$event->teamId][$stat] ??= 0;
            $stats[$event->matchId][$event->teamId][$stat] += $valueToIncrement;
        }
        
        $this->saveStatistics($stats);
    }
    
    public function getTeamStatistics(string $matchId, string $teamId): array
    {
        $stats = $this->getStatistics();
        return $stats[$matchId][$teamId] ?? [];
    }
    
    public function getMatchStatistics(string $matchId): array
    {
        $stats = $this->getStatistics();
        return $stats[$matchId] ?? [];
    }
    
    private function getStatistics(): array
    {
        $content = $this->storage->getAllRaw();
        return json_decode($content, true) ?? [];
    }
    
    private function saveStatistics(array $stats): void
    {
        $this->storage->saveAllRaw(json_encode($stats, JSON_PRETTY_PRINT));
    }
}
