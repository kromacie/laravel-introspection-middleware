<?php

declare(strict_types=1);

namespace Kromacie\IntrospectionMiddleware\Repositories;

interface AccessTokenRepositoryInterface
{
    /**
     * Find an existing access token based on identifier.
     *
     * @param string $identifier
     * @return string|null
     */
    public function find(string $identifier): ?string;

    /**
     * Save new access token with already known identifier.
     *
     * @param string $identifier
     * @param string $accessToken
     * @return void
     */
    public function save(string $identifier, string $accessToken): void;
}
