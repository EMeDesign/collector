<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index()->comment('The name of items');
            $table->string('image')->nullable()->comment('The image of items');
            $table->string('description')->comment('The description of items');
            $table->integer('quantity')->default(0)->comment('The quantity of items');
            $table->unsignedTinyInteger('is_private')->default(0)->comment('If the item is private');
            $table->unsignedBigInteger('furniture_id')->comment('The foreign id for the furniture where the item locate');
            $table->unsignedBigInteger('category_id')->comment('The foreign id for the category which the item use');
            $table->unsignedBigInteger('unit_id')->comment('The foreign id for the unit which the item use');
            $table->unsignedBigInteger('user_id')->comment('The foreign id for the user who created the item');
            $table->unsignedBigInteger('owner_id')->default(0)->comment('The foreign id for the user who own the item');
            $table->timestamp('obtained_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
