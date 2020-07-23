<?php

namespace Byteam\Spectre\OAuth;


use Byteam\Spectre\OAuthToken;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    /** @var \Byteam\Spectre\OAuthToken  */
    protected $oauthAccessTokens;

    function __construct()
    {
        $this->oauthAccessTokens = app('Byteam\Spectre\OAuthToken');
    }

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
        /** @var $clientEntity \Byteam\Spectre\OAuthClient */
        $clientEntity = $accessTokenEntity->getClient();
        if ($clientEntity->single_session) {
            $this->oauthAccessTokens
                ->where([
                    ['user_id', '=', $accessTokenEntity->getUserIdentifier()],
                    ['client_id', '=', $clientEntity->id],
                    ['revoked', '=', false]])
                ->update(['revoked' => true]);
        }
        $accessTokenEntity->id = $accessTokenEntity->getIdentifier();
        $accessTokenEntity->expires_at = $accessTokenEntity->getExpiryDateTime();
        $accessTokenEntity->save();
    }

    public function revokeAccessToken($tokenId)
    {
        $accessTokenEntity = $this->oauthAccessTokens->find($tokenId);
        $accessTokenEntity->revoked = true;
        $accessTokenEntity->save();
    }

    public function isAccessTokenRevoked($tokenId)
    {
        $accessTokenEntity = $this->oauthAccessTokens->find($tokenId);
        if ($accessTokenEntity != null) {
            return $accessTokenEntity->revoked;
        }
        return true;
    }
}