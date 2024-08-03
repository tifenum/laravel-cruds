<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('cin')->unique();
            $table->date('date_de_naissance');
            $table->string('adresse');
            $table->string('telephone');
            $table->string('diplome');
            $table->string('level_study');
            $table->string('email')->unique();
            $table->text('experience');
            $table->string('genre');
            $table->string('school');
            $table->string('cv');
            $table->string('lettre');
            $table->enum('status', ['not studied', 'accepted', 'refused'])->default('not studied');
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
        Schema::dropIfExists('candidatures');
    }
}
