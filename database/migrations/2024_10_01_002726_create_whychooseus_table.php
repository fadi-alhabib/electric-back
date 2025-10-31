<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhychooseusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('whychooseuses', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Title column
            $table->text('description'); // Description column
            $table->timestamps(); // Created at and updated at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('whychooseus');
    }
}
