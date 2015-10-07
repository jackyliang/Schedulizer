<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassUrlTable extends Migration {

    /**
     * Run the migrations.
     * Create the `class_urls` table with the following columns and properties
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_urls', function(Blueprint $table)
        {
            $table->integer('year')->nullable();
            $table->text('term')->nullable();
            $table->integer('crn')->unique();
            $table->primary('crn');
            $table->text('url')->nullable();
            $table->timestamp('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     * Drop the entire class_urls table
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('class_urls');
    }

}
