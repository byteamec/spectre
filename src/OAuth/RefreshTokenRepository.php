<?php

namespace Byteam\Spectre\OAuth;


use Byteam\Spectre\OAuthRefreshToken;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /** @var \Byteam\Spectre\OAuthRefreshToken  */
    protected $oauthRefreshTokens;

    function __construct()
    {
        $this->oauthRefreshTokens = app('Byteam\Spectre\OAuthRefreshToken');
    }

    public function getNewRefreshToken()
    {
        return new OAuthRefreshToken();
    }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        /** @var $clientEntity \Byteam\Spectre\OAuthClient */
        $clientEntity = $refreshTokenEntity->getAccessToken()->getClient();
        if ($clientEntity->single_session) {
            $refreshTksToBeRevoked = $this->oauthRefreshTokens
                ->join('oauth_access_tokens', 'oauth_refresh_tokens.access_token_id', '=', 'oauth_access_tokens.id')
                ->where([
                    ['user_id', '=', $refreshTokenEntity->getAccessToken()->getUserIdentifier()],
                    ['client_id', '=', $clientEntity->id],
                    ['oauth_refresh_tokens.revoked', '=', false]])
                ->pluck('oauth_refresh_tokens.id');
            $this->oauthRefreshTokens
                ->whereIn('id', $refreshTksToBeRevoked)
                ->update(['revoked' => true]);
        }
        $refreshTokenEntity->id = $refreshTokenEntity->getIdentifier();
        $refreshTokenEntity->access_token_id = $refreshTokenEntity->getAccessToken()->getIdentifier();
        $refreshTokenEntity->expires_at = $refreshTokenEntity->getExpiryDateTime();
        $refreshTokenEntity->save();
    }

    public function revokeRefreshToken($tokenId)
    {
        $refreshTokenEntity = $this->oauthRefreshTokens->find($tokenId);
        $refreshTokenEntity->revoked = true;
        $refreshTokenEntity->save();
    }

    public function isRefreshTokenRevoked($tokenId)
    {
        $refreshTokenEntity = $this->oauthRefreshTokens->find($tokenId);
        if ($refreshTokenEntity != null) {
            return $refreshTokenEntity->revoked;
        }
        return true;
    }
}