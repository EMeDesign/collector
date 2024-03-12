<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('constructions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('The name of constructions');
            $table->string('image')->nullable()->comment('The image of constructions');
            $table->string('location')->comment('The location of constructions');
            $table->text('description')->comment('The description of constructions');
            $table->unsignedInteger('position')->index()->comment('The position of constructions');
            $table->unsignedBigInteger('user_id');
            $table->unique(['name', 'user_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('constructions');
    }
};
