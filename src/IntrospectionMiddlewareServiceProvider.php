<?php

namespace Kromacie\IntrospectionMiddleware;

use Carbon\Laravel\ServiceProvider;
use Kromacie\IntrospectionMiddleware\Repositories\AccessTokenRepositoryInterface;
use Kromacie\IntrospectionMiddleware\Repositories\CacheAccessTokenRepository;

class IntrospectionMiddlewareServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom("/../config/introspection_middleware.php", "introspection_middleware");

        $this->registerAccessTokenRepository();
    }

    protected function registerAccessTokenRepository()
    {
        $this->app->bind(AccessTokenRepositoryInterface::class, function () {
            return $this->app->make(config('introspection_middleware.access_token_repository'));
        });

        $this->app->bind(CacheAccessTokenRepository::class, function () {
            return new CacheAccessTokenRepository($this->app['cache.store']);
        });
    }
}
