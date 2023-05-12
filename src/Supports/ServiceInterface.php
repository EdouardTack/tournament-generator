<?php

namespace Tackacoder\Tournament\Supports;

interface ServiceInterface
{
    /**
     * Main generate into service
     * 
     * @return array
     */
    public function generate(): array;

    /**
     * Uses for finder collection
     * 
     * @return string
     */
    public function getName(): string;
}