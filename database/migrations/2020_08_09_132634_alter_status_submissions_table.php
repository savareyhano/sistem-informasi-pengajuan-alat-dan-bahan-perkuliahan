<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterStatusSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->enum('status', [1, 2, 3, 4])->default(1)->comment('1: pengajuan, 2: negosiasi, 3: pengajuan tahap 2, 4: realisasi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->enum('status', [1, 2, 3])->default(1)->comment('1: pengajuan, 2: negosiasi, 3: realisasi');
        });
    }
}
