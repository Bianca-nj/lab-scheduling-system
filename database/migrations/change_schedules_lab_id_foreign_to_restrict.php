<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['lab_id']);

            $table->foreign('lab_id')
                ->references('id')->on('laboratories')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['lab_id']);

            $table->foreign('lab_id')
                ->references('id')->on('laboratories')
                ->onDelete('cascade');
        });
    }
};