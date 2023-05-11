<?php

namespace Tackacoder\Tournament\Contracts;

use Tackacoder\Tournament\Traits\Collection;

abstract class AbstractCollection implements \ArrayAccess
{
    use Collection;
}
