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
        Schema::create('user_roles', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name', 50)->unique();
            $table->string('description', 255)->nullable();

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

        DB::table('user_roles')->insert([
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
    }

    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
