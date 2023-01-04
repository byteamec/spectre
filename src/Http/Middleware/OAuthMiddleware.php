<?php

namespace Byteam\Spectre\Http\Middleware;

use Byteam\Spectre\Exception\OAuthException;
use Closure;
use Illuminate\Http\Request;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Psr\Http\Message\ServerRequestInterface;

class OAuthMiddleware
{
    /** @var ResourceServer $resourceServer */
    protected $resourceServer;

    /**
     * OAuthMiddleware constructor.
     * @param ResourceServer $resourceServer
     */
    public function __construct(ResourceServer $resourceServer)
    {
        $this->resourceServer = $resourceServer;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @throws OAuthException
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $psr = app(ServerRequestInterface::class);
        try {
            $psr = $this->resourceServer->validateAuthenticatedRequest($psr);
        } catch (OAuthServerException $e) {
            throw new OAuthException($e);
        }

        return $next($request);
    }
}
