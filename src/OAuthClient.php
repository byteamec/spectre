<?php

namespace Byteam\Spectre;


use Illuminate\Database\Eloquent\Model;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class OAuthClient extends Model implements ClientEntityInterface
{
    use EntityTrait;

    protected $table = "spc_oauth_clients";

    public function getName()
    {
        return $this->name;
    }

    public function getRedirectUri()
    {
        return explode(',', $this->redirect);
    }
}