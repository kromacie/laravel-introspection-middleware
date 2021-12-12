<?php

declare(strict_types=1);

namespace Kromacie\IntrospectionMiddleware;

class AuthManager
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function connection(string $name = null): Client
    {
        $identifier = $name ?? $this->getDefault();

        return new Client(array_replace_recursive($this->config['servers.' . $identifier],
            ['server_identifier' => $identifier]));
    }

    public function getDefault(): string
    {
        return $this->config['default'];
    }
}
