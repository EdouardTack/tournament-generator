<?php

namespace Tackacoder\Tournament\Components;

use Carbon\CarbonImmutable;
use Tackacoder\Tournament\Components\Contest;

class Schedule
{
    protected array $contests;

    public function __construct(protected CarbonImmutable $date, array $contests = [])
    {
        if (!empty($contests)) {
            foreach ($contests as $contest) {
                $this->addContest($contest);
            }
        }
    }

    public function addContest(Contest $contest)
    {
        $this->contests[] = $contest;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getContests()
    {
        return $this->contests;
    }
}

