<?php

namespace Byteam\Spectre\OAuth;


use Byteam\Spectre\OAuthToken;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        $token = new OAuthToken();
        $token->user_id = $userIdentifier;
        $token->scopes = implode(",", $scopes);
        $token->client_id = $clientEntity->getIdentifier();

        return $token;
    }

    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $accessTokenEntity->id = $accessTokenEntity->getIdentifier();
        $accessTokenEntity->expires_at = $accessTokenEntity->getExpiryDateTime();
        $accessTokenEntity->save();
    }

    public function revokeAccessToken($tokenId)
    {
        // TODO: Implement revokeAccessToken() method.
    }

    public function isAccessTokenRevoked($tokenId)
    {
        // TODO: Implement isAccessTokenRevoked() method.
    }
}