<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('admin')->change();
            });

            DB::table('users')
                ->where('role', 'superadmin')
                ->update(['role' => 'admin']);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('superadmin')->change();
            });

            DB::table('users')
                ->where('role', 'admin')
                ->update(['role' => 'superadmin']);
        }
    }
};
