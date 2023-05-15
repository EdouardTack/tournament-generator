<?php

namespace Tackacoder\Tournament\Services;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Tackacoder\Tournament\Collections\ContestsCollection;
use Tackacoder\Tournament\Components\Contest;
use Tackacoder\Tournament\Components\Day;
use Tackacoder\Tournament\Components\Schedule;
use Tackacoder\Tournament\Components\Team;
use Tackacoder\Tournament\Services\Service;
use Tackacoder\Tournament\Supports\ServiceInterface;

class ChampionshipService extends Service implements ServiceInterface
{
    /**
     * @var string
     */
    protected string $name = 'championship';

    /**
     * @var CarbonImmutable
     */
    protected CarbonImmutable $date;

    /**
     * @var ContestsCollection
     */
    protected ContestsCollection $contests;

    public function __construct(protected string $interval = '2 days', $callable = null)
    {
        $this->contests = new ContestsCollection();
        $this->callable = $callable;
    }

    /**
     * Generate Array with all informations about a complete championship
     * 
     * A championship tournament contains information about it
     * 
     * List of all registered teams
     * 
     * List of all matches day, like 1st journey, 2nd journey, etc...
     * - A matches day is split in Schedule date
     * - A schedule Date contains all matches between teams
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
     * @return array
     */
    public function generate(array $config): array
    {
        $this->setConfig($config);

        $this->date = $this->getConfig('date');
        $date = $this->date->add(CarbonInterval::make('1 Day'));
        $this->event(['name' => 'generate:afterIntervalDate', 'date' => $this->date, 'intervalDate' => $date]);
        $rounds = $this->rounds();

        $this->event(['name' => 'generate:beforeSetContests']);
        $i = 1;
        foreach ($rounds as $round) {
            $date = $this->matches($round, $date, $i);
            $i++;
        }
        $this->event(['name' => 'generate:afterSetContests']);

        return $this->contests->toArray();
    }

    public function matches(array $contests, $intervalDate, $idDay)
    {
        $day = new Day("Journey n°$idDay");
        $intervalDate = $intervalDate->add(CarbonInterval::make($this->interval));
        $day->addSchedule(new Schedule(
            $intervalDate,
            $contests
        ));
        $this->contests->add($day);
        $this->event(['name' => 'contests:createSchedule', 'day' => $day]);

        return $intervalDate;
    }

    /**
     * Create round robin for a single matches between Teams
     */
    public function rounds(): array
    {
        $teamsId = $this->getConfig('teams')->map(fn (Team $team) => $team->getUuid());
        $countTeams = $this->getConfig('teams')->count();

        // If the team number is not an even number
        $ghost = false;
        // if ($countTeams % 2 == 1) {
        //     $countTeams++;
        //     $ghost = true;
        // }
        
        $totalRounds = $countTeams - 1;
        $matchesPerRound = $countTeams / 2;

        $this->event(['name' => 'rounds:teams', 'totalTeams' => $countTeams, 'totalRounds' => $totalRounds, 'matchesPerRound' => $matchesPerRound, 'ghostTeam' => $ghost]);

        // Set the number of match rounds
        $rounds = [];
        for ($i = 0;$i < $totalRounds;$i++) {
            $rounds[$i] = [];
        }

        for ($round = 0;$round < $totalRounds;$round++) {
            for ($match = 0;$match < $matchesPerRound;$match++) {
                $home = ($round + $match) % $totalRounds;
                $away = ($totalRounds - $match + $round) % $totalRounds;
                // Last team remains in the same place, the others revolve around it
                if ($match == 0) {
                    $away = $countTeams - 1;
                }
                $rounds[$round][$match] = new Contest(
                    $this->getConfig('teams')->find(['uuid' => $this->teamName($home + 1, $teamsId)]),
                    $this->getConfig('teams')->find(['uuid' => $this->teamName($away + 1, $teamsId)])
                );
            }
        }

        // Interleave so home and away games are pretty evenly spread out
        $interleaved = [];
        for ($i = 0;$i < $totalRounds;$i++) {
            $interleaved[$i] = [];
        }

        $even = 0;
        $odd = ($countTeams / 2);
        $roundsCount = count($rounds);
        for ($i = 0;$i < $roundsCount;$i++) {
            $interleaved[$i] = $i % 2 == 0 ? $rounds[$even++] : $rounds[$odd++];
        }
        $rounds = $interleaved;
        $roundsCount = count($rounds);
        for ($round = 0;$round < $roundsCount;$round++) {
            if ($round % 2 == 1) {
                $rounds[$round][0] = $this->flip($rounds[$round][0]);
            }
        }

        foreach ($rounds as $match => $matches) {
            foreach ($matches as $key => $round) {
                if (random_int(0, 2) == 1) {
                    $rounds[$match][$key] = $this->flip($round);
                }
            }
        }

        $this->event(['name' => 'rounds:roundsCreated', 'rounds' => $rounds]);

        return $rounds;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Flip the 2 Team object
     */
    protected function flip(Contest $round): Contest
    {
        return new Contest(
            $round->getAwayTeam(),
            $round->getHomeTeam()
        );
    }

    /**
     * Try to get the uuid of Team
     */
    protected function teamName(int $num, array $ids): string|int
    {
        $i = $num - 1;
        if (count($ids) > $i && strlen(trim((string) $ids[$i])) > 0) {
            return trim((string) $ids[$i]);
        } else {
            return $num;
        }
    }
}