# Football Events Application

Simple application for handling football events - recruitment task.

## Acceptance Criteria

The following business requirements must be met by the solution:

### Core business requirements
- [x] System accurately logs and updates statistics upon receiving a **goal** event, including details such as scorer, assisting player, team, minute, and match ID.
- [x] System accurately logs and updates records upon receiving a **foul** event, including details such as player at fault, affected player, team, match ID, and precise time of the foul.

- [x] All event data is permanently stored and retrievable (Fixed problem with rewriting storage by tests) 
- [x] Relevant statistics are calculated and maintained for both event types (In tests)
- [x] Clients receive information about all events in real-time (Describe in next section)
- [x] Data integrity is maintained at all times (In tests)
- [x] Historical data is preserved and accessible
- [ ] System can handle high volume of events

### Client communication requirements
- [x] All clients receive event notifications (preferably to not send to every client, but send to orchestrating system like rabbitmq/kafka)
- [x] Information is delivered in a timely manner (see MockEventNotifier)
- [x] Communication is reliable and consistent (see retries on sending)

### Recruitment requirements
- [x] The solution should be provided as a GitHub repository with at least three meaningful commits (repo is public)
- [x] Some kind of abstraction is allowed to demonstrate the solution over a fully functioning application (EventNotifierInterface for notifications, BaseEvent for events)
- [x] Try to devote no more than 3 hours to solving the problem - anything you don't have time to do can be written as a plan for further action (you can check by commits)
- [x] Try not to use AI tools. If you do - write down how you use it. (only for generating comments)


## Requirements

- Docker
- Docker Compose

## Installation and Setup

1. Build and run the container:
```bash
docker compose up --build -d
```

2. Build and run the container:
```bash
docker exec -it football_events_app composer install
```

3. The application will be available at: `http://localhost:8000`

## Usage

### Foul Event

Send a POST request with a foul event:

```bash
curl -X POST http://localhost:8000/event \
  -H "Content-Type: application/json" \
  -d '{"type": "foul", "player": "William Saliba", "team_id": "arsenal", "match_id": "m1", "minute": 45, "second": 34}'
```

### Example Response

Both events return a similar response structure:

```json
{
  "status": "success",
  "message": "Event saved successfully",
  "event": {
    "type": "foul",
    "timestamp": 1729599123,
    "data": {
      "type": "foul",
      "player": "William Saliba",
      "team_id": "arsenal",
      "match_id": "m1",
      "minute": 45,
      "second": 34
    }
  }
}
```

### Statistics Endpoint

Get team statistics for a specific match:

```bash
curl "http://localhost:8000/statistics?match_id=m1&team_id=arsenal"
```

Get all team statistics for a match:

```bash
curl "http://localhost:8000/statistics?match_id=m1"
```

Example response:
```json
{
  "match_id": "m1",
  "team_id": "arsenal",
  "statistics": {
    "fouls": 2
  }
}
```

Foul events automatically update team statistics (fouls counter) for the specified team in the given match.

## Tests

### PHPUnit Tests

Run PHPUnit tests inside the container:

```bash
docker exec -it football_events_app vendor/bin/phpunit tests
```

Or after entering the container:
```bash
docker exec -it football_events_app bash
vendor/bin/phpunit tests
```

### Codeception API Tests

Run Codeception API tests for comprehensive endpoint testing:

```bash
docker exec -it football_events_app vendor/bin/codecept run Api
```

Run all Codeception tests:

```bash
docker exec -it football_events_app vendor/bin/codecept run
```

### Test Coverage

The project includes:
- **Unit tests** (PHPUnit): Test individual classes and methods
- **API tests** (Codeception): Test HTTP endpoints and responses
- **Integration tests**: Test complete workflows including statistics tracking

## Project Structure

```
.
├── Dockerfile
├── docker-compose.yml
├── composer.json
├── phpunit.xml
├── public/
│   └── index.php          # Application entry point
├── src/
│   ├── EventHandler.php      # Event handling
│   ├── FileStorage.php       # File storage
│   └── StatisticsManager.php # Statistics management
├── tests/
│   ├── EventHandlerTest.php  # PHPUnit tests
│   ├── Api/                  # Codeception API tests
│   │   ├── EventApiCest.php
│   │   └── StatisticsApiCest.php
│   └── Support/              # Test helpers
└── storage/                  # Files with saved events and statistics
```

