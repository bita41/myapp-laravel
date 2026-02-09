<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dictionaries', function (Blueprint $table) {
            $table->increments('dictionar_id');
            $table->string('parameter', 255)->nullable();
            $table->mediumText('romanian')->nullable();
            $table->mediumText('english')->nullable();
            $table->dateTime('record_update_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dictionaries');
    }
};
