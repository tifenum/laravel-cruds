<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
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
            $table->string('prenom')->nullable();
            $table->integer('cin')->unique();
            $table->integer('cnss')->unique();
            $table->string('post')->nullable();
            $table->date('date_de_naissance')->nullable();
            $table->string('genre')->nullable();
            $table->decimal('salaire', 15, 2)->nullable();
            $table->date('date_embauche')->nullable();
            $table->integer('tel')->nullable();
            $table->string('ville')->nullable();
            $table->string('adresse')->nullable();
            $table->string('image')->default('profile.jpg');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('EMPLOYER');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->integer('department_id')->nullable();
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
}
