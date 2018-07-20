<?php

namespace Byteam\Spectre;


use Illuminate\Database\Eloquent\Model;

class NavigationItem extends Model
{
    protected $table = 'navigation_items';

    public  function roles() {
        return $this->belongsToMany(Role::class, 'navigation_item_role');
    }

    public function children() {
        return $this->hasMany(NavigationItem::class, 'parent_id')->with('children');
    }

    public function parent() {
        return $this->belongsTo(NavigationItem::class, 'parent_id');
    }
}