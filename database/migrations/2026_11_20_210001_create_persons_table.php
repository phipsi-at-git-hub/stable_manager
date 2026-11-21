<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('title', 100)->nullable();
            $table->date('birthdate')->nullable();
            $table->string('gender', 20)->nullable();

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

            $table->index(
                ['last_name', 'first_name'],
                'persons_last_name_first_name_index'
            );
        });

        DB::statement("
            ALTER TABLE persons
            ADD CONSTRAINT persons_gender_check
            CHECK (
                gender IS NULL
                OR gender IN ('female', 'male', 'non-binary')
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};
