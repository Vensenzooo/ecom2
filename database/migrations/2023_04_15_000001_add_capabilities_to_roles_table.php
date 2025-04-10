<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('can_manage_books')->default(false);
            $table->boolean('can_manage_categories')->default(false);
            $table->boolean('can_manage_comments')->default(false);
            $table->boolean('can_manage_sales')->default(false);
            $table->boolean('can_view_dashboard')->default(false);
            $table->integer('max_books_per_day')->nullable();
            $table->integer('max_comments_per_day')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn([
                'can_manage_books',
                'can_manage_categories',
                'can_manage_comments',
                'can_manage_sales',
                'can_view_dashboard',
                'max_books_per_day',
                'max_comments_per_day'
            ]);
        });
    }
};
