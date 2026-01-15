<?php

namespace App\GraphQL\Queries;

final class Health
{
    /**
     * Return the health status.
     */
    public function __invoke(): string
    {
        return 'ok';
    }
}
