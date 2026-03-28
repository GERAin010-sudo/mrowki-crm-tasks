<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('task_projects', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'planned', 'completed', 'archived'])->default('active');
            $table->enum('type', ['contract', 'one_time', 'internal'])->default('internal');
            $table->string('color', 7)->default('#2563eb');

            $table->foreignId('creator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('coordinator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('contractor_name', 255)->nullable();

            $table->foreignId('contragent_id')
                ->nullable()
                ->constrained('contragents')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_projects');
    }
};
