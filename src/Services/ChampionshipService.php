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

    public function __construct(protected string $interval = '2 days')
    {
        $this->contests = new ContestsCollection();
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
        $rounds = $this->rounds();

        $i = 1;
        foreach ($rounds as $round) {
            $date = $this->matches($round, $date, $i);
            $i++;
        }

        return $this->contests->toArray();
    }

    public function matches(array $contests, $date, $idDay)
    {
        $intervalDate = $date->add(CarbonInterval::make($this->interval));

        $day = new Day("Journey n°$idDay");
        $intervalDate = $intervalDate->add(CarbonInterval::make('4 Hours'));
        $day->addSchedule(new Schedule(
            $intervalDate,
            $contests
        ));

        $this->contests->add($day);

        return $intervalDate;
    }

    /**
     * Create round robin for a single matches between Teams
     * 
     * @return array
     */
    public function rounds(): array
    {
        $teamsId = $this->getConfig('teams')->map(fn (Team $team) => $team->getUuid());
        $countTeams = $this->getConfig('teams')->count();

        // Si le nombre d'equipe n'est pas un nombre pair
        // $ghost = false;
        // if ($countTeams % 2 == 1) {
        //     $countTeams++;
        //     $ghost = true;
        // }
        
        // Défini le nombre de match par équipe
        $totalRounds = $countTeams - 1;
        // Défini le nombre de match par journée
        $matchesPerRound = $countTeams / 2;

        // Défini le nombre de tour de matchs
        $rounds = [];
        for ($i = 0;$i < $totalRounds;$i++) {
            $rounds[$i] = [];
        }

        for ($round = 0;$round < $totalRounds;$round++) {
            for ($match = 0;$match < $matchesPerRound;$match++) {
                $home = ($round + $match) % $totalRounds;
                $away = ($totalRounds - $match + $round) % $totalRounds;
                // Dernière équipe reste à la même place, les autres tournent autour de lui
                if ($match == 0) {
                    $away = $countTeams - 1;
                }
                $rounds[$round][$match] = new Contest(
                    $this->getConfig('teams')->find(['uuid' => $this->teamName($home + 1, $teamsId)]),
                    $this->getConfig('teams')->find(['uuid' => $this->teamName($away + 1, $teamsId)])
                );
            }
        }

        // Interleave afin que les matchs à domicile et à l'extérieur soient assez uniformément dispersés
        $interleaved = [];
        for ($i = 0;$i < $totalRounds;$i++) {
            $interleaved[$i] = [];
        }

        $even = 0;
        $odd = ($countTeams / 2);
        for ($i = 0;$i < count($rounds);$i++) {
            if ($i % 2 == 0) {
                $interleaved[$i] = $rounds[$even++];
            } else {
                $interleaved[$i] = $rounds[$odd++];
            }
        }
        $rounds = $interleaved;

        // Dernière équipe ne peut pas être dernière durant chaque journée, donc on les retournes
        for ($round = 0;$round < count($rounds);$round++) {
            if ($round % 2 == 1) {
                $rounds[$round][0] = $this->flip($rounds[$round][0]);
            }
        }

        // On mélange un peu chaque rounds dans le sens aller-retour
        foreach ($rounds as $match => $matches) {
            foreach ($matches as $key => $round) {
                if (rand(0, 2) == 1) {
                    $rounds[$match][$key] = $this->flip($round);
                }
            }
        }

        return $rounds;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * On retourne les 2 équipes
     *
     * @param $match
     *
     * @return string
     */
    protected function flip(Contest $round): Contest
    {
        return new Contest(
            $round->getAwayTeam(),
            $round->getHomeTeam()
        );
    }

    /**
     * On récupère le nom de l'équipe
     *
     * @param int
     * @param array
     *
     * @return string
     */
    protected function teamName(int $num, array $ids): string
    {
        $i = $num - 1;
        if (count($ids) > $i && strlen(trim($ids[$i])) > 0) {
            return trim($ids[$i]);
        } else {
            return $num;
        }
    }

}