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
        Schema::create('request_letters', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique()->comment('Nomor Surat');
            $table->string('subject');
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->date('letter_date')->nullable();
            $table->text('description')->nullable();
            $table->text('note')->nullable();
            $table->date('submit_date')->nullable();
            $table->enum('status', ['SEND', 'ON-PROCESS', 'VALIDATED', 'REJECTED']);
            $table->datetime('date_approval_result')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_letters');
    }
};
