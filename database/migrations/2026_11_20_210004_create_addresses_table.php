<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('person_id')
                ->constrained('persons')
                ->cascadeOnDelete();

            $table->string('street', 255);
            $table->string('postal_code', 20);
            $table->string('city', 150);
            $table->char('country', 2);

            $table->timestampTz('created_at')->useCurrent();
            $table->foreignUuid('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestampTz('updated_at')->useCurrent();
            $table->foreignUuid('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->index('person_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
