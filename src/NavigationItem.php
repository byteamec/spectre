<?php

namespace Byteam\Spectre;


use Illuminate\Database\Eloquent\Model;

class NavigationItem extends Model
{
    public  function roles() {
        return $this->belongsToMany(Role::class);
    }

    public function children() {
        return $this->hasMany(NavigationItem::class, 'parent_id')->with('children');
    }

    public function parent() {
        return $this->belongsTo(NavigationItem::class, 'parent_id');
    }
}