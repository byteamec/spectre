<?php

namespace Byteam\Spectre\Http\Controllers;


use Illuminate\Http\Response;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response as Psr7Response;

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
     * @return \Illuminate\Http\Response
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     */
    public function login(ServerRequestInterface $request)
    {
        $response = $this->oauthServer->respondToAccessTokenRequest($request, new Psr7Response());
        return new Response(
            $response->getBody(),
            $response->getStatusCode(),
            $response->getHeaders()
        );
    }
}