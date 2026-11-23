<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | User Status Enum
        |--------------------------------------------------------------------------
        */

        DB::statement("
            DO $$
            BEGIN
                CREATE TYPE user_status AS ENUM (
                    'active',
                    'inactive',
                    'blocked',
                    'banned',
                    'closed'
                );
            EXCEPTION
                WHEN duplicate_object THEN
                    NULL;
            END
            $$;
        ");

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('person_id')
                ->nullable()
                ->unique()
                ->constrained('persons')
                ->restrictOnDelete();

            // Authentication
            $table->string('username', 100)->unique();

            $table->foreignUuid('login_email_id')
                ->nullable()
                ->unique()
                ->constrained('emails')
                ->restrictOnDelete();

            $table->timestampTz('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            // Account settings
            $table->string('preferred_locale', 10)->default('en-US');

            // Account status
            $table->string('status')->default('active');

            // Audit fields
            $table->timestampTz('created_at')->useCurrent();
            $table->uuid('created_by')->nullable();

            $table->timestampTz('updated_at')->useCurrent();
            $table->uuid('updated_by')->nullable();
        });

        /*
        |--------------------------------------------------------------------------
        | User Status Conversion
        |--------------------------------------------------------------------------
        */

        /*
        DB::statement("
            ALTER TABLE users
            ALTER COLUMN status TYPE user_status
            USING status::user_status
        ");
        */

        DB::statement('ALTER TABLE users ALTER COLUMN status DROP DEFAULT');

        DB::statement("
            ALTER TABLE users
            ALTER COLUMN status TYPE user_status
            USING status::user_status
        ");

        DB::statement("
            ALTER TABLE users
            ALTER COLUMN status SET DEFAULT 'active'::user_status
        ");

        /*
        |--------------------------------------------------------------------------
        | User Audit Foreign Keys
        |--------------------------------------------------------------------------
        */

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('created_by', 'users_created_by_foreign')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('updated_by', 'users_updated_by_foreign')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });

        /*
         * A user's login email must also be assigned to that user's person.
         *
         * When person_id is NULL, PostgreSQL's default MATCH SIMPLE
         * behavior skips this composite foreign key.
         *
         * When a person is deleted, only person_id is set to NULL.
         * login_email_id remains unchanged.
         */

        DB::statement("
            ALTER TABLE users
            ADD CONSTRAINT users_person_login_email_foreign
            FOREIGN KEY (person_id, login_email_id)
            REFERENCES person_emails (person_id, email_id)
            ON DELETE SET NULL (person_id)
        ");

        /*
        |--------------------------------------------------------------------------
        | User Statistics
        |--------------------------------------------------------------------------
        */

        Schema::create('user_statistics', function (Blueprint $table) {
            $table->foreignUuid('user_id')
                ->primary()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('successful_login_count')
                ->default(0);

            $table->timestampTz('first_login_at')->nullable();
            $table->timestampTz('last_login_at')->nullable();

            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
        });

        DB::statement("
            ALTER TABLE user_statistics
            ADD CONSTRAINT user_statistics_login_count_check
            CHECK (successful_login_count >= 0)
        ");

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name', 50)->unique();
            $table->string('description', 255)->nullable();

            $table->timestampTz('created_at')->useCurrent();
            $table->uuid('created_by')->nullable();

            $table->timestampTz('updated_at')->useCurrent();
            $table->uuid('updated_by')->nullable();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });

        DB::table('roles')->insert([
            [
                'id' => (string) Str::uuid(),
                'name' => 'super_admin',
                'description' => 'Full system administration',
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'admin',
                'description' => 'Administrative access',
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'user',
                'description' => 'Standard user access',
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'trainer',
                'description' => 'Trainer',
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'student',
                'description' => 'Student or learner',
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'club_member',
                'description' => 'Club member',
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'club_employer',
                'description' => 'Club employer',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | User Roles
        |--------------------------------------------------------------------------
        */

        Schema::create('user_roles', function (Blueprint $table) {
            $table->foreignUuid('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignUuid('role_id')
                ->constrained('roles')
                ->restrictOnDelete();

            $table->timestampTz('created_at')->useCurrent();
            $table->uuid('created_by')->nullable();

            $table->timestampTz('updated_at')->useCurrent();
            $table->uuid('updated_by')->nullable();

            $table->primary(['user_id', 'role_id']);

            $table->index('role_id');
        });

        Schema::table('user_roles', function (Blueprint $table) {
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });

        /*
        |--------------------------------------------------------------------------
        | Person Audit Foreign Keys
        |--------------------------------------------------------------------------
        */

        $personTables = [
            'persons',
            'addresses',
            'person_addresses',
            'phone_numbers',
            'person_phone_numbers',
            'emails',
            'person_emails',
        ];

        foreach ($personTables as $table) {
            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                $blueprint->foreign(
                    'created_by',
                    "{$table}_created_by_foreign"
                )
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();

                $blueprint->foreign(
                    'updated_by',
                    "{$table}_updated_by_foreign"
                )
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Automatic User Statistics
        |--------------------------------------------------------------------------
        */

        DB::unprepared("
            CREATE OR REPLACE FUNCTION identity_create_user_statistics()
            RETURNS TRIGGER AS $$
            BEGIN
                INSERT INTO user_statistics (
                    user_id,
                    successful_login_count,
                    created_at,
                    updated_at
                )
                VALUES (
                    NEW.id,
                    0,
                    NEW.created_at,
                    NEW.created_at
                );

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER users_create_statistics
            AFTER INSERT ON users
            FOR EACH ROW
            EXECUTE FUNCTION identity_create_user_statistics();
        ");
    }

    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Remove User Statistics Trigger
        |--------------------------------------------------------------------------
        */

        DB::statement("
            DROP TRIGGER IF EXISTS users_create_statistics ON users
        ");

        DB::statement("
            DROP FUNCTION IF EXISTS identity_create_user_statistics()
        ");

        /*
        |--------------------------------------------------------------------------
        | Remove Person Audit Foreign Keys
        |--------------------------------------------------------------------------
        */

        $personTables = [
            'persons',
            'addresses',
            'person_addresses',
            'phone_numbers',
            'person_phone_numbers',
            'emails',
            'person_emails',
        ];

        foreach ($personTables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropForeign('created_by');
                $blueprint->dropForeign('updated_by');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Remove Identity Tables
        |--------------------------------------------------------------------------
        */

        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('user_statistics');
        Schema::dropIfExists('users');

        DB::statement('DROP TYPE IF EXISTS user_status');
    }
};
