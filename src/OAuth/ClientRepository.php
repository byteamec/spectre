<?php

namespace Byteam\Spectre\OAuth;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    /** @var \Byteam\Spectre\OAuthClient  */
    protected $oauthClients;

    function __construct()
    {
        $this->oauthClients = app('Byteam\Spectre\OAuthClient');
    }

    /**
     * 
     * @return ClientEntityInterface
     */
    public function getClientEntity($clientIdentifier)
    {
        $client = $this->oauthClients->cacheFor(config('spectre.cache.clientTimeout', 24 * 3600))
            ->where('id', $clientIdentifier)->first();
        $client->setIdentifier($clientIdentifier);
        return $client;
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        $client = $this->oauthClients->cacheFor(config('spectre.cache.clientTimeout', 24 * 3600))
            ->where('id', $clientIdentifier)->first();
        if (!is_null($client) && $client->secret == $clientSecret && !$client->revoked) {
            switch ($grantType) {
                case "password":
                case "refresh_token":
                    return $client->type == "P";
            }
        }
        return false;
    }
}
