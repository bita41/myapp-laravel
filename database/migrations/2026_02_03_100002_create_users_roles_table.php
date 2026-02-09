<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. schema: users_roles (auth/roles).
     */
    public function up(): void
    {
        Schema::create('users_roles', function (Blueprint $table) {
            $table->integer('role_id', true);
            $table->string('role_name', 50)->nullable();
            $table->string('role_description', 255)->nullable();
            $table->tinyInteger('disable_delete')->nullable();
            $table->enum('record_status_code', ['insert', 'update', 'delete'])->nullable();
            $table->integer('record_insert_user_id')->nullable();
            $table->dateTime('record_insert_date')->nullable();
            $table->integer('record_update_user_id')->nullable();
            $table->dateTime('record_update_date')->nullable();
            $table->integer('record_delete_user_id')->nullable();
            $table->dateTime('record_delete_date')->nullable();
        });

        Schema::table('users_roles', function (Blueprint $table) {
            $table->index('record_status_code');
            $table->index('record_insert_user_id');
            $table->index('record_insert_date');
            $table->index('record_update_user_id');
            $table->index('record_update_date');
            $table->index('record_delete_user_id');
            $table->index('record_delete_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_roles');
    }
};
