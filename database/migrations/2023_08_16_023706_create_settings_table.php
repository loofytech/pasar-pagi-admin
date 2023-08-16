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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->text('tagline_home')->nullable();
            $table->text('tagline_information')->nullable();
            $table->text('tagline_information_description')->nullable();
            $table->text('tagline_information_goal')->nullable();
            $table->text('tagline_information_position')->nullable();
            $table->text('tagline_information_personality_description')->nullable();
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
        Schema::dropIfExists('settings');
    }
};
