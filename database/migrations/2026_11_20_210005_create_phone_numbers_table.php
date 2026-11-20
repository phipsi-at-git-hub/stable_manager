<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phone_numbers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('person_id')
                ->constrained('persons')
                ->cascadeOnDelete();

            $table->string('phone_number', 16);

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

            $table->unique(
                ['person_id', 'phone_number'],
                'phone_numbers_person_number_unique'
            );

            $table->index('person_id');

            /*
            $table->check(
                "phone_number ~ '^\\+[1-9][0-9]{1,14}$'",
                'phone_numbers_e164_check'
            );
            */

            DB::statement("
                ALTER TABLE phone_numbers
                ADD CONSTRAINT phone_numbers_e164_check
                CHECK (phone_number ~ '^\\+[1-9][0-9]{1,14}$')
            ");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phone_numbers');
    }
};
