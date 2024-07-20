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
        Schema::create('conges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_de_conge_id')->constrained()->onDelete('cascade');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('etat', ['not studied', 'refused', 'accepted'])->default('not studied');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('conges');
    }
};
