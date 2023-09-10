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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->enum('role', ['admin', 'sub-admin', 'reviewer', 'user'])->default('user');
            $table->string('imageUrl')->nullable();
            $table->string('track')->nullable();
            $table->text('bio')->nullable();
            $table->string('email_profile')->unique()->nullable();
            $table->string('cvUrl')->nullable();
            $table->string('githubUrl')->nullable();
            $table->string('linkedinUrl')->nullable();
            $table->string('behanceUrl')->nullable();
            $table->string('facebookUrl')->nullable();
            $table->string('twitterUrl')->nullable();
            $table->integer('code')->nullable();
            $table->timestamp('code_expired_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
