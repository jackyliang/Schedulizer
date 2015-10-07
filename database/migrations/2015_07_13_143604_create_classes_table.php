<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassesTable extends Migration {

	/**
	 * Run the migrations.
     * This adds in the `classes` table that saves the scraped information
     * from TMS (Drexel Term Master Schedule)
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('classes', function(Blueprint $table)
		{
            $table->integer('year')->nullable();
            $table->text('term')->nullable();
            $table->text('subject_code')->nullable();
            $table->text('course_no')->nullable();
            $table->text('instr_type')->nullable();
            $table->text('instr_method')->nullable();
            $table->text('section')->nullable();
            $table->integer('crn')->unique();
            $table->primary('crn');
            $table->text('course_title')->nullable();
            $table->float('credits')->nullable();
            $table->text('day')->nullable();
            $table->text('time')->nullable();
            $table->text('instructor')->nullable();
            $table->text('campus')->nullable();
            $table->integer('max_enroll')->nullable();
            $table->integer('enroll')->nullable();
            $table->text('building')->nullable();
            $table->text('room')->nullable();
            $table->text('description')->nullable();
            $table->text('pre_reqs')->nullable();
            $table->text('co_reqs')->nullable();
            $table->timestamp('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('classes');
	}

}
