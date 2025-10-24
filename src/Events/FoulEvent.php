<?php

namespace App\Events;

/**
 * Represents a foul event in a match.
 * 
 * Extends the BaseEvent class and provides specific logic for handling fouls.
 */
class FoulEvent extends BaseEvent
{
    /**
     * Returns the statistics to update when this event occurs.
     *
     * For a foul event, it simply increments the 'fouls' count by 1.
     *
     * @return array Associative array of stats to update.
     */
    public function getStatsForUpdate(): array
    {
        return [
            'fouls' => 1
        ];
    }

    /**
     * Validates that the event contains all required data fields.
     *
     * This ensures that the event can be processed correctly.
     *
     * @throws \InvalidArgumentException if any required field is missing.
     */
    public function validate()
    {
        // Define the required fields for a foul event.
        $existing_fields = [
            'match_id',  // ID of the match where the foul occurred
            'team_id',   // ID of the team committing the foul
            'minute',    // Minute in the match when the foul occurred
            'second',    // Second in the match when the foul occurred
        ];

        // Loop through each required field and check if it exists in $this->data
        foreach ($existing_fields as $field) {
            // If the field is missing, throw an exception
            if (!isset($this->data[$field])) {
                throw new \InvalidArgumentException(
                    "{$field} are required for {$this->statType} events"
                );
            }
        }
    }
}