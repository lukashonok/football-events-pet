<?php

namespace App\Events;

/**
 * Represents a goal event in a match.
 * 
 * Extends the BaseEvent class and provides specific logic for handling goals.
 */
class GoalEvent extends BaseEvent
{
    /**
     * Returns the statistics to update when this event occurs.
     *
     * For a goal event, it simply increments the 'goals' count by 1.
     *
     * @return array Associative array of stats to update.
     */
    public function getStatsForUpdate(): array
    {
        return [
            'goals' => 1
        ];
    }

    /**
     * Validates that the event contains all required data fields.
     *
     * Ensures that a goal event can be processed correctly.
     *
     * @throws \InvalidArgumentException if any required field is missing.
     */
    public function validate()
    {
        // Define the required fields for a goal event.
        $existing_fields = [
            'match_id',  // ID of the match where the goal occurred
            'team_id',   // ID of the team that scored
            'player',    // Name or ID of the scoring player
            'minute'     // Minute in the match when the goal occurred
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