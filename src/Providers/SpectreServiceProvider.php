<?php

namespace Byteam\Spectre\Providers;

use Byteam\Spectre\OAuth\AccessTokenRepository;
use Byteam\Spectre\OAuth\ClientRepository;
use Byteam\Spectre\OAuth\RefreshTokenRepository;
use Byteam\Spectre\OAuth\ScopeRepository;
use Byteam\Spectre\OAuth\UserRepository;
use Illuminate\Support\ServiceProvider;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\ResourceServer;

class SpectreServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadRoutes();

        $this->app->routeMiddleware([
            'oauth' => \Byteam\Spectre\Http\Middleware\OAuthMiddleware::class
        ]);
    }

    /**
     *
     */
    public function register()
    {
        $this->app->singleton(AuthorizationServer::class, function () {
            return tap($this->makeAuthorizationServer(), function ($server) {
                $server->enableGrantType($this->makePasswordGrant(), new \DateInterval('PT1H'));
            });
        });

        $this->app->singleton(ResourceServer::class, function () {
            return $this->makeResourceServer();
        });
    }

    /**
     *
     */
    private function loadRoutes()
    {
        $this->app->router->group([
            'namespace' => 'Byteam\Spectre\Http\Controllers',
        ], function ($router) {
            require __DIR__.'/../../routes/web.php';
        });
    }

    /**
     * @return AuthorizationServer
     */
    private function makeAuthorizationServer()
    {
        return new AuthorizationServer(
            new ClientRepository,
            new AccessTokenRepository,
            new ScopeRepository,
            new CryptKey(storage_path('oauth-private.key'), null, false),
            app('encrypter')->getKey()
        );
    }

    /**
     * @return PasswordGrant
     * @throws \Exception
     */
    private function makePasswordGrant()
    {
        $passwordGrant =  new PasswordGrant(
            new UserRepository,
            new RefreshTokenRepository()
        );

        $passwordGrant->setRefreshTokenTTL(new \DateInterval('P1M'));

        return $passwordGrant;
    }

    /**
     * @return ResourceServer
     */
    private function makeResourceServer()
    {
        return new ResourceServer(
            new AccessTokenRepository(),
            new CryptKey(storage_path('oauth-public.key'), null, false)
        );
    }
}