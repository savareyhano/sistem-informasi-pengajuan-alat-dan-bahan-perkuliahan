<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRealizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('realizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('submission_detail_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama_barang');
            $table->string('image_path');
            $table->integer('jumlah')->default(0);
            $table->integer('harga_satuan');
            $table->integer('harga_total');
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('realizations');
    }
}
