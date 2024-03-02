<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('The name of rooms');
            $table->string('image')->nullable()->comment('The image of rooms');
            $table->text('description')->nullable()->comment('The description of rooms');
            $table->unsignedInteger('position')->index()->comment('The position of rooms');
            $table->foreignId('user_id')->constrained();
            $table->unique(['name', 'user_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
