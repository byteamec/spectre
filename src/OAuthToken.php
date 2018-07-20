<?php

namespace Byteam\Spectre;


use Illuminate\Database\Eloquent\Model;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

class OAuthToken extends Model implements AccessTokenEntityInterface
{
    use EntityTrait, AccessTokenTrait, TokenEntityTrait;

    protected $table = "oauth_access_tokens";
}