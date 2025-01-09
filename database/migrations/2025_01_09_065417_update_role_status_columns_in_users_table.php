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
        Schema::table('users', function (Blueprint $table) {
            // Change 'role' column from enum to string
            $table->string('role')->default('user')->change();

            // Change 'status' column from enum to string
            $table->string('status')->default('1')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert 'role' column back to enum
            $table->enum('role', ['admin', 'user'])->default('user')->change();

            // Revert 'status' column back to enum
            $table->enum('status', ['active', 'inactive'])->default('active')->change();
        });
    }
};
