<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. schema: users_roles_modules (auth/roles).
     */
    public function up(): void
    {
        Schema::create('users_roles_modules', function (Blueprint $table) {
            $table->integer('module_id', true);
            $table->integer('module_parent_id')->nullable();
            $table->string('module_name', 50)->nullable();
            $table->integer('position')->nullable();
        });

        Schema::table('users_roles_modules', function (Blueprint $table) {
            $table->index('module_parent_id');
            $table->index('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_roles_modules');
    }
};
