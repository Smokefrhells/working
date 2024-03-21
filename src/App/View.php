<?php

namespace App;

class View
{
    public function __construct(public readonly string $body)
    {

    }

    public function render(): string
    {
        return $this->body;
    }
}