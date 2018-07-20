<?php

namespace Byteam\Spectre;


use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = ['name', 'slug', 'description', 'created_at', 'updated_at'];

    public function allowedRoutes() {
        return $this->hasMany(RoleRoute::class);
    }

    public function children()
    {
        return $this->belongsToMany(Role::class , 'role_child_role', 'parent_role_id', 'child_role_id');
    }
}