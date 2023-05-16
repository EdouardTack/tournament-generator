# Tournament generator

## Features

- Create a simple round robin tournament with an even count teams and home/away matches

## Installation

`$ composer require tackacoder/tournament-services`

## Basic Usage

```php
require './vendor/autoload.php';

use Carbon\CarbonImmutable;
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
// Change the start date
$tournament->setDate(date: CarbonImmutable::now('UTC'));

$tournament->addService(new ChampionshipService());
$result = $tournament->generate();
```

### Championship Service Usage

The Championship Service object can be construct with optionals parameters.
    * interval : See [documentation](https://carbon.nesbot.com/docs/#api-interval)
    * callable : Closure to send event on diffrent endpoint

```php
new ChampionshipService('2 days', function ($args) {
    $endpoint = $args['name'];
    event(new Event($args));
});
```

On generate method, yon can add some configuration parameters to send to Services. For `ChampionshipService`, you can add mirror and shift configuration.

```php

[...]

$tournament->generate([
    'mirror' => false, // false => each Teams meet once, true => home & away matches
    'shift' => 3 // Shift as many matches to avoid meeting teams on the same model
]);
```

The default configuration parameters to `generate()` are :
    * The name of the tournament
    * The date of creation
    * Teams collection

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

        $date = $this->getConfig('date');
        $teams = $this->getConfig('teams');
        $name = $this->getConfig('name');

        return [];
    }
}

// In other file
$tournament = new Tournament();
$tournament->setMode('my_service');
```

## Contributing 

### Run Functional Tests

`$ composer test` or `$ php vendor/bin/pest`
