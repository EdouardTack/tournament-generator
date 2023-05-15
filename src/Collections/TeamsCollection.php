<?php

namespace Tackacoder\Tournament\Collections;

use Tackacoder\Tournament\Components\Team;
use Tackacoder\Tournament\Contracts\AbstractCollection;

class TeamsCollection extends AbstractCollection
{
    public function define(array $lists)
    {
        $this->list = [];

        foreach ($lists as $list) {
            $this->list[] = $this->set($list);
        }
    }

    protected function set($value, $offset = null)
    {
        return new Team($value);
    }
}
