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
        Schema::table('contracts', function (Blueprint $table) {
            $table->renameColumn('contact_file', 'contract_file');
        });
    }
    
    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->renameColumn('contract_file', 'contact_file');
        });
    }
    
};
