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
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\ResourceServer;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;

class SpectreServiceProvider extends ServiceProvider
{
    private $accessTokenInterval;

    private $refreshTokenInterval;

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

        $this->app['auth']->viaRequest('api', function ($request) {
            return $this->getUserViaRequest($request);
        });
    }

    /**
     *
     */
    public function register()
    {
        $this->app->configure('spectre');
        $this->accessTokenInterval = config('spectre.interval.access_token', 'PT1H');
        $this->refreshTokenInterval = config('spectre.interval.refresh_token', 'P1M');

        $this->app->singleton(AuthorizationServer::class, function () {
            return tap($this->makeAuthorizationServer(), function ($server) {
                $server->enableGrantType($this->makePasswordGrant(), new \DateInterval($this->accessTokenInterval));
                $server->enableGrantType($this->makeRefreshGrant(), new \DateInterval($this->accessTokenInterval));
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

        $passwordGrant->setRefreshTokenTTL(new \DateInterval($this->refreshTokenInterval));

        return $passwordGrant;
    }

    /**
     * @return RefreshTokenGrant
     * @throws \Exception
     */
    private function makeRefreshGrant()
    {
        $refreshGrant = new RefreshTokenGrant(
            new RefreshTokenRepository()
        );

        $refreshGrant->setRefreshTokenTTL(new \DateInterval($this->refreshTokenInterval));

        return $refreshGrant;
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

    /**
     * @param $request
     * @return object|null
     */
    private function getUserViaRequest($request)
    {
        $psr = (new DiactorosFactory)->createRequest($request);
        try {
            $psr = $this->app->make(ResourceServer::class)
                ->validateAuthenticatedRequest($psr);

            $client = app('Byteam\Spectre\OAuthClient')->find($psr->getAttribute('oauth_client_id'));
            if ($client->user_type == null)
                $users = app('Byteam\Spectre\User');
            else
                $users = app($client->user_type);
            return $users->find($psr->getAttribute('oauth_user_id'));
        } catch (OAuthServerException $e) {
            return null;
        }
    }
}