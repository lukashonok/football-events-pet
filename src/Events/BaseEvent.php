<?php

namespace App\Events;

/**
 * Abstract base class for all event types.
 * 
 * Provides automatic property initialization from a configuration array,
 * and defines a standard structure for collecting and saving event statistics.
 */
abstract class BaseEvent
{
    /**
     * Constructor.
     *
     * Initializes class properties from the given configuration array.
     * Only properties declared in the class will be assigned.
     * Automatically sets a timestamp for the event.
     *
     * @param array $config  Key-value pairs matching class property names.
     */
    function __construct(array $config)
    {
        foreach ($config as $field_raw => $value) {
            $field = match ($field_raw) {
                'type' => 'statType',
                'team_id' => 'teamId',
                'match_id' => 'matchId',
                default => $field_raw
            };

            // Assign the value only if the property exists in this class.
            if (property_exists($this, $field)) {
                $this->$field = $value;
            }
        }

        // Record the current timestamp when the event is created.
        $this->timestamp = time();

        // Store whole package in data section
        $this->data = $config;
    }

    /**
     * Returns the base structure of the event for saving.
     *
     * This can be stored in a database or used for further processing.
     *
     * @return array
     */
    public function getStatsForSave(): array
    {
        return [
            'type' => $this->statType,
            'timestamp' => $this->timestamp,
            'data' => $this->data
        ];
    }

    /** @var string Unique identifier of the match. */
    public readonly string $matchId;

    /** @var string Identifier of the team associated with this event. */
    public readonly string $teamId;

    /** @var string Type of statistic or event (e.g., "goal", "foul"). */
    public readonly string $statType;

    /** @var array Additional event data (custom per subclass). */
    protected array $data;

    /** @var int Unix timestamp of when the event was created. */
    protected int $timestamp;

    /**
     * Must return the structure used to update statistics
     * (e.g., increments, counters, etc.).
     *
     * @return array
     */
    abstract public function getStatsForUpdate(): array;

    /**
     * Must validate that the event data is correct and consistent.
     *
     * @return void
     * @throws InvalidArgumentException if validation fails.
     */
    abstract public function validate();
}
