<?php

namespace Byteam\Spectre\OAuth;


use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    /** @var \Byteam\Spectre\OAuthClient  */
    protected $oauthClients;

    function __construct()
    {
        $this->oauthClients = app('Byteam\Spectre\OAuthClient');
    }

    public function getClientEntity($clientIdentifier, $grantType = null, $clientSecret = null, $mustValidateSecret = true)
    {
        $cachedId = "spc-cli-$clientIdentifier";
        $client = \Cache::remember($cachedId, 300, function () use ($clientIdentifier) {
            return $this->oauthClients->find($clientIdentifier);
        });
        if (!is_null($client) && $client->secret == $clientSecret && !$client->revoked) {
            $client->setIdentifier($clientIdentifier);
            switch ($grantType) {
                case "password":
                case "refresh_token":
                    return $client->type == "P" ? $client : null;
            }
        }
        return null;
    }
}