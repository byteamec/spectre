<?php

namespace Byteam\Spectre\OAuth;


use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    )
    {
        if ($clientEntity->user_type == null)
            $users = app('\Byteam\Spectre\User');
        else
            $users = app($clientEntity->user_type);

        $user = $users->cacheFor(config('spectre.cache.userTimeout', 24 * 3600))
            ->where('name', $username)->first();
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