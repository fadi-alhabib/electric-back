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
        Schema::create('footer_images', function (Blueprint $table) {
            $table->id();
            $table->string('image_path'); // Store the image file path
            $table->string('alt_text')->nullable(); // Optional: Alt text for accessibility
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('footer_images');
    }
};
