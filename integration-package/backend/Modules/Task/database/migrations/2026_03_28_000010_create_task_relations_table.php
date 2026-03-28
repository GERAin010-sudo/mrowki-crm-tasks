<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('task_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->foreignId('related_task_id')->constrained('tasks')->cascadeOnDelete();
            $table->enum('type', ['blocks', 'blocked_by', 'related', 'duplicate'])->default('related');
            $table->timestamps();

            $table->unique(['task_id', 'related_task_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_relations');
    }
};
