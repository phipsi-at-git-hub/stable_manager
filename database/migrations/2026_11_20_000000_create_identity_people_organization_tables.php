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
        | Persons
        |--------------------------------------------------------------------------
        */

        Schema::create('persons', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('title', 100)->nullable();
            $table->date('birthdate')->nullable();
            $table->string('gender', 20)->nullable();

            $table->timestampTz('created_at')->useCurrent();
            $table->uuid('created_by')->nullable();

            $table->timestampTz('updated_at')->useCurrent();
            $table->uuid('updated_by')->nullable();

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

        /*
        |--------------------------------------------------------------------------
        | Addresses
        |--------------------------------------------------------------------------
        */

        Schema::create('addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('street', 255);
            $table->string('postal_code', 20);
            $table->string('city', 150);
            $table->char('country', 2);

            $table->timestampTz('created_at')->useCurrent();
            $table->uuid('created_by')->nullable();

            $table->timestampTz('updated_at')->useCurrent();
            $table->uuid('updated_by')->nullable();
        });

        DB::statement("
            CREATE UNIQUE INDEX addresses_normalized_unique
            ON addresses (
                lower(btrim(street)),
                lower(btrim(postal_code)),
                lower(btrim(city)),
                upper(country)
            )
        ");

        DB::statement("
            ALTER TABLE addresses
            ADD CONSTRAINT addresses_country_check
            CHECK (country = upper(country))
        ");

        /*
        |--------------------------------------------------------------------------
        | Person Addresses
        |--------------------------------------------------------------------------
        */

        Schema::create('person_addresses', function (Blueprint $table) {
            $table->foreignUuid('person_id')
                ->constrained('persons')
                ->cascadeOnDelete();

            $table->foreignUuid('address_id')
                ->constrained('addresses')
                ->restrictOnDelete();

            $table->boolean('is_primary')->default(false);

            $table->timestampTz('created_at')->useCurrent();
            $table->uuid('created_by')->nullable();

            $table->timestampTz('updated_at')->useCurrent();
            $table->uuid('updated_by')->nullable();

            $table->primary(['person_id', 'address_id']);

            $table->index('address_id');
        });

        DB::statement("
            CREATE UNIQUE INDEX person_addresses_primary_unique
            ON person_addresses (person_id)
            WHERE is_primary = TRUE
        ");

        /*
        |--------------------------------------------------------------------------
        | Phone Numbers
        |--------------------------------------------------------------------------
        */

        Schema::create('phone_numbers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('phone_number', 16)->unique();

            $table->timestampTz('created_at')->useCurrent();
            $table->uuid('created_by')->nullable();

            $table->timestampTz('updated_at')->useCurrent();
            $table->uuid('updated_by')->nullable();
        });

        DB::statement("
            ALTER TABLE phone_numbers
            ADD CONSTRAINT phone_numbers_e164_check
            CHECK (phone_number ~ '^\\+[1-9][0-9]{1,14}$')
        ");

        /*
        |--------------------------------------------------------------------------
        | Person Phone Numbers
        |--------------------------------------------------------------------------
        */

        Schema::create('person_phone_numbers', function (Blueprint $table) {
            $table->foreignUuid('person_id')
                ->constrained('persons')
                ->cascadeOnDelete();

            $table->foreignUuid('phone_number_id')
                ->constrained('phone_numbers')
                ->restrictOnDelete();

            $table->boolean('is_primary')->default(false);

            $table->timestampTz('created_at')->useCurrent();
            $table->uuid('created_by')->nullable();

            $table->timestampTz('updated_at')->useCurrent();
            $table->uuid('updated_by')->nullable();

            $table->primary(['person_id', 'phone_number_id']);

            $table->index('phone_number_id');
        });

        DB::statement("
            CREATE UNIQUE INDEX person_phone_numbers_primary_unique
            ON person_phone_numbers (person_id)
            WHERE is_primary = TRUE
        ");

        /*
        |--------------------------------------------------------------------------
        | Emails
        |--------------------------------------------------------------------------
        */

        Schema::create('emails', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('email', 254);

            $table->timestampTz('created_at')->useCurrent();
            $table->uuid('created_by')->nullable();

            $table->timestampTz('updated_at')->useCurrent();
            $table->uuid('updated_by')->nullable();
        });

        DB::statement("
            ALTER TABLE emails
            ADD CONSTRAINT emails_not_empty_check
            CHECK (email = btrim(email) AND email <> '')
        ");

        DB::statement("
            CREATE UNIQUE INDEX emails_normalized_unique
            ON emails (lower(email))
        ");

        /*
        |--------------------------------------------------------------------------
        | Person Emails
        |--------------------------------------------------------------------------
        */

        Schema::create('person_emails', function (Blueprint $table) {
            $table->foreignUuid('person_id')
                ->constrained('persons')
                ->cascadeOnDelete();

            $table->foreignUuid('email_id')
                ->constrained('emails')
                ->restrictOnDelete();

            $table->boolean('is_primary')->default(false);

            $table->timestampTz('created_at')->useCurrent();
            $table->uuid('created_by')->nullable();

            $table->timestampTz('updated_at')->useCurrent();
            $table->uuid('updated_by')->nullable();

            $table->primary(['person_id', 'email_id']);

            $table->index('email_id');
        });

        DB::statement("
            CREATE UNIQUE INDEX person_emails_primary_unique
            ON person_emails (person_id)
            WHERE is_primary = TRUE
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
                ->nullOnDelete();

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
        |--------------------------------------------------------------------------
        | User Login Email Constraint
        |--------------------------------------------------------------------------
        |
        | A user's login email must also belong to their linked person.
        |
        | If person_id is NULL, the composite FK is skipped.
        | When a person is deleted, only person_id is set to NULL.
        |
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
        | Clubs
        |--------------------------------------------------------------------------
        */

        Schema::create('clubs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name', 150);

            $table->timestampTz('created_at')->useCurrent();
            $table->uuid('created_by')->nullable();

            $table->timestampTz('updated_at')->useCurrent();
            $table->uuid('updated_by')->nullable();
        });

        /*
        |--------------------------------------------------------------------------
        | Stables
        |--------------------------------------------------------------------------
        |
        | A stable may belong to a club.
        | Independent stables are supported through nullable club_id.
        |
        */

        Schema::create('stables', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('club_id')
                ->nullable()
                ->constrained('clubs')
                ->restrictOnDelete();

            $table->string('name', 150);

            $table->timestampTz('created_at')->useCurrent();
            $table->uuid('created_by')->nullable();

            $table->timestampTz('updated_at')->useCurrent();
            $table->uuid('updated_by')->nullable();

            $table->index('club_id');
        });

        /*
        |--------------------------------------------------------------------------
        | Club and Stable Contact Associations
        |--------------------------------------------------------------------------
        */

        $organizationContactTables = [
            'club_addresses' => ['club_id', 'addresses', 'address_id'],
            'club_emails' => ['club_id', 'emails', 'email_id'],
            'club_phone_numbers' => ['club_id', 'phone_numbers', 'phone_number_id'],
            'stable_addresses' => ['stable_id', 'addresses', 'address_id'],
            'stable_emails' => ['stable_id', 'emails', 'email_id'],
            'stable_phone_numbers' => ['stable_id', 'phone_numbers', 'phone_number_id'],
        ];

        foreach ($organizationContactTables as $tableName => [$ownerColumn, $contactTable, $contactColumn]) {
            Schema::create($tableName, function (Blueprint $table) use ($ownerColumn, $contactTable, $contactColumn) {
                $ownerTable = $ownerColumn === 'club_id' ? 'clubs' : 'stables';

                $table->foreignUuid($ownerColumn)
                    ->constrained($ownerTable)
                    ->cascadeOnDelete();

                $table->foreignUuid($contactColumn)
                    ->constrained($contactTable)
                    ->restrictOnDelete();

                $table->boolean('is_primary')->default(false);

                $table->timestampTz('created_at')->useCurrent();
                $table->uuid('created_by')->nullable();

                $table->timestampTz('updated_at')->useCurrent();
                $table->uuid('updated_by')->nullable();

                $table->primary([$ownerColumn, $contactColumn]);
                $table->index($contactColumn);
            });

            DB::statement("
                CREATE UNIQUE INDEX {$tableName}_primary_unique
                ON {$tableName} ({$ownerColumn})
                WHERE is_primary = TRUE
            ");

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->foreign(
                    'created_by',
                    "{$tableName}_created_by_foreign"
                )
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();

                $table->foreign(
                    'updated_by',
                    "{$tableName}_updated_by_foreign"
                )
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        |
        | entity_type defines which entity a role belongs to:
        | user or person.
        |
        */

        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name', 50)->unique();
            $table->string('entity_type', 20);
            $table->string('description', 255)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestampTz('created_at')->useCurrent();
            $table->uuid('created_by')->nullable();

            $table->timestampTz('updated_at')->useCurrent();
            $table->uuid('updated_by')->nullable();
        });

        DB::statement("
            ALTER TABLE roles
            ADD CONSTRAINT roles_entity_type_check
            CHECK (entity_type IN ('user', 'person'))
        ");

        /*
        |--------------------------------------------------------------------------
        | Role Audit Foreign Keys
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Default Roles
        |--------------------------------------------------------------------------
        */

        DB::table('roles')->insert([
            [
                'id' => (string) Str::uuid(),
                'name' => 'super_admin',
                'entity_type' => 'user',
                'description' => 'Full system administration',
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'admin',
                'entity_type' => 'user',
                'description' => 'Administrative access',
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'user',
                'entity_type' => 'user',
                'description' => 'Standard user access',
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'trainer',
                'entity_type' => 'person',
                'description' => 'Trainer',
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'student',
                'entity_type' => 'person',
                'description' => 'Student or learner',
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'club_member',
                'entity_type' => 'person',
                'description' => 'Club member application role',
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'club_employer',
                'entity_type' => 'person',
                'description' => 'Club employer',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | User Roles
        |--------------------------------------------------------------------------
        |
        | Both club_id and stable_id NULL means global scope.
        | Exactly one populated column means a scoped assignment.
        |
        */

        Schema::create('user_roles', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignUuid('role_id')
                ->constrained('roles')
                ->restrictOnDelete();

            $table->foreignUuid('club_id')
                ->nullable()
                ->constrained('clubs')
                ->cascadeOnDelete();

            $table->foreignUuid('stable_id')
                ->nullable()
                ->constrained('stables')
                ->cascadeOnDelete();

            $table->timestampTz('created_at')->useCurrent();
            $table->uuid('created_by')->nullable();

            $table->timestampTz('updated_at')->useCurrent();
            $table->uuid('updated_by')->nullable();

            $table->index('role_id');
            $table->index('club_id');
            $table->index('stable_id');
        });

        DB::statement("
            ALTER TABLE user_roles
            ADD CONSTRAINT user_roles_scope_check
            CHECK (
                club_id IS NULL
                OR stable_id IS NULL
            )
        ");

        DB::statement("
            CREATE UNIQUE INDEX user_roles_global_unique
            ON user_roles (user_id, role_id)
            WHERE club_id IS NULL AND stable_id IS NULL
        ");

        DB::statement("
            CREATE UNIQUE INDEX user_roles_club_unique
            ON user_roles (user_id, role_id, club_id)
            WHERE club_id IS NOT NULL
        ");

        DB::statement("
            CREATE UNIQUE INDEX user_roles_stable_unique
            ON user_roles (user_id, role_id, stable_id)
            WHERE stable_id IS NOT NULL
        ");

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
        | Person Roles
        |--------------------------------------------------------------------------
        |
        | Person roles must always have either a club or stable scope.
        | Global person roles are not supported.
        |
        */

        Schema::create('person_roles', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('person_id')
                ->constrained('persons')
                ->cascadeOnDelete();

            $table->foreignUuid('role_id')
                ->constrained('roles')
                ->restrictOnDelete();

            $table->foreignUuid('club_id')
                ->nullable()
                ->constrained('clubs')
                ->cascadeOnDelete();

            $table->foreignUuid('stable_id')
                ->nullable()
                ->constrained('stables')
                ->cascadeOnDelete();

            $table->timestampTz('created_at')->useCurrent();
            $table->uuid('created_by')->nullable();

            $table->timestampTz('updated_at')->useCurrent();
            $table->uuid('updated_by')->nullable();

            $table->index('role_id');
            $table->index('club_id');
            $table->index('stable_id');
        });

        DB::statement("
            ALTER TABLE person_roles
            ADD CONSTRAINT person_roles_scope_check
            CHECK (
                (club_id IS NOT NULL AND stable_id IS NULL)
                OR
                (club_id IS NULL AND stable_id IS NOT NULL)
            )
        ");

        DB::statement("
            CREATE UNIQUE INDEX person_roles_club_unique
            ON person_roles (person_id, role_id, club_id)
            WHERE club_id IS NOT NULL
        ");

        DB::statement("
            CREATE UNIQUE INDEX person_roles_stable_unique
            ON person_roles (person_id, role_id, stable_id)
            WHERE stable_id IS NOT NULL
        ");

        Schema::table('person_roles', function (Blueprint $table) {
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
        | Validate Role Entity Type
        |--------------------------------------------------------------------------
        |
        | user_roles accepts only roles with entity_type = user.
        | person_roles accepts only roles with entity_type = person.
        |
        */

        DB::unprepared("
            CREATE OR REPLACE FUNCTION validate_role_assignment_entity_type()
            RETURNS TRIGGER AS $$
            DECLARE
                expected_entity_type VARCHAR(20);
                actual_entity_type VARCHAR(20);
            BEGIN
                IF TG_TABLE_NAME = 'user_roles' THEN
                    expected_entity_type := 'user';
                ELSE
                    expected_entity_type := 'person';
                END IF;

                SELECT entity_type
                INTO actual_entity_type
                FROM roles
                WHERE id = NEW.role_id;

                IF actual_entity_type IS DISTINCT FROM expected_entity_type THEN
                    RAISE EXCEPTION
                        'Role % has entity_type %, expected %',
                        NEW.role_id,
                        actual_entity_type,
                        expected_entity_type;
                END IF;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER user_roles_validate_entity_type
            BEFORE INSERT OR UPDATE OF role_id
            ON user_roles
            FOR EACH ROW
            EXECUTE FUNCTION validate_role_assignment_entity_type();
        ");

        DB::unprepared("
            CREATE TRIGGER person_roles_validate_entity_type
            BEFORE INSERT OR UPDATE OF role_id
            ON person_roles
            FOR EACH ROW
            EXECUTE FUNCTION validate_role_assignment_entity_type();
        ");

        /*
        |--------------------------------------------------------------------------
        | Audit Foreign Keys for Person Tables
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
        | Audit Foreign Keys for Organization Tables
        |--------------------------------------------------------------------------
        */

        foreach (['clubs', 'stables'] as $table) {
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
        | Remove Triggers and Functions
        |--------------------------------------------------------------------------
        */

        DB::statement("
            DROP TRIGGER IF EXISTS users_create_statistics ON users
        ");

        DB::statement("
            DROP FUNCTION IF EXISTS identity_create_user_statistics()
        ");

        DB::statement("
            DROP TRIGGER IF EXISTS user_roles_validate_entity_type
            ON user_roles
        ");

        DB::statement("
            DROP TRIGGER IF EXISTS person_roles_validate_entity_type
            ON person_roles
        ");

        DB::statement("
            DROP FUNCTION IF EXISTS validate_role_assignment_entity_type()
        ");

        /*
        |--------------------------------------------------------------------------
        | Remove Role Tables
        |--------------------------------------------------------------------------
        */

        Schema::dropIfExists('person_roles');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('roles');

        /*
        |--------------------------------------------------------------------------
        | Remove Organization Tables
        |--------------------------------------------------------------------------
        */

        Schema::dropIfExists('stable_phone_numbers');
        Schema::dropIfExists('stable_emails');
        Schema::dropIfExists('stable_addresses');
        Schema::dropIfExists('club_phone_numbers');
        Schema::dropIfExists('club_emails');
        Schema::dropIfExists('club_addresses');

        Schema::dropIfExists('stables');
        Schema::dropIfExists('clubs');

        /*
        |--------------------------------------------------------------------------
        | Remove Identity Tables
        |--------------------------------------------------------------------------
        */

        Schema::dropIfExists('user_statistics');
        Schema::dropIfExists('users');

        DB::statement('DROP TYPE IF EXISTS user_status');

        /*
        |--------------------------------------------------------------------------
        | Remove Person Tables
        |--------------------------------------------------------------------------
        */

        Schema::dropIfExists('person_emails');
        Schema::dropIfExists('emails');

        Schema::dropIfExists('person_phone_numbers');
        Schema::dropIfExists('phone_numbers');

        Schema::dropIfExists('person_addresses');
        Schema::dropIfExists('addresses');

        Schema::dropIfExists('persons');
    }
};
