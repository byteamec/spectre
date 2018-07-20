<?php

namespace Byteam\Spectre;


use Illuminate\Database\Eloquent\Model;

class NavigationItem extends Model
{
    protected $table = 'spc_navigation_items';

    public  function roles() {
        return $this->belongsToMany(Role::class, 'spc_navigation_item_role');
    }

    public function children() {
        return $this->hasMany(NavigationItem::class, 'parent_id')->with('children');
    }

    public function parent() {
        return $this->belongsTo(NavigationItem::class, 'parent_id');
    }
}