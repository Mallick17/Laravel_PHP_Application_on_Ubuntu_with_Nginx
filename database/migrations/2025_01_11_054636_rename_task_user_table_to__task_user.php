<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // In your migration file



    public function up(): void

    {

        Schema::rename('taskUser', 'TaskUser'); // Renames the 'products' table to 'items'

    }



    public function down(): void

    {

        Schema::rename('TaskUser', 'taskUser'); // Reverses the change for rollback

    }

};
