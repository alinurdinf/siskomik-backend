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
        Schema::create('incoming_letters', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number');
            $table->string('letter_number')->nullable();
            $table->string('subject');
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->string('type');
            $table->date('letter_date')->nullable();
            $table->text('description')->nullable();
            $table->text('note')->nullable();
            $table->date('submit_date')->nullable();
            $table->text('file_path')->nullable();
            $table->boolean('is_validated')->default(false);
            $table->string('status');
            $table->datetime('date_approval_result')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_letters');
    }
};
