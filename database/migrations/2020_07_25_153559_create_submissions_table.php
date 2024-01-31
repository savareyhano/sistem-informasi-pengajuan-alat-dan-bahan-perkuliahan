<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_akademik', 9);
            $table->enum('semester', ['genap', 'ganjil']);
            $table->integer('siswa')->nullable();
            $table->bigInteger('pagu')->nullable();
            $table->enum('status', [1, 2, 3])->default(1)->comment('1: pengajuan, 2: negosiasi, 3: realisasi');
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
        Schema::dropIfExists('submissions');
    }
}
