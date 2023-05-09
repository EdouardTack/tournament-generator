<?php

namespace Tackacoder\Tournament\Contracts;

use Tackacoder\Tournament\Traits\Collection;
use ArrayAccess;

abstract class AbstractCollection implements ArrayAccess
{
    use Collection;
}
