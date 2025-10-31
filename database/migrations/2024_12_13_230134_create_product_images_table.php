<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('image_path');
            $table->timestamps();
        });

        // Add orderBy clause to fix the error
        DB::table('products')
            ->whereNotNull('image')
            ->orderBy('id') // Specify the orderBy clause
            ->get()
            ->each(function ($product) {
                DB::table('product_images')->insert([
                    'product_id' => $product->id,
                    'image_path' => $product->image,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->string('key_features')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('dimensions')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['key_features', 'serial_number', 'dimensions']);
            $table->string('image')->nullable();
        });

        Schema::dropIfExists('product_images');
    }
};
