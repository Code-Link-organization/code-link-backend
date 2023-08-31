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
        Schema::create('user_profile', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('track')->nullable();
            $table->text('bio')->nullable();
            $table->string('email')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('phoneNumber')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->string('imageUrl')->nullable();
            $table->string('cvUrl')->nullable();
            $table->string('githubUrl')->nullable();
            $table->string('linkedinUrl')->nullable();
            $table->string('behanceUrl')->nullable();
            $table->string('twitterUrl')->nullable();
            $table->string('facebookUrl')->nullable();
            $table->dateTime('date_of_birth')->nullable();
            $table->string('Address')->nullable();
            $table->enum('education', ['preparatory', 'secondary','university','graduated','other'])->nullable();
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
