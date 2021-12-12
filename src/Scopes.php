<?php

declare(strict_types=1);

namespace Kromacie\IntrospectionMiddleware;

class Scopes
{
    protected array $scopes;

    public function __construct(array $scopes)
    {
        $this->scopes = $scopes;
    }

    public function hasAny(array $scopes): bool
    {
        return count(array_diff($scopes, $this->scopes)) !== count($scopes);
    }

    public function hasAll(array $scopes): bool
    {
        return count(array_diff($scopes, $this->scopes)) === 0;
    }

    public function doesntHaveAny(array $scopes): bool
    {
        return ! $this->hasAny($scopes);
    }

    public function doesntHaveAll(array $scopes): bool
    {
        return ! $this->hasAll($scopes);
    }
}
