<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramStudiSubmissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_study_submission', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_study_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('submission_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->integer('siswa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('program_study_submission');
    }
}
