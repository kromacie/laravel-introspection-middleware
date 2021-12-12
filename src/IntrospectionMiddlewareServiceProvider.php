<?php

namespace Kromacie\IntrospectionMiddleware;

use Kromacie\IntrospectionMiddleware\Repositories\AccessTokenRepositoryInterface;
use Kromacie\IntrospectionMiddleware\Repositories\CacheAccessTokenRepository;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class IntrospectionMiddlewareServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel_introspection_middleware')
            ->hasConfigFile();
    }

    public function packageRegistered()
    {
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
