<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Attendence', function (Blueprint $table) {
            $table->tinyInteger('status')->default(-1)->comment('-1: Chưa điểm danh, 0: Vắng, 1: Có mặt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Attendence', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
