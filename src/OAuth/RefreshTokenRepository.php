<?php

namespace Byteam\Spectre\OAuth;


use Byteam\Spectre\OAuthRefreshToken;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    public function getNewRefreshToken()
    {
        return new OAuthRefreshToken();
    }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        $refreshTokenEntity->id = $refreshTokenEntity->getIdentifier();
        $refreshTokenEntity->access_token_id = $refreshTokenEntity->getAccessToken()->getIdentifier();
        $refreshTokenEntity->expires_at = $refreshTokenEntity->getExpiryDateTime();
        $refreshTokenEntity->save();
    }

    public function revokeRefreshToken($tokenId)
    {
        // TODO: Implement revokeRefreshToken() method.
    }

    public function isRefreshTokenRevoked($tokenId)
    {
        // TODO: Implement isRefreshTokenRevoked() method.
    }
}