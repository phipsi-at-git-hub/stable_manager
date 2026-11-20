<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TYPE user_status AS ENUM (
                'active',
                'inactive',
                'blocked',
                'banned',
                'closed'
            )
        ");

        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Optional relation to a person
            $table->foreignUuid('person_id')
                ->nullable()
                ->unique()
                ->constrained('persons')
                ->nullOnDelete();

            // Authentication
            $table->string('username', 100)->unique();
            $table->string('email')->unique();
            $table->timestampTz('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            // Account settings
            $table->string('preferred_locale', 10)->default('en-US');

            // Account status
            $table->string('status')
                ->default('active');

            // Login information
            $table->timestampTz('last_login_at')->nullable();

            // Audit fields
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
        });

        DB::statement("
            ALTER TABLE users
            ALTER COLUMN status TYPE user_status
            USING status::user_status
        ");

        DB::statement("
            ALTER TABLE persons
            ADD CONSTRAINT persons_created_by_foreign
            FOREIGN KEY (created_by)
            REFERENCES users(id)
            ON DELETE SET NULL
        ");

        DB::statement("
            ALTER TABLE persons
            ADD CONSTRAINT persons_updated_by_foreign
            FOREIGN KEY (updated_by)
            REFERENCES users(id)
            ON DELETE SET NULL
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('users');

        DB::statement('DROP TYPE IF EXISTS user_status');
    }
};
