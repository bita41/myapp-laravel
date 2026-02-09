<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. schema: users_permissions (auth/roles).
     */
    public function up(): void
    {
        Schema::create('users_permissions', function (Blueprint $table) {
            $table->integer('permission_id', true);
            $table->integer('role_id')->nullable();
            $table->integer('module_id')->nullable();
            $table->tinyInteger('permission_r')->nullable();
            $table->tinyInteger('permission_w')->nullable();
            $table->tinyInteger('permission_a')->nullable();
            $table->tinyInteger('permission_d')->nullable();
        });

        Schema::table('users_permissions', function (Blueprint $table) {
            $table->index('module_id');
            $table->index('role_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_permissions');
    }
};
