<?php

namespace Byteam\Spectre\OAuth;


use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /** @var \Byteam\Spectre\User  */
    protected $users;

    function __construct()
    {
        $this->users = app('Byteam\Spectre\User');
    }

    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    )
    {
        $user = $this->users->where('name', $username)->first();
        if (!is_null($user)) {
            $user->setIdentifier($user->id);
            $hasher = app('hash');
            if ($hasher->check($password, $user->getAuthPassword())) {
                return $user;
            }
        }
        return null;
    }
}