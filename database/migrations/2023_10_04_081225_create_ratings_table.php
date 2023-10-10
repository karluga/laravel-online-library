<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id'); // Unsigned integer for foreign key
            $table->unsignedBigInteger('user_id'); // Unsigned integer for foreign key
            $table->integer('rating');
            $table->timestamps();
        
            // Define foreign key constraints
            $table->foreign('book_id')
                  ->references('id')
                  ->on('catalogs')
                  ->onDelete('cascade'); // Cascade on delete
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade'); // Cascade on delete if you have a 'users' table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
