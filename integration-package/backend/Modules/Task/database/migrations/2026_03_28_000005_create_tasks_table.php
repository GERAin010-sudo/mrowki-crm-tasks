<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title', 500);
            $table->text('description')->nullable();

            $table->foreignId('status_id')
                ->nullable()
                ->constrained('task_statuses')
                ->nullOnDelete();

            $table->foreignId('priority_id')
                ->nullable()
                ->constrained('task_priorities')
                ->nullOnDelete();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('task_categories')
                ->nullOnDelete();

            $table->foreignId('project_id')
                ->nullable()
                ->constrained('task_projects')
                ->nullOnDelete();

            $table->foreignId('creator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('assignee_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('assignee_type', ['user', 'team', 'department'])->default('user');

            $table->foreignId('contragent_id')
                ->nullable()
                ->constrained('contragents')
                ->nullOnDelete();

            $table->dateTime('deadline')->nullable();
            $table->string('position', 50)->nullable(); // kanban ordering

            // Linked entity (polymorphic-like, flexible)
            $table->string('linked_entity_type', 50)->nullable();
            $table->unsignedBigInteger('linked_entity_id')->nullable();
            $table->string('linked_entity_name', 255)->nullable();

            $table->timestamps();

            $table->index(['status_id']);
            $table->index(['priority_id']);
            $table->index(['assignee_id']);
            $table->index(['project_id']);
            $table->index(['deadline']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
