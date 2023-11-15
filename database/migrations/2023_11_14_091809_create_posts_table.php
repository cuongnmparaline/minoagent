<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 255);
            $table->binary('image')->nullable();
            $table->string('summary', 500)->nullable();
            $table->text('content');
            $table->foreignIdFor(\App\Models\PostCategories::class, 'category_id')->nullable()->change();
            $table->foreignIdFor(\App\Models\Admin::class, 'user_id');
            $table->foreignIdFor(\App\Models\PostStatus::class, 'status_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
