<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            // Change 'role' column from enum to string
            $table->string('role')->default('admin')->change();

            // Change 'status' column from enum to string
            $table->string('status')->default('1')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            // Revert 'role' column back to enum
            $table->enum('role', ['admin', 'user'])->default('admin')->change();

            // Revert 'status' column back to enum
            $table->enum('status', ['active', 'inactive'])->default('active')->change();
        });
    }
};
