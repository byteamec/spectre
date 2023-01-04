<?php

namespace Byteam\Spectre\Http\Controllers;


use Byteam\Spectre\Exception\OAuthException;
use Illuminate\Http\Response;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Response as Psr7Response;

class OAuthController extends Controller
{
    /** @var AuthorizationServer $oauthServer */
    private $oauthServer;

    /**
     * OAuthController constructor.
     * @param AuthorizationServer $oauthServer
     */
    public function __construct(AuthorizationServer $oauthServer)
    {
        $this->oauthServer = $oauthServer;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @throws OAuthException
     *
     * @return \Illuminate\Http\Response
     */
    public function login(ServerRequestInterface $request)
    {
        try {
            $response = $this->oauthServer->respondToAccessTokenRequest($request, new Psr7Response());
            return new Response(
                $response->getBody(),
                $response->getStatusCode(),
                $response->getHeaders()
            );
        } catch (OAuthServerException $e) {
            throw new OAuthException($e);
        }
    }
}