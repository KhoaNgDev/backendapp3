<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_feedback_id')
                ->constrained('repair_feedback') 
                ->onDelete('cascade');
            $table->text('reply');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_replies');
    }
};
