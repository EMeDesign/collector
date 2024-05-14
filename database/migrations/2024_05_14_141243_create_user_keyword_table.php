<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_keyword', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('keyword_id')->index();
            $table->unsignedBigInteger('item_id')->default(0)->index();
            $table->unique(['user_id', 'keyword_id', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_keyword');
    }
};
