<?php

namespace Byteam\Spectre;


use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\UserEntityInterface;

class User extends Model implements AuthenticatableContract, AuthorizableContract, UserEntityInterface
{
    use Authenticatable, Authorizable, EntityTrait;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password', 'reset_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles() {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * @param string $route
     *
     * @return bool
     */
    public function canUseRoute(string $route) {
        $count = DB::query()->select('id')
            ->from('role_routes')
            ->join('roles', 'role_routes.role_id', '=', 'roles.id')
            ->join('role_user', 'roles.id', '=', 'role_user.role_id')
            ->where('role_user.user_id', '=', $this->id)
            ->where(function ($query) use ($route) {
                $query->where('role_routes.route_name', '=', $route)
                    ->orWhere('role_routes.route_name', '=', '*');
            })
            ->count();

        return $count > 0;
    }
}
