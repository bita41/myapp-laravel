<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. Users schema (auth/roles).
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('user_id', true);
            $table->string('email', 50)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('displayname', 50)->nullable();
            $table->string('phone', 50)->nullable();
            $table->integer('role_id')->nullable();
            $table->integer('language_id')->nullable();
            $table->string('img', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->dateTime('lastlogin')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->enum('record_status_code', ['insert', 'update', 'delete'])->nullable();
            $table->integer('record_insert_user_id')->nullable();
            $table->dateTime('record_insert_date')->nullable();
            $table->integer('record_update_user_id')->nullable();
            $table->dateTime('record_update_date')->nullable();
            $table->integer('record_delete_user_id')->nullable();
            $table->dateTime('record_delete_date')->nullable();
            $table->dateTime('record_lastlogin_date')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role_id');
            $table->index('language_id');
            $table->index('active');
            $table->index('record_status_code');
            $table->index('record_insert_user_id');
            $table->index('record_insert_date');
            $table->index('record_update_user_id');
            $table->index('record_update_date');
            $table->index('record_delete_user_id');
            $table->index('record_delete_date');
            $table->index('url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
