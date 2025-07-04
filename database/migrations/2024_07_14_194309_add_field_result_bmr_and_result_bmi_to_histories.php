<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('histories', function (Blueprint $table) {
            $table->string('result_bmr')->nullable()->after('duration')->default(0);
            $table->string('result_bmi')->nullable()->after('duration')->default(0);
            $table->unsignedBigInteger('weight')->nullable()->after('duration')->default(0);
            $table->unsignedBigInteger('height')->nullable()->after('duration')->default(0);
            $table->unsignedBigInteger('imt')->nullable()->after('duration')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('histories', function (Blueprint $table) {
            //
        });
    }
};
