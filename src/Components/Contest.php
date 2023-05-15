<?php

namespace Tackacoder\Tournament\Components;

class Contest
{
    protected int $homeScore = 0;

    protected int $awayScore = 0;

    public function __construct(protected Team $home, protected Team $away)
    {
    }

    public function getHomeTeam()
    {
        return $this->home;
    }

    public function getAwayTeam()
    {
        return $this->away;
    }

    public function getHomeScore()
    {
        return $this->homeScore;
    }

    public function getAwayScore()
    {
        return $this->awayScore;
    }
}
