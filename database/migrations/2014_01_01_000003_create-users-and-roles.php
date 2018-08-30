<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersAndRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('password');
            $table->boolean('is_active')->nullable()->default(true);
            $table->string('reset_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('role_child_role', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_role_id');
            $table->integer('child_role_id');
            $table->timestamps();
        });

        Schema::create('roles', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('role_user', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned()->index();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('role_routes', function(Blueprint $table) {
            $table->increments('id');
            $table->string('route_name');
            $table->integer('role_id')->unsigned()->index();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('navigation_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sort')->nullable();
            $table->string('icon')->nullable();
            $table->string('name');
            $table->string('route')->nullable();
            $table->string('type')->nullable();
            $table->integer('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('navigation_items');
            $table->timestamps();
        });

        Schema::create('navigation_item_role', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('navigation_item_id');
            $table->integer('role_id');
            $table->foreign('navigation_item_id')->references('id')->on('navigation_items');
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('navigation_item_role');
        Schema::dropIfExists('navigation_items');
        Schema::dropIfExists('role_routes');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_child_role');
        Schema::dropIfExists('users');
    }
}
