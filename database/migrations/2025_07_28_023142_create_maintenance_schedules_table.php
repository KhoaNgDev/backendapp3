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
            Schema::create('maintenance_schedules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
                $table->foreignId('repair_id')->nullable()->constrained()->onDelete('set null');
                $table->date('next_maintenance_date');
                $table->enum('status', allowed: ['pending', 'sent', 'overdue'])->default('pending');
                $table->text('note')->nullable();
                $table->timestamp('notified_at')->nullable();
                $table->timestamps();
            });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedules');
    }
};
