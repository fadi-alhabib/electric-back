<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_category_id')->constrained('sub_categories')->onDelete('cascade'); // Foreign key to categories table
            $table->string('title');
            $table->text('description');
            $table->string('image')->nullable(); // Image can be nullable
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
