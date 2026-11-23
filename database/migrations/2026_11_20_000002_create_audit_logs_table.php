<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('event', 100);

            // Polymorphic relation to the audited model.
            $table->string('auditable_type', 255)->nullable();
            $table->uuid('auditable_id')->nullable();

            $table->jsonb('old_values')->nullable();
            $table->jsonb('new_values')->nullable();

            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestampTz('created_at')->useCurrent();

            $table->index(
                ['auditable_type', 'auditable_id'],
                'audit_logs_auditable_index'
            );

            $table->index('created_at', 'audit_logs_created_at_index');
            $table->index('event', 'audit_logs_event_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
