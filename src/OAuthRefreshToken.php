<?php

namespace Byteam\Spectre;


use Illuminate\Database\Eloquent\Model;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;

class OAuthRefreshToken extends Model implements RefreshTokenEntityInterface
{
    use EntityTrait, RefreshTokenTrait;

    protected $table = "spc_oauth_refresh_tokens";
}