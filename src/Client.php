<?php

declare(strict_types=1);

namespace Kromacie\IntrospectionMiddleware;

class Client
{
    protected bool $active;

    protected Scopes $scopes;

    public function __construct(array $options)
    {
        $this->active = $options['active'] ?? false;

        if (isset($options['scopes']) && !is_array($options['scopes'])) {
            $options['scopes'] = explode(' ', $options['scopes']);
        }

        $this->scopes = new Scopes($options['scopes'] ?? []);
    }

    protected function getScopes(): Scopes
    {
        return $this->scopes;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
