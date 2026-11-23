<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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

        // Eine normalisierte Adresse darf nur einmal existieren.
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

        // Case-insensitive Eindeutigkeit.
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
    }

    public function down(): void
    {
        Schema::dropIfExists('person_emails');
        Schema::dropIfExists('emails');

        Schema::dropIfExists('person_phone_numbers');
        Schema::dropIfExists('phone_numbers');

        Schema::dropIfExists('person_addresses');
        Schema::dropIfExists('addresses');

        Schema::dropIfExists('persons');
    }
};
