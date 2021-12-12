<?php

declare(strict_types=1);

namespace Kromacie\IntrospectionMiddleware\Repositories;

use Illuminate\Cache\Repository;

class CacheAccessTokenRepository implements AccessTokenRepositoryInterface
{
    protected Repository $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function find(string $identifier): ?string
    {
        return $this->repository->get($identifier);
    }

    public function save(string $identifier, string $accessToken): void
    {
        $this->repository->put($identifier, $accessToken, new \DateInterval('P1H'));
    }
}
