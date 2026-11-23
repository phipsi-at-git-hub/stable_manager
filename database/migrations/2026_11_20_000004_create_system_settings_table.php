<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('key')->unique();
            $table->text('value');

            $table->foreignUuid('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestampTz('created_at')->nullable();
            $table->timestampTz('updated_at')->nullable();
        });

        /*
        |--------------------------------------------------------------------------
        | Value Constraints
        |--------------------------------------------------------------------------
        */

        DB::statement("
            ALTER TABLE system_settings
            ADD CONSTRAINT system_settings_debug_mode_check
            CHECK (
                key <> 'debug_mode'
                OR value IN ('off', 'partial', 'full')
            )
        ");

        DB::statement("
            ALTER TABLE system_settings
            ADD CONSTRAINT system_settings_default_locale_check
            CHECK (
                key <> 'default_locale'
                OR value IN ('en-US', 'de-DE')
            )
        ");

        DB::statement("
            ALTER TABLE system_settings
            ADD CONSTRAINT system_settings_system_enabled_check
            CHECK (
                key <> 'system_enabled'
                OR value IN ('true', 'false')
            )
        ");

        DB::statement("
            ALTER TABLE system_settings
            ADD CONSTRAINT system_settings_user_registration_enabled_check
            CHECK (
                key <> 'user_registration_enabled'
                OR value IN ('true', 'false')
            )
        ");

        DB::statement("
            ALTER TABLE system_settings
            ADD CONSTRAINT system_settings_user_login_enabled_check
            CHECK (
                key <> 'user_login_enabled'
                OR value IN ('true', 'false')
            )
        ");

        /*
        |--------------------------------------------------------------------------
        | Initial System Settings
        |--------------------------------------------------------------------------
        */

        DB::table('system_settings')->insert([
            [
                'id' => '5c5499e8-4ed3-4e38-a689-c2e401920136',
                'key' => 'default_locale',
                'value' => 'en-US',
            ],
            [
                'id' => '3eaf62de-b88a-41fa-b674-30d077885a72',
                'key' => 'debug_mode',
                'value' => 'off',
            ],
            [
                'id' => 'd37984de-ad34-4c20-9e36-14e2e9f28fc4',
                'key' => 'system_enabled',
                'value' => 'true',
            ],
            [
                'id' => '4d2115ff-2d76-4313-8756-194daeb5b10b',
                'key' => 'user_registration_enabled',
                'value' => 'true',
            ],
            [
                'id' => '62e1b9f8-700c-45bf-b8a5-a34e0cab701f',
                'key' => 'user_login_enabled',
                'value' => 'true',
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
