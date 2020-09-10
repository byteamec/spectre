<?php

namespace Byteam\Spectre;


use Illuminate\Database\Eloquent\Model;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use Rennokki\QueryCache\Traits\QueryCacheable;

class OAuthClient extends Model implements ClientEntityInterface
{
    use EntityTrait, QueryCacheable;

    protected $table = "oauth_clients";

    public function getName()
    {
        return $this->name;
    }

    public function getRedirectUri()
    {
        return explode(',', $this->redirect);
    }
}