<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set order positions for existing product images based on their creation order
        $productImages = DB::table('product_images')
            ->where('order_position', 0)
            ->orderBy('product_id')
            ->orderBy('created_at')
            ->get();

        $currentProductId = null;
        $orderPosition = 0;

        foreach ($productImages as $image) {
            if ($currentProductId !== $image->product_id) {
                $currentProductId = $image->product_id;
                $orderPosition = 0;
            }

            DB::table('product_images')
                ->where('id', $image->id)
                ->update(['order_position' => $orderPosition]);

            $orderPosition++;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset all order positions to 0
        DB::table('product_images')->update(['order_position' => 0]);
    }
};
