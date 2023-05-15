# Tournament generator

## Features

- Create a simple round robin tournament with an even count teams

## Installation

`$ composer require tackacoder/tournament-services`

## Basic Usage

```php
require './vendor/autoload.php';

use Tackacoder\Tournament\Tournament;

/**
 * Create a tournament
 * 
 * ` Tournament
 *   - Tournament name
 *   - Tournament Mode
 *   - Tournament generate date
 * 
 * ` Teams
 *   - List of teams
 * 
 * ` Matches
 *   - Day name
 * 
 *   ` Schedules
 *     - Schedule Date
 * 
 *     ` Matches
 *       - Home Team
 *       - Away Team
 *       - Score
 *       - Stats
 * 
 * TOURNAMENT_MODE is a service variable
 * By default, services are included :
 * - championship
 * - cup
 */
$TOURNAMENT_MODE = 'championship';
$tournament = new Tournament('My tournament title', $TOURNAMENT_MODE);

// OR
$tournament = new Tournament();
$tournament->setName('My tournament title')->setMode($TOURNAMENT_MODE);

// Complete with teams
$tournament->setTeams([
    [
        "name" => "Team One",
        "status" => true
    ],
    [
        "name" => "Team Two",
        "status" => false
    ],
    [
        "name" => "Team Three",
        "status" => true
    ],
    [
        "name" => "Team Four",
        "status" => true
    ]
]);
$tournament->addService(new ChampionshipService());
$result = $tournament->generate();
```

### Create a generator service

```php
use Tackacoder\Tournament\Services\Service;
use Tackacoder\Tournament\Supports\ServiceInterface;

class MyServiceService extends Service implements ServiceInterface
{
    /**
     * NEEDED to find the generator
     */
    protected string $name = 'my_service';

    public function generate(array $config): array
    {
        $this->setConfig($config);

        $this->getConfig('date');
        $this->getConfig('teams');
        $this->getConfig('name');
        $this->getConfig('mode');

        return [];
    }
}

// In other file
$tournament->setMode('my_service');
```

## Contributing 

### Run Functional Tests

`composer test` or `php vendor/bin/pest`
