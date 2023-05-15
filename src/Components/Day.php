<?php

namespace Tackacoder\Tournament\Components;

use Tackacoder\Tournament\Components\Schedule;

class Day
{
    protected array $schedules;

    public function __construct(protected string $name)
    {
    }

    public function addSchedule(Schedule $schedule)
    {
        $this->schedules[] = $schedule;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSchedules()
    {
        return $this->schedules;
    }
}