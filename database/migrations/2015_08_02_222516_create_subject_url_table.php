<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectUrlTable extends Migration {

    /**
     * Run the migrations.
     * This creates the `subject_urls` table
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_urls', function(Blueprint $table)
        {
            $table->integer('year')->nullable();
            $table->text('term')->nullable();
            $table->text('url')->nullable();
            $table->timestamp('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     * Drops the `subject_urls` table`
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('subject_urls');
    }
}
