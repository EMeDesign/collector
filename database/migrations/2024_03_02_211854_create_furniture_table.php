<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('furniture', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index()->comment('The name of furniture');
            $table->string('image')->nullable()->comment('The image of furniture');
            $table->text('description')->comment('The description of furniture');
            $table->unsignedInteger('position')->comment('The position of furniture');
            $table->unsignedTinyInteger('is_private')->default(0)->comment('If the furniture is private');
            $table->foreignId('room_id')->nullable()->comment('The foreign id for the room where the furniture set')->constrained();
            $table->foreignId('user_id')->comment('The foreign id for the user who created the furniture')->constrained();
            $table->unique(['name', 'room_id', 'user_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('furniture');
    }
};
