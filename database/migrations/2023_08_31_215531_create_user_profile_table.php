<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('governate')->nullable();
            $table->string('university')->nullable();
            $table->string('faculty')->nullable();
            $table->string('birthDate')->nullable();
            $table->string('emailProfile')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->string('projects')->nullable();
            $table->string('progLanguages')->nullable();
            $table->string('cvUrl')->nullable();
            $table->string('githubUrl')->nullable();
            $table->string('linkedinUrl')->nullable();
            $table->string('behanceUrl')->nullable();
            $table->string('facebookUrl')->nullable();
            $table->string('twitterUrl')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('user_profile');
    }
};
